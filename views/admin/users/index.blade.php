<!-- resources/views/layouts/admin/app.blade.php -->
@include('admin.partials.header')


@section('title', 'Kategori')



<div class="bg-white rounded shadow overflow-x-auto">
    <table class="min-w-full text-sm text-left text-gray-700">
        <thead class="bg-gray-100 border-b">
            <tr>
                <th class="px-4 py-3">No</th>
                <th class="px-4 py-3">Nama Users</th>
                <th class="px-4 py-3">Email</th>
                <th class="px-4 py-3">role</th>
            </tr>
        </thead>
        <tbody>
            @if (session('success'))
                <div class="bg-green-100 text-green-700 p-2 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @foreach ($users as $index => $user)
                <tr class="border-b hover:bg-gray-50">
                    <td class="px-4 py-3">{{ $index + 1 }}</td>
                    <td class="px-4 py-3">{{ $user->name }}</td>
                    <td class="px-4 py-3">{{ $user->email }}</td>
                    <td class="px-4 py-3">
                        <form action="{{ route('users.updateRole', $user->id) }}" method="POST">

                            @csrf
                            @method('PUT')
                            <select name="role" onchange="this.form.submit()"
                                class="text-sm border rounded px-2 py-1">
                                <option value="admin" {{ $user->role == 'admin' ? 'selected' : '' }}>Admin</option>
                                <option value="customer" {{ $user->role == 'customer' ? 'selected' : '' }}>Customer
                                </option>
                            </select>
                        </form>
                    </td>

                    <td class="px-4 py-3 space-x-2 flex">
                        <form action="{{route('users.destroyrole',$user->id)}}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="bg-red-600 hover:bg-red-700 text-white px-3 py-1 rounded text-xs">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach


        </tbody>
    </table>
</div>


@include('admin.partials.footer')
