@include('admin.partials.header')

@section('title', 'Produk')

<div class="w-full bg-white p-6 rounded shadow">
    <h2 class="text-xl font-semibold text-gray-700 mb-4">Tambah Produk</h2>

    <form action="{{route('products.store')}}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('post')

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Produk</label>
            <input type="text" name="product_name" id="name"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan nama Produk">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Harga</label>
            <input type="number" name="price" id="name"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan harga">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
            <input type="text" name="description" id="name"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan Deskripsi">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Stok</label>
            <input type="number" name="stock" id="name"
                class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                placeholder="Masukkan stok">
        </div>

        <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">kategori</label>
            <select name="category_id" id="">
                <option value="" >Pilih kategori</option>
                @foreach ($categories as $category )
                    <option value="{{$category->id}}">{{$category->category_name}}</option>                
                @endforeach
            </select>
        </div>


         <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Gambar</label>
            <input type="file" name="image" >
        </div>
        <div class="flex justify-end space-x-2">
            <a href="" class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">Batal</a>
            <button type="submit" class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                Simpan
            </button>
        </div>
    </form>
</div>

@include('admin.partials.footer')
