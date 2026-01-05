<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Midtrans\Notification;
use Midtrans\Config;
use App\Models\Order;
use Illuminate\Support\Facades\Log;

class MidtransController extends Controller
{
    public function notification(Request $request)
    {
        // Log incoming notification
        Log::info('Midtrans notification received', $request->all());

        try {
            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = config('midtrans.is_production');

            // Get notification data from request
            $notificationData = $request->all();
            
            // If this is from Midtrans, use Notification class
            // Otherwise, parse manually for testing
            if (isset($notificationData['signature_key'])) {
                $notification = new Notification();
                $transaction = $notification->transaction_status;
                $type = $notification->payment_type;
                $orderId = $notification->order_id;
                $fraud = $notification->fraud_status;
            } else {
                // Manual parsing for testing/development
                $transaction = $notificationData['transaction_status'] ?? '';
                $type = $notificationData['payment_type'] ?? '';
                $orderId = $notificationData['order_id'] ?? '';
                $fraud = $notificationData['fraud_status'] ?? 'accept';
            }

            Log::info('Parsed notification', [
                'order_id' => $orderId,
                'transaction_status' => $transaction,
                'payment_type' => $type,
                'fraud_status' => $fraud
            ]);

            // Parse order_id: format ORDER-{id}-{timestamp}
            $parts = explode('-', $orderId);
            $myOrderId = $parts[1] ?? null;

            Log::info('Looking for order', ['order_id' => $myOrderId]);

            $order = Order::find($myOrderId);
            if (!$order) {
                Log::error('Order not found', ['order_id' => $myOrderId]);
                return response()->json(['message' => 'Order not found'], 404);
            }

            $oldStatus = $order->status;

            // Update status berdasarkan transaction status
            if ($transaction == 'capture') {
                if ($type == 'credit_card') {
                    if ($fraud == 'accept') {
                        $order->status = 'paid';
                    }
                }
            } elseif ($transaction == 'settlement') {
                $order->status = 'paid';
            } elseif ($transaction == 'pending') {
                $order->status = 'pending';
            } elseif ($transaction == 'deny') {
                $order->status = 'failed';
            } elseif ($transaction == 'expire') {
                $order->status = 'failed';
            } elseif ($transaction == 'cancel') {
                $order->status = 'canceled';
            }

            $order->save();

            Log::info('Order status updated', [
                'order_id' => $order->id,
                'old_status' => $oldStatus,
                'new_status' => $order->status
            ]);

            return response()->json([
                'message' => 'Notification handled successfully',
                'order_id' => $order->id,
                'status' => $order->status
            ]);

        } catch (\Exception $e) {
            Log::error('Midtrans notification error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['message' => 'Error processing notification'], 500);
        }
    }
}
