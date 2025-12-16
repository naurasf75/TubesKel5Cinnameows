@include('layout.header')
@section('title', 'Home')


<section class="relative min-h-screen bg-[#FFF7ED] flex items-center">
    <div class="max-w-7xl mx-auto px-6 grid md:grid-cols-2 gap-10 items-center pt-24">

        <!-- TEXT -->
        <div>
            <h1 class="text-4xl md:text-5xl font-bold text-[#6B4F3F] leading-tight">
                Freshly Baked with <br>
                <span class="text-[#C08457]">Love & Happiness</span> 🐾
            </h1>

            <p class="mt-4 text-lg text-[#8B5E34]">
                Nikmati roti dan pastry homemade dengan rasa manis, hangat, dan penuh cinta.
            </p>

            <a href="#produk"
                class="inline-block mt-6 bg-[#C08457] hover:bg-[#A86A42] text-white px-6 py-3 rounded-full shadow transition">
                Lihat Menu 🍞
            </a>
        </div>

        <!-- IMAGE -->
        <div class="hidden md:block">
            <img src="{{ asset('images/image.jpg') }}" class="rounded-3xl shadow-lg border border-[#E7D3B0]"
                alt="Bakery">
        </div>

    </div>
</section>


<section id="produk" class="max-w-7xl mx-auto px-4 py-16">
    <!-- Judul -->
    <h1 class="text-4xl text-center font-bold text-[#6B4F3F] mb-2">
        Menu Favorit Kami 🍪
    </h1>
    <p class="text-center text-[#8B5E34] mb-10">
        Pilih camilan manis favoritmu hari ini
    </p>

    @php
        $selectedCategoryId = request('category');
    @endphp

    <div class="flex flex-wrap gap-3 mb-6 justify-center">

        {{-- SEMUA --}}
        <a href="{{ route('products.search', array_filter([
    'search' => request('search')
])) . '#produk' }}" class="
        px-5 py-2 rounded-full text-sm font-medium transition
        {{ !$selectedCategoryId
    ? 'bg-[#C08457] text-white shadow'
    : 'bg-[#FFF7ED] text-[#6B4F3F] border border-[#E7D3C0] hover:bg-[#F3E8DC]'
        }}">
            Semua
        </a>

        @foreach ($categories as $category)
                <a href="{{ route('products.search', array_filter([
                'category' => $category->id,
                'search' => request('search')
            ])) . '#produk' }}" class="
                    px-5 py-2 rounded-full text-sm font-medium transition
                    {{ $selectedCategoryId == $category->id
                ? 'bg-[#C08457] text-white shadow'
                : 'bg-[#FFF7ED] text-[#6B4F3F] border border-[#E7D3C0] hover:bg-[#F3E8DC]'
                    }}">
                    {{ $category->category_name }}
                </a>
        @endforeach

    </div>

    </div>

    <!-- Search Form -->
    <form method="GET" action="{{ route('products.search') }}#produk" class="mb-8">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari roti favoritmu..."
            class="w-full md:w-1/3 px-4 py-2 border border-[#E7D3B0] rounded-full shadow-sm focus:outline-none focus:ring-2 focus:ring-[#C08457]">
    </form>

    {{-- INFO HASIL PENCARIAN --}}
    @if(request('search'))
        @if($products->count())
            <p class="mb-6 text-center text-gray-700">
                Hasil pencarian produk
                <span class="font-semibold text-yellow-600">
                    "{{ request('search') }}"
                </span>
            </p>
        @else
            <p class="mb-6 text-center text-red-500 font-semibold">
                Produk "{{ request('search') }}" tidak ditemukan 😢
            </p>
        @endif
    @endif

    <!-- Product Grid -->
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
        @foreach ($products as $product)
            <div
                class="bg-white border border-[#E7D3B0] rounded-2xl overflow-hidden hover:shadow-lg transition flex flex-col min-h-[420px]">
                <img src="{{ asset('storage/images/' . $product->image) }}" alt="{{ $product->product_name }}"
                    class="w-full h-64 object-cover">
                <div class="p-4 flex flex-col justify-between flex-grow">
                    <div>
                        <h2 class="text-lg font-semibold text-gray-800">{{ $product->product_name }}</h2>
                        <p class="text-sm text-gray-500 mt-1">{{ Str::limit($product->description, 60) }}</p>
                        <div class="mt-2 text-yellow-600 font-bold">Rp{{ number_format($product->price, 0, ',', '.') }}
                        </div>
                    </div>

                    @auth
                        <a href="{{ route('products.show', $product->id) }}"
                            class="mt-4 inline-block text-sm text-white bg-[#C08457] hover:bg-[#A86A42] px-4 py-2 rounded-full transition self-start">
                            Lihat Detail
                        </a>

                    @else
                        <a href="{{ route('login') }}"
                            class="mt-4 inline-block text-sm text-white bg-gray-400 hover:bg-gray-500 px-4 py-2 rounded transition self-start">
                            Login untuk Detail
                        </a>
                    @endauth
                </div>

            </div>
        @endforeach
    </div>


    {{-- Pagination jika pakai --}}
    <div class="mt-8">

    </div>
</section>


@include('layout.footer')