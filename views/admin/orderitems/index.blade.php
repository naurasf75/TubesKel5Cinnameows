<!-- resources/views/layouts/admin/app.blade.php -->
@include('admin.partials.header')


@section('title', 'Order Item')


<div class="flex justify-between items-center mb-6">
    <h2 class="text-2xl font-semibold text-gray-700">Order Item</h2>

</div>

<div class="bg-white rounded shadow overflow-x-auto">
    @if (session('sucess'))
        <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
            {{ session('sucess') }}
        </div>
    @endif
    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Order Id</th>
                <th class="px-4 py-3">Produk ID</th>
                <th class="px-4 py-3">nama Produk</th>
                <th class="px-4 py-3">harga</th>
                <th class="px-4 py-3">Kuantitas</th>

            </tr>
        </thead>
        <tbody>
        <tbody>
            @foreach ($orderitems as $index => $item)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $item->order->id }}</td>
                    <td class="px-4 py-3">{{ $item->product->id }}</td>
                    <td class="px-4 py-3">{{ $item->product->product_name }}</td>
                    <td class="px-4 py-3">Rp. {{ $item->price }}</td>
                    <td class="px-4 py-3">{{ $item->quantity }}</td>
                </tr>
            @endforeach
        </tbody>




        </tbody>
    </table>
</div>


@include('admin.partials.footer')
