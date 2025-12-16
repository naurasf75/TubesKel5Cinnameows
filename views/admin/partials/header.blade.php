<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Admin Panel - @yield('title', 'Dashboard')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>

<body class="bg-gray-100 min-h-screen flex">

    <!-- SIDEBAR -->
    <aside class="w-64 bg-white shadow-md h-screen fixed top-0 left-0 z-30">
        <div class="h-16 flex items-center px-6 font-bold text-blue-600 border-b">Admin Panel</div>
        <nav class="mt-4">
            <ul class="flex flex-col space-y-1 px-4 text-sm text-gray-700 font-medium">
                <li><a href="{{route('dashboard')}}" class="block py-2 px-3 rounded hover:bg-blue-100">Dashboard</a>
                </li>
                <li><a href="{{route('categories.index')}}"
                        class="block py-2 px-3 rounded hover:bg-blue-100">Kategori</a></li>
                <li><a href="{{route('products.index')}}" class="block py-2 px-3 rounded hover:bg-blue-100">Produk</a>
                </li>
                <li><a href="{{route('admin.orders.index')}}"
                        class="block py-2 px-3 rounded hover:bg-blue-100">Pesanan</a></li>
                <li><a href="{{route('orderitems.index')}}" class="block py-2 px-3 rounded hover:bg-blue-100">Order
                        Item</a></li>
                <li>
                    <a href="{{ route('admin.reviews.index') }}" class="block py-2 px-3 rounded hover:bg-blue-100">
                        Ulasan
                    </a>
                </li>
                <li><a href="{{route('users.index')}}" class="block py-2 px-3 rounded hover:bg-blue-100">Kelola User</a>
                </li>
            </ul>
        </nav>
    </aside>

    <!-- NAVBAR -->
    <div class="fixed top-0 left-64 right-0 bg-white border-b z-20 shadow-sm">
        <div class="h-16 flex items-center justify-between px-6">
            <h2 class="text-lg font-semibold text-gray-700">@yield('title', 'Dashboard admin')</h2>
            <div class="flex items-center space-x-4">
                <span class="text-sm text-gray-600">Halo, {{ Auth::user()->name ?? 'Admin' }}</span>
                <form action="{{route('logout')}}" method="POST">
                    @csrf
                    <button type="submit" class="text-red-500 hover:underline text-sm">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <!-- MAIN CONTENT START -->
    <main class="ml-64 mt-16 p-6 flex-1">
        @yield('content')