<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Facades\DB;
use Auth;
use Midtrans\Snap;
use Midtrans\Config as MidtransConfig;

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
                $status = 'pending'; // default pending untuk transfer & ewallet
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
                    'product_image' => $item['image'],
                    'price' => $item['price'],
                    'quantity' => $item['quantity'],
                ]);

                Product::where('id', $item['product_id'])
                    ->decrement('stock', $item['quantity']);
            }

            // JIKA TRANSFER, GUNAKAN MIDTRANS
            if ($request->payment_method === 'transfer') {
                // Configure Midtrans
                MidtransConfig::$serverKey = config('midtrans.server_key');
                MidtransConfig::$isProduction = config('midtrans.is_production');
                MidtransConfig::$isSanitized = config('midtrans.is_sanitized');
                MidtransConfig::$is3ds = config('midtrans.is_3ds');

                // Prepare transaction details
                $transactionDetails = [
                    'order_id' => 'ORDER-' . $order->id . '-' . time(),
                    'gross_amount' => (int) $order->total_price,
                ];

                // Prepare item details
                $itemDetails = [];
                foreach ($cart as $item) {
                    $itemDetails[] = [
                        'id' => $item['product_id'],
                        'price' => (int) $item['price'],
                        'quantity' => (int) $item['quantity'],
                        'name' => $item['product_name'],
                    ];
                }

                // Prepare customer details
                $customerDetails = [
                    'first_name' => $request->name,
                    'email' => Auth::user()->email,
                    'phone' => $request->phone,
                    'billing_address' => [
                        'address' => $request->street,
                        'city' => $request->city,
                        'postal_code' => '',
                        'country_code' => 'IDN'
                    ],
                ];

                // Build Snap transaction params
                $params = [
                    'transaction_details' => $transactionDetails,
                    'item_details' => $itemDetails,
                    'customer_details' => $customerDetails,
                    'enabled_payments' => ['bank_transfer', 'bca_va', 'bni_va', 'bri_va', 'permata_va', 'other_va'],
                ];

                // Get Snap Token
                $snapToken = Snap::getSnapToken($params);

                session()->forget('cart');
                DB::commit();

                // Return view with snap token
                return view('checkout.payment', compact('snapToken', 'order'));
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