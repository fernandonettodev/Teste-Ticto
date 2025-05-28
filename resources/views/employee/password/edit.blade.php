@extends('layouts.employee')

@section('content')
    <h2 class="text-xl font-semibold mb-4">Alterar Senha</h2>

    @if (session('status'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">
            {{ session('status') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="bg-red-100 text-red-800 px-4 py-2 rounded mb-4">
            <ul class="list-disc pl-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('employee.password.update') }}" class="space-y-4 max-w-md">
        @csrf

        <div>
            <label class="block text-sm font-medium mb-1">Senha Atual</label>
            <input type="password" name="current_password" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Nova Senha</label>
            <input type="password" name="new_password" class="w-full border rounded p-2" required>
        </div>

        <div>
            <label class="block text-sm font-medium mb-1">Confirmar Nova Senha</label>
            <input type="password" name="new_password_confirmation" class="w-full border rounded p-2" required>
        </div>

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700 transition">
            Alterar Senha
        </button>
    </form>
@endsection
