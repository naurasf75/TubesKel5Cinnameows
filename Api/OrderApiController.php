<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Product;
use Auth;
use DB;

class OrderApiController extends Controller
{
    public function index()
    {
        return response()->json(
            Order::where('user_id', Auth::id())->with('items')->get()
        );
    }

    public function placeOrder(Request $request)
    {
        $request->validate([
            'items' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $total = 0;
            foreach ($request->items as $i) {
                $product = Product::findOrFail($i['product_id']);
                $total += $product->price * $i['qty'];
            }

            $order = Order::create([
                'user_id'=>Auth::id(),
                'total_price'=>$total,
                'status'=>'pending'
            ]);

            DB::commit();

            return response()->json([
                'message'=>'Order berhasil dibuat',
                'order'=>$order
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message'=>$e->getMessage()], 500);
        }
    }
}
