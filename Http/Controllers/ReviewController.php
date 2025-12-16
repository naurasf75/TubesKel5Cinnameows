<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OrderItem;
use App\Models\Review;

class ReviewController extends Controller
{
    public function create(OrderItem $orderItem)
    {
        // 🔒 pastikan order completed
        if ($orderItem->order->status !== 'completed') {
            abort(403, 'Pesanan belum selesai');
        }

        // 🔒 cegah review ganda
        $exists = Review::where('order_id', $orderItem->order_id)
            ->where('product_id', $orderItem->product_id)
            ->exists();

        if ($exists) {
            return redirect()->route('orders.index')
                ->with('error', 'Produk ini sudah kamu review');
        }

        return view('reviews.create', compact('orderItem'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'order_id' => 'required|integer',
            'product_id' => 'required|integer',
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string'
        ]);

        $orderItem = OrderItem::where('order_id', $request->order_id)
            ->where('product_id', $request->product_id)
            ->firstOrFail(); // 🔥 penting

        Review::create([
            'user_id' => auth()->id(),
            'order_id' => $orderItem->order_id,
            'product_id' => $orderItem->product_id,
            'product_name' => $orderItem->product_name,
            'rating' => $request->rating,
            'review' => $request->review,
        ]);

        return redirect()->route('orders.index')
            ->with('success', 'Terima kasih atas ulasan kamu ⭐');
    }


    // ADMIN
    public function reply(Request $request, Review $review)
    {
        $review->update([
            'admin_reply' => $request->admin_reply
        ]);

        return back()->with('success', 'Balasan berhasil dikirim');
    }

    public function show($orderId, $productId)
    {
        $review = Review::with(['product'])
            ->where('order_id', $orderId)
            ->where('product_id', $productId)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        return view('reviews.show', compact('review'));
    }

    // ADMIN - LIST REVIEW
    public function index()
    {
        $reviews = Review::with(['user', 'product'])
            ->latest()
            ->get();

        return view('admin.reviews.index', compact('reviews'));
    }

}