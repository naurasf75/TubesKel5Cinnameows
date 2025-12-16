@extends('layout.header')

@section('content')

    <div class="max-w-5xl mx-auto p-5 mt-20">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#6B4F3F]">Checkout</h1>
            <a href="{{ route('home') }}" class="text-sm text-[#C08457] hover:underline">
                ← Kembali
            </a>
        </div>
        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="grid md:grid-cols-3 gap-6">
            <!-- PRODUK -->
            <div class="md:col-span-2 space-y-4">
                @foreach($cart as $item)
                    <div class="bg-white rounded-xl shadow-sm p-4 flex gap-4">
                        <img src="{{ asset('storage/images/' . $item['image']) }}" class="w-20 h-20 object-cover rounded">

                        <div class="flex-1">
                            <h3 class="font-semibold">{{ $item['product_name'] }}</h3>
                            <p class="text-sm text-gray-500">Qty: {{ $item['quantity'] }}</p>
                        </div>

                        <div class="font-semibold text-[#6B4F3F]">
                            Rp{{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                        </div>
                    </div>
                @endforeach
            </div>

            {{-- FORM CHECKOUT --}}
            <!-- FORM -->
            <div class="bg-white rounded-xl shadow p-6">
                <h3 class="font-bold text-lg mb-4">Detail Pengiriman</h3>

                <form method="POST" action="{{ route('checkout.payment') }}" class="space-y-3">
                    @csrf

                    <input type="text" name="name" placeholder="Nama Lengkap" class="w-full border rounded p-2 mb-3"
                        required> <textarea name="street" placeholder="Alamat Lengkap (Jalan, Rt/Rw"
                        class="w-full border rounded p-2 mb-3" required></textarea> <input type="text" name="district"
                        placeholder="Kecamatan" class="w-full border rounded p-2 mb-3" required> <input type="text"
                        name="city" placeholder="Kabupaten / Kota" class="w-full border rounded p-2 mb-3" required> <input
                        type="text" name="province" placeholder="Provinsi" class="w-full border rounded p-2 mb-3" required>
                    <input type="text" name="phone" inputmode="numeric" pattern="[0-9]{10,13}"
                        placeholder="Contoh: 081234567890" class="field w-full border p-2 rounded mb-2" required>

                    <select name="payment_method" class="w-full border rounded p-2">
                        <option value="">Pilih Metode Pembayaran</option>
                        <option value="cod">COD</option>
                        <option value="transfer">Transfer</option>
                    </select>

                    <div class="flex justify-between font-semibold border-t pt-3">
                        <span>Total</span>
                        <span class="text-[#C08457]">
                            Rp{{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <button class="w-full bg-[#C08457] hover:bg-[#a86a42] text-white py-3 rounded-full font-semibold">
                        🥐 Bayar 🥐
                    </button>
                </form>
            </div>
        </div>
@endsection