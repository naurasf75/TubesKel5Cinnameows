<!-- resources/views/layouts/admin/app.blade.php -->
@include('admin.partials.header')

   
@section('title', 'Kategori')


    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-semibold text-gray-700">Daftar Kategori</h2>
        <a href="{{route('categories.create')}}" class="bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium px-4 py-2 rounded shadow">
            + Tambah Data
        </a>
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
                    <th class="px-4 py-3">Nama Kategori</th>
                    <th class="px-4 py-3">Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($category as $index => $categori )
                 <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{$index+1}}</td>
                    <td class="px-4 py-3">{{$categori->category_name}}</td>
                    <td class="px-4 py-3 space-x-2 flex">
                        <div>
                            <a href="{{route('categories.edit',$categori->id)}}" class="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-xs">Edit</a>
                        </div>
                        <form action="{{route('categories.destroy',$categori->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                           <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
                
                @endforeach
               
            </tbody>
        </table>
    </div>


@include('admin.partials.footer')
