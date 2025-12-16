<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <div class="w-full max-w-md p-8 bg-white rounded shadow">
        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Register Akun</h2>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('register') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama</label>
                <input type="text" id="name" name="name" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    value="" autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    value="" autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500" autocomplete="off">
            </div>

            <div class="mb-6">
                <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500">
            </div>

            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                Daftar
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">
            Sudah punya akun? <a href="{{route('login')}}" class="text-yellow-500 hover:underline">Login di sini</a>
        </p>
    </div>

</body>
</html>
