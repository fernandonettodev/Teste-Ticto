<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Admin - @yield('title', 'Dashboard')</title>
    <!-- Tailwind CDN (ou seu build via vite/mix) -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex flex-col">

    {{-- Header --}}
    <header class="bg-white shadow p-4 flex justify-between items-center">
        <div class="font-bold text-xl text-blue-700">Meu Sistema Admin</div>
        <nav>
            <a href="{{ route('admin.dashboard') }}" class="text-gray-700 hover:text-blue-600 px-3">Dashboard</a>
            <a href="{{ route('admin.reports.time-records') }}" class="text-gray-700 hover:text-blue-600 px-3">Relatório de Ponto</a>
            <a href="{{ route('admin.employees.index') }}" class="text-gray-700 hover:text-blue-600 px-3">Funcionários</a>
            <form id="logout-form" action="{{ route('admin.logout') }}" method="POST" style="display: none;">
                @csrf
            </form>

            <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="text-red-600 hover:text-red-800 px-3">
                Sair
            </a>
        </nav>
    </header>

    {{-- Conteúdo principal --}}
    <main class="flex-grow container mx-auto px-4 py-6">
        @yield('content')
    </main>

    {{-- Footer opcional --}}
    <footer class="bg-white text-center p-4 text-sm text-gray-500">
        &copy; {{ date('Y') }} Meu Sistema
    </footer>

</body>
</html>
