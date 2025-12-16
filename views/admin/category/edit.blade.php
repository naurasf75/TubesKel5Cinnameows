
@include('admin.partials.header')

   
@section('title', 'Kategori')

<div class="w-full bg-white p-6 rounded shadow">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">Edit Kategori</h2>

        <form action="{{route('categories.update',$category->id)}}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Kategori</label>
                <input type="text" name="category_name" id="name"
                       class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-blue-500"
                       value="{{$category->category_name}}">
            </div>

            <div class="flex justify-end space-x-2">
                <a href="{{route('categories.index')}}" class="px-4 py-2 text-sm bg-gray-200 hover:bg-gray-300 text-gray-700 rounded">Batal</a>
                <button type="submit"
                        class="px-4 py-2 text-sm bg-blue-600 hover:bg-blue-700 text-white rounded shadow">
                    Simpan
                </button>
            </div>
        </form>
    </div>
  


@include('admin.partials.footer')
