<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Área do Funcionário</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 text-gray-800">
    <header class="bg-white shadow p-4">
        <div class="container mx-auto flex justify-between items-center">
            <h1 class="text-lg font-semibold">Painel do Funcionário</h1>

            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    {{ auth('employee')->user()->name }}
                </span>

                <a href="{{ route('employee.password.change') }}"
                   class="text-sm text-blue-600 hover:underline">
                   Alterar Senha
                </a>

                <form action="{{ route('employee.logout') }}" method="POST" class="inline">
                    @csrf
                    <button
                        class="text-sm px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600 transition"
                        onclick="return confirm('Deseja realmente sair?')"
                    >
                        Sair
                    </button>
                </form>
            </div>
        </div>
    </header>

    <main class="py-8">
        <div class="container mx-auto px-4">
            @yield('content')
        </div>
    </main>
</body>
</html>
