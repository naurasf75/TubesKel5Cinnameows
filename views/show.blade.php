@include('layout.header')<!-- component -->

<section class="bg-[#FFF7ED] py-24">
    <div class="container px-5 py-24 mx-auto">
        <div class="lg:w-4/5 mx-auto flex flex-wrap">
            <img class="lg:w-1/2 w-full object-cover rounded-3xl border border-[#E7D3B0] shadow"
                src="{{ asset('storage/images/' . $product->image) }}">

            <div class="lg:w-1/2 w-full lg:pl-10 lg:py-6 mt-6 lg:mt-0">
                <h2 class="text-sm title-font text-gray-500 tracking-widest">{{ $product->category->category_name }}
                </h2>
                <h1 class="text-gray-900 text-3xl title-font font-medium mb-1">{{ $product->product_name }}</h1>
                <span>Stok : {{ $product->stock }}</span>
                <div class="flex mb-4">
                </div>
                <p class="leading-relaxed">{{ $product->description }}</p>
                <div class="flex mt-6 items-center pb-5 border-b-2 border-gray-200 mb-5">

                </div>
                <div class="flex">
                    <span id="totalPrice" data-price="{{ $product->price }}"
                        class="title-font font-medium text-2xl text-gray-900">
                        Rp{{ number_format($product->price, 0, ',', '.') }}
                    </span>

                    <form action="{{ route('cart.add') }}" method="POST" class="mt-4">
                        @csrf
                        <input type="hidden" name="product_id" value="{{ $product->id }}">

                        <label for="quantity" class="block mb-2 font-semibold">Jumlah:</label>
                        <input type="number" id="quantity" name="quantity" value="1" min="1" max="{{ $product->stock }}"
                            class="w-20 px-2 py-1 border rounded" />

                        <button
                            class="mt-4 bg-[#C08457] hover:bg-[#A86A42] text-white px-6 py-3 rounded-full transition">
                            Masukkan Keranjang 🛒
                        </button>

                    </form>
                    <!--
                    <button
                        class="rounded-full w-10 h-10 bg-gray-200 p-0 border-0 inline-flex items-center justify-center text-gray-500 ml-4">
                        <svg fill="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            class="w-5 h-5" viewBox="0 0 24 24">
                            <path
                                d="M20.84 4.61a5.5 5.5 0 00-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 00-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 000-7.78z">
                            </path>
                        </svg>
                    </button> -->
                </div>
                <hr class="my-10">
            </div>
        </div>
    </div>
</section>

<section class="bg-white py-16">
    <div class="max-w-5xl mx-auto px-5">

        <h2 class="text-3xl font-bold text-[#6B4F3F] mb-8 text-center">
            Ulasan Pelanggan 💬
        </h2>

        @forelse($product->reviews as $review)
            <div class="border border-[#E7D3B0] rounded-2xl p-6 mb-6 shadow-sm">

                <div class="flex items-center justify-between">
                    <div class="font-semibold text-[#6B4F3F]">
                        {{ $review->user->name }}
                    </div>

                    <div class="text-[#C08457] text-sm">
                        {{ str_repeat('⭐', $review->rating) }}
                    </div>
                </div>

                <p class="mt-3 text-[#6B4F3F] leading-relaxed">
                    {{ $review->review }}
                </p>

                @if($review->admin_reply)
                    <div class="mt-4 bg-[#FFF7ED] border-l-4 border-[#C08457] p-4 rounded">
                        <p class="text-sm font-semibold text-[#6B4F3F]">
                            Balasan Admin
                        </p>
                        <p class="text-sm text-[#8B5E34] mt-1">
                            {{ $review->admin_reply }}
                        </p>
                    </div>
                @endif

            </div>
        @empty
            <p class="text-center text-[#8B5E34]">
                Belum ada ulasan untuk produk ini 😿
            </p>
        @endforelse

    </div>
</section>


<script>
    const qtyInput = document.getElementById('quantity');
    const priceEl = document.getElementById('totalPrice');

    const basePrice = parseInt(priceEl.dataset.price);

    function formatRupiah(angka) {
        return 'Rp' + angka.toLocaleString('id-ID');
    }

    qtyInput.addEventListener('input', function () {
        let qty = parseInt(this.value);

        if (qty < 1 || isNaN(qty)) {
            qty = 1;
            this.value = 1;
        }

        const total = basePrice * qty;
        priceEl.textContent = formatRupiah(total);
    });
</script>

@include('layout.footer')