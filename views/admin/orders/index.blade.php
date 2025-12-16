<!-- resources/views/admin/orders/index.blade.php -->
@include('admin.partials.header')

@section('title', 'Daftar Pesanan')

<div class="mb-6">
    <h2 class="text-2xl font-semibold text-gray-700">Daftar Pesanan</h2>
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
                <th class="px-4 py-3">Tanggal</th>
                <th class="px-4 py-3">Alamat</th>
                <th class="px-4 py-3">Produk</th>
                <th class="px-4 py-3">Total</th>
                <th class="px-4 py-3">Pembayaran</th>
                <th class="px-4 py-3">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($orders as $index => $order)
                <tr class="border-b align-top hover:bg-gray-50">
                    <!-- NO -->
                    <td class="px-4 py-3">{{ $index + 1 }}</td>

                    <!-- USER -->
                    <td class="px-4 py-3 font-medium">
                        {{ $order->user->name }}
                        <div class="text-xs text-gray-500">
                            {{ $order->phone }}
                        </div>
                    </td>
                    <td class="px-4 py-3">
                        <div class="text-sm">
                            {{ $order->created_at->format('d M Y') }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $order->created_at->format('H:i') }}
                        </div>
                    </td>

                    <!-- ALAMAT -->
                    <td class="px-4 py-3">
                        {{ $order->address }}
                    </td>

                    <!-- PRODUK -->
                    <td class="px-4 py-3">
                        <ul class="space-y-1">
                            @foreach ($order->items as $item)
                                <li>
                                    • {{ $item->product_name }}
                                    <span class="text-xs text-gray-500">
                                        ({{ $item->quantity }} x Rp {{ number_format($item->price, 0, ',', '.') }})
                                    </span>
                                </li>
                            @endforeach
                        </ul>
                    </td>

                    <!-- TOTAL -->
                    <td class="px-4 py-3 font-semibold">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </td>

                    <!-- METODE PEMBAYARAN -->
                    <td class="px-4 py-3 capitalize">
                        {{ $order->payment_method }}
                    </td>

                    <!-- STATUS -->
                    <td class="px-4 py-3">
                        <form method="POST" action="{{ route('admin.orders.updateStatus', $order->id) }}">
                            @csrf
                            @method('PATCH')

                            @php
                                $statusOptions = [
                                    'pending' => 'Menunggu Pembayaran',
                                    'paid' => 'Sudah Dibayar',
                                    'processed' => 'Diproses',
                                    'shipped' => 'Dikirim',
                                    'completed' => 'Selesai',
                                    'canceled' => 'Dibatalkan',
                                ];
                            @endphp

                            <select name="status" class="border rounded p-1 text-sm">
                                @foreach($statusOptions as $value => $label)
                                    <option value="{{ $value }}" {{ $order->status == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>

                            <button class="ml-2 bg-orange-500 hover:bg-orange-600 text-white px-2 py-1 rounded text-xs">
                                Update
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center py-6 text-gray-500">
                        Belum ada pesanan
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

@include('admin.partials.footer')