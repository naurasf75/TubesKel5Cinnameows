<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Auth;

class CartController extends Controller
{
    public function add(Request $request)
    {
        $productId = $request->input('product_id');
        $quantity = $request->input('quantity', 1);

        $product = Product::findOrFail($productId);

        // Ambil cart dari session, jika belum ada buat array kosong
        $cart = session()->get('cart', []);

        // Jika produk sudah ada di cart, tambah quantity
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $quantity;
        } else {
            // Jika belum ada, tambahkan produk baru ke cart
            $cart[$productId] = [
                "product_id" => $productId,
                "product_name" => $product->product_name,
                "price" => $product->price,
                "quantity" => $quantity,
                "image" => $product->image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Produk berhasil ditambahkan ke keranjang!');
    }

    public function update(Request $request)
    {
        $cart = session()->get('cart', []);

        $key = $request->key;

        if (!isset($cart[$key])) {
            return back()->with('info', 'Produk tidak ditemukan di keranjang');
        }

        if ($request->action === 'plus') {
            $cart[$key]['quantity']++;
        }

        if ($request->action === 'minus') {
            if ($cart[$key]['quantity'] > 1) {
                $cart[$key]['quantity']--;
            }
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Jumlah produk diperbarui');
    }


    public function index()
    {
        $cart = session()->get('cart', []);

        return view('cart.index', compact('cart'));
    }

    public function remove($key)
    {
        $cart = session()->get('cart', []);

        if (isset($cart[$key])) {
            unset($cart[$key]);
            session()->put('cart', $cart);
            return redirect()->back()->with('success', 'Produk berhasil dihapus dari keranjang.');
        }

        return redirect()->back()->with('error', 'Produk tidak ditemukan di keranjang.');
    }

    public function showCheckout()
    {
        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong');
        }

        $total = collect($cart)->sum(function ($item) {
            return $item['price'] * $item['quantity'];
        });

        return view('checkout.index', compact('cart', 'total'));
    }

    public function updateQuantity(Request $request)
    {
        $cart = session()->get('cart', []);
        $key = $request->key;

        if (!isset($cart[$key])) {
            return back()->with('error', 'Item tidak ditemukan');
        }

        if ($request->action === 'plus') {
            $cart[$key]['quantity']++;
        }

        if ($request->action === 'minus') {
            $cart[$key]['quantity']--;

            if ($cart[$key]['quantity'] <= 0) {
                unset($cart[$key]);
            }
        }

        session()->put('cart', $cart);

        return back()->with('info', 'Jumlah pesanan diperbarui');
    }


    public function processPayment(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'street' => 'required|string',
            'district' => 'required|string',
            'city' => 'required|string',
            'province' => 'required|string',
            'phone' => [
                'required',
                'regex:/^[0-9]{10,13}$/'
            ],
            'payment_method' => 'required|in:cod,ewallet,transfer',
        ], [
            'phone.regex' => 'Nomor HP harus terdiri dari 10 sampai 13 digit angka.',
        ]);


        $cart = session()->get('cart', []);

        if (empty($cart)) {
            return redirect()->route('cart.index')
                ->with('error', 'Keranjang kosong.');
        }

        DB::beginTransaction();

        try {

            $total = 0;

            // VALIDASI STOK
            foreach ($cart as $item) {
                $product = Product::lockForUpdate()
                    ->findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception(
                        "Stok {$product->product_name} tidak cukup!"
                    );
                }

                $total += $item['price'] * $item['quantity'];
            }

            // 🔑 LOGIKA STATUS & PESAN
            if ($request->payment_method === 'cod') {
                $status = 'processed';
                $message = '🕒 Pesanan berhasil dibuat. Silakan bayar saat barang diterima.';
            } else {
                $status = 'paid';
                $message = '✅ Pembayaran berhasil. Pesanan sedang diproses.';
            }

            // SIMPAN ORDER
            $order = Order::create([
                'user_id' => Auth::id(),
                'total_price' => $total,
                'name' => $request->name,
                'address' =>
                    $request->street . ', ' .
                    'Kec. ' . $request->district . ', ' .
                    $request->city . ', ' .
                    'Prov. ' . $request->province,
                'phone' => $request->phone,
                'payment_method' => $request->payment_method,
                'status' => $status
            ]);

            // SIMPAN ORDER ITEMS + POTONG STOK
            foreach ($cart as $item) {

                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_name' => $item['product_name'],
                    'product_image' => $product->image,
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            session()->forget('cart');

            DB::commit();

            return redirect()->route('orders.index')
                ->with('success', $message);

        } catch (\Exception $e) {

            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
}