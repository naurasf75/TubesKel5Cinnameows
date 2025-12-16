<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Login</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">


    <div class="w-full max-w-md p-8 bg-white rounded shadow">
        <a href="{{route('home') }}"
            class="absolute top-4 left-4 px-4 py-2  text-gray-700 rounded hover:bg-gray-100 transition text-sm">
            &larr; Kembali
        </a>

        <h2 class="text-2xl font-bold text-center text-gray-700 mb-6">Login Akun</h2>


        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" id="email" name="email" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    value="" autocomplete="off">
            </div>

            <div class="mb-4">
                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" id="password" name="password" required
                    class="w-full px-4 py-2 border rounded focus:outline-none focus:ring-2 focus:ring-yellow-500"
                    autocomplete="off">
            </div>


            <button type="submit"
                class="w-full bg-yellow-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded shadow">
                Login
            </button>
        </form>

        <p class="mt-4 text-sm text-center text-gray-600">
            Belum punya akun? <a href="{{ route('showRegister') }}" class="text-yellow-500 hover:underline">register di
                sini</a>
        </p>
    </div>

</body>

</html>
