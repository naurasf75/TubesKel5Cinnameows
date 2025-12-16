<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
    // =====================
    // USER - RIWAYAT PESANAN
    // =====================
    public function index()
    {
        $orders = Order::with('items.product')
            ->where('user_id', auth()->id()) // 🔥 FILTER USER
            ->latest()
            ->get();

        return view('orders.index', compact('orders'));
    }

    // =====================
    // ADMIN - SEMUA PESANAN
    // =====================
    public function adminIndex()
    {
        $orders = Order::with('user', 'items')
            ->latest()
            ->get();

        return view('admin.orders.index', compact('orders'));
    }

    // =====================
    // ADMIN - UPDATE STATUS
    // =====================
    public function updateStatus(Request $request, Order $order)
    {
        $request->validate([
            'status' => 'required|in:pending,paid,processed,shipped,completed,canceled'
        ]);

        $order->update([
            'status' => $request->status
        ]);

        return back()->with('success', 'Status pesanan berhasil diupdate');
    }
}
