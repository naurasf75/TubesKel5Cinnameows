@extends('layout.header')
@section('content')

    <div class="max-w-4xl mx-auto mt-24 px-4">
        <div class="bg-white rounded-xl shadow p-6">

            <div class="flex gap-6 mb-6">
                <img src="{{ asset('storage/images/' . $orderItem->product->image) }}"
                    class="w-32 h-32 rounded-lg object-cover">

                <div>
                    <h3 class="text-xl font-semibold">{{ $orderItem->product_name }}</h3>
                    <p class="text-[#C08457] font-bold">
                        Rp{{ number_format($orderItem->price, 0, ',', '.') }}
                    </p>

                    <div class="flex gap-1 text-2xl mt-3">
                        @for($i = 1; $i <= 5; $i++)
                            <span class="star cursor-pointer text-gray-300" data-value="{{ $i }}">★</span>
                        @endfor
                    </div>
                </div>
            </div>

            <form method="POST" action="{{ route('reviews.store') }}">
                @csrf
                <input type="hidden" name="order_id" value="{{ $orderItem->order_id }}">
                <input type="hidden" name="product_id" value="{{ $orderItem->product_id }}">
                <input type="hidden" name="rating" id="rating" required>

                <textarea name="review" rows="4" class="w-full border rounded-lg p-3 focus:ring focus:ring-[#C08457]"
                    placeholder="Bagaimana rasa produknya? 😋"></textarea>

                <div class="text-right mt-4">
                    <button class="bg-[#C08457] text-white px-6 py-2 rounded-full">
                        Kirim Ulasan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const stars = document.querySelectorAll('.star');
        const rating = document.getElementById('rating');
        stars.forEach((s, i) => {
            s.onclick = () => {
                rating.value = i + 1;
                stars.forEach((x, j) => {
                    x.classList.toggle('text-yellow-400', j <= i)
                })
            }
        })
    </script>
@endsection