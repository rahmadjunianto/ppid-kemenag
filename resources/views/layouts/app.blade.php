<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PPID Kementerian Agama')</title>
    <!-- CDN Tailwind CSS & Alpine.js -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <!-- FontAwesome / Bootstrap Icons jika diperlukan -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body class="bg-slate-100 text-slate-800 antialiased">

    <!-- Panggil Navbar Dinamis yang terhubung ke CRUD Menu Filament -->
    @include('layouts.navigation')

    <!-- Konten Utama yang dinamis dari halaman lain -->
    <main>
        @yield('content')
    </main>

    <!-- Footer atau Script tambahan jika ada -->
    @stack('scripts')
</body>
</html>