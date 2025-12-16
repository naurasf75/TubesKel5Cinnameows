@extends('layout.header')

@section('content')
<div class="max-w-4xl mx-auto mt-24 px-4">

    <div class="bg-white rounded-xl shadow p-6">

        <h2 class="text-2xl font-bold text-[#6B4F3F] mb-6">
            Ulasan Produk ⭐
        </h2>

        {{-- PRODUK --}}
        <div class="flex gap-6 items-center mb-6">
            <img src="{{ asset('storage/images/'.$review->product->image) }}"
                class="w-28 h-28 rounded-lg object-cover border">

            <div>
                <h3 class="text-xl font-semibold">
                    {{ $review->product_name }}
                </h3>

                <div class="text-yellow-400 text-xl mt-1">
                    {{ str_repeat('★',$review->rating) }}
                    <span class="text-gray-300">
                        {{ str_repeat('★',5-$review->rating) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- ULASAN USER --}}
        <div class="mb-6">
            <p class="font-medium mb-2">Ulasan kamu</p>
            <div class="bg-[#FFF7ED] p-4 rounded-lg text-gray-700">
                {{ $review->review }}
            </div>
        </div>

        {{-- BALASAN ADMIN --}}
        <div>
            <p class="font-medium mb-2">Balasan Admin</p>

            @if($review->admin_reply)
                <div class="bg-green-50 text-green-700 p-4 rounded-lg">
                    {{ $review->admin_reply }}
                </div>
            @else
                <p class="text-gray-400 italic">
                    Admin belum membalas ulasan ini.
                </p>
            @endif
        </div>

        <div class="mt-6">
            <a href="{{ route('orders.index') }}"
               class="text-sm text-[#C08457] hover:underline">
                ← Kembali ke Riwayat Pesanan
            </a>
        </div>
    </div>
</div>
@endsection
