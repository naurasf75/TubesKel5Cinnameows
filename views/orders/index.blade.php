@extends('layout.header')

@section('content')
    <div class="max-w-6xl mx-auto mt-24 px-4">

        {{-- ALERT --}}
        @foreach (['success' => 'green', 'error' => 'red'] as $type => $color)
            @if(session($type))
                <div class="mb-4 bg-{{ $color }}-100 text-{{ $color }}-700 p-3 rounded-lg">
                    {{ session($type) }}
                </div>
            @endif
        @endforeach

        <div class="flex justify-between items-center mb-8">
            <h1 class="text-3xl font-bold text-[#6B4F3F]">Riwayat Pesanan 📦</h1>
            <a href="{{ route('home') }}" class="text-sm text-[#C08457] hover:underline">
                ← Kembali
            </a>
        </div>

        @forelse($orders as $order)

            <div class="bg-white rounded-xl shadow-sm mb-8 overflow-hidden">

                {{-- HEADER --}}
                <div class="flex justify-between items-center px-5 py-4 bg-[#FFF7ED]">
                    @php
                        $statusColor = [
                            'pending' => 'bg-gray-200 text-gray-700',
                            'paid' => 'bg-blue-100 text-blue-700',
                            'processed' => 'bg-yellow-100 text-yellow-700',
                            'shipped' => 'bg-indigo-100 text-indigo-700',
                            'completed' => 'bg-green-100 text-green-700',
                            'canceled' => 'bg-red-100 text-red-700',
                        ];
                    @endphp

                    <span class="px-3 py-1 text-sm rounded-full {{ $statusColor[$order->status] ?? '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                    <div class="text-xs text-gray-500">
                        🗓 {{ $order->created_at->format('d M Y') }}
                    </div>
                    <span class="font-semibold text-[#6B4F3F]">
                        Rp{{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>

                {{-- INFO --}}
                <div class="px-5 py-3 text-sm text-gray-600 space-y-1">
                    <div>📍 {{ $order->address }}</div>
                    <div>👤 {{ $order->name }} · 📞 {{ $order->phone }}</div>
                </div>

                {{-- ITEMS --}}
                <div class="divide-y">
                    @foreach($order->items as $item)

                        @php
                            $hasReview = \App\Models\Review::where('order_id', $order->id)
                                ->where('product_id', $item->product_id)
                                ->exists();
                        @endphp

                        <div class="flex gap-4 items-center px-5 py-4">
                            <img src="{{ asset('storage/images/' . $item->product->image) }}"
                                class="w-20 h-20 rounded-lg object-cover border">

                            <div class="flex-1">
                                <p class="font-semibold">{{ $item->product_name }}</p>
                                <p class="text-sm text-gray-500">
                                    Qty {{ $item->quantity }} ·
                                    Rp{{ number_format($item->price, 0, ',', '.') }}
                                </p>
                            </div>

                            <div class="flex gap-2">
                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $item->product_id }}">
                                    <input type="hidden" name="quantity" value="1">
                                    <button class="px-4 py-2 text-sm bg-[#C08457] hover:bg-[#a86a42] text-white rounded-full">
                                        Beli Lagi
                                    </button>
                                </form>

                                @if($order->status === 'completed' && !$hasReview)
                                    <a href="{{ route('reviews.create', $item->id) }}"
                                        class="px-4 py-2 text-sm bg-blue-500 hover:bg-blue-600 text-white rounded-full">
                                        Review
                                    </a>
                                @elseif($hasReview)
                                    <a href="{{ route('reviews.show', [$order->id, $item->product_id]) }}"
                                        class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 rounded-full">
                                        Lihat Review
                                    </a>
                                @endif
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

        @empty
            <p class="text-center text-gray-500 mt-20">
                Kamu belum punya pesanan 😿
            </p>
        @endforelse
    </div>
@endsection
