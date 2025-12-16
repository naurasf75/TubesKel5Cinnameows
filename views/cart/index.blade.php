@extends('layout.header')
@section('title', 'Keranjang')

@section('content')
    <div class="max-w-6xl mx-auto mt-24 px-4">

        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-[#6B4F3F]">Keranjang Belanja 🛒</h1>
            <a href="{{ route('home') }}" class="text-sm text-[#C08457] hover:underline">
                ← Kembali Belanja
            </a>
        </div>

        @if(count($cart))
            <div class="space-y-4">
                @php $total = 0; @endphp

                @foreach($cart as $key => $item)
                    @php $subtotal = $item['price'] * $item['quantity']; @endphp
                    <div class="bg-white rounded-xl shadow-sm p-4 flex gap-4 items-center">

                        <img src="{{ asset('storage/images/' . $item['image']) }}" class="w-24 h-24 object-cover rounded-lg border">

                        <div class="flex-1">
                            <h3 class="font-semibold text-lg">{{ $item['product_name'] }}</h3>
                            <p class="text-sm text-gray-500">
                                Rp{{ number_format($item['price'], 0, ',', '.') }}
                            </p>

                            <form action="{{ route('cart.update') }}" method="POST" class="flex items-center gap-3 mt-3">
                                @csrf
                                <input type="hidden" name="key" value="{{ $key }}">

                                <button name="action" value="minus" class="px-3 py-1 bg-[#FFF1E6] rounded-full">−</button>

                                <span class="font-medium">{{ $item['quantity'] }}</span>

                                <button name="action" value="plus" class="px-3 py-1 bg-[#FFF1E6] rounded-full">+</button>
                            </form>
                        </div>

                        <div class="text-right">
                            <p class="font-bold text-[#6B4F3F]">
                                Rp{{ number_format($subtotal, 0, ',', '.') }}
                            </p>

                            <form action="{{ route('cart.remove', $key) }}" method="POST">
                                @csrf @method('DELETE')
                                <button class="text-sm text-red-500 hover:underline mt-2">
                                    Hapus
                                </button>
                            </form>
                        </div>
                    </div>
                    @php $total += $subtotal; @endphp
                @endforeach
            </div>

            <div class="bg-white mt-8 p-6 rounded-xl shadow flex justify-between items-center">
                <span class="text-lg font-semibold">Total</span>
                <span class="text-2xl font-bold text-[#C08457]">
                    Rp{{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <div class="mt-6 text-right">
                <a href="{{ route('checkout.form') }}"
                    class="inline-block bg-[#C08457] hover:bg-[#a86a42] text-white px-8 py-3 rounded-full font-semibold transition">
                    Checkout →
                </a>
            </div>
        @else
            <p class="text-center text-gray-500 mt-20">
                Keranjang kamu masih kosong 🥐
            </p>
        @endif
    </div>

    @include('layout.footer')
@endsection