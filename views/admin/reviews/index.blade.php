@include('admin.partials.header')

@section('title', 'Daftar Ulasan Produk')

<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-700">Daftar Ulasan Produk</h2>
</div>

@if (session('success'))
    <div class="bg-green-100 text-green-700 p-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">User</th>
                <th class="px-4 py-3">Produk</th>
                <th class="px-4 py-3">Rating</th>
                <th class="px-4 py-3">Ulasan</th>
                <th class="px-4 py-3">Balasan Admin</th>
                <th class="px-4 py-3">Aksi</th>
            </tr>
        </thead>

        <tbody>
            @forelse ($reviews as $index => $review)
                <tr class="border-b align-top hover:bg-gray-50">

                    <!-- NO -->
                    <td class="px-4 py-3">
                        {{ $index + 1 }}
                    </td>

                    <!-- USER -->
                    <td class="px-4 py-3 font-medium">
                        {{ $review->user->name }}
                        <div class="text-xs text-gray-500">
                            {{ $review->user->email ?? '-' }}
                        </div>
                    </td>

                    <!-- PRODUK -->
                    <td class="px-4 py-3 font-medium">
                        {{ $review->product_name }}

                        <div class="text-xs text-gray-500">
                            Order #{{ $review->order_id }}
                        </div>
                    </td>

                    <!-- RATING -->
                    <td class="px-4 py-3 text-yellow-500">
                        {{ str_repeat('★', $review->rating) }}
                    </td>

                    <!-- ULASAN -->
                    <td class="px-4 py-3 text-gray-700">
                        {{ $review->review }}
                    </td>

                    <!-- BALASAN ADMIN -->
                    <td class="px-4 py-3">
                        @if ($review->admin_reply)
                            <span class="text-green-600 text-sm">
                                {{ $review->admin_reply }}
                            </span>
                        @else
                            <span class="text-gray-400 italic text-sm">
                                Belum dibalas
                            </span>
                        @endif
                    </td>

                    <!-- AKSI -->
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.reviews.reply', $review->id) }}" class="space-y-2">
                            @csrf
                            @method('PATCH')

                            <textarea name="admin_reply" rows="2" class="w-56 border rounded p-2 text-sm"
                                placeholder="Balas ulasan...">{{ $review->admin_reply }}</textarea>

                            <button type="submit"
                                class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-xs">
                                Kirim
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Belum ada ulasan produk
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.partials.footer')