@extends('layouts.admin')

@section('title', 'Gestão de Funcionários') 

@section('content')
<div class="p-6">
    <h1 class="text-2xl font-bold mb-4">Gestão de Funcionários</h1>

    @if(session('message'))
        <div class="bg-green-100 text-green-700 p-3 rounded mb-4">{{ session('message') }}</div>
    @endif

    <div class="mb-4 flex justify-between">
        <form method="GET" action="{{ route('admin.employees.index') }}" class="flex gap-2">
            <input type="text" name="search" placeholder="Buscar..." value="{{ $search }}" class="border p-2 rounded" />
            <select name="position" class="border p-2 rounded">
                <option value="">Todas as posições</option>
                @foreach($positions as $pos)
                    <option value="{{ $pos }}" @if($pos == $position) selected @endif>{{ $pos }}</option>
                @endforeach
            </select>
            <select name="city" class="border p-2 rounded">
                <option value="">Todas as cidades</option>
                @foreach($cities as $c)
                    <option value="{{ $c }}" @if($c == $city) selected @endif>{{ $c }}</option>
                @endforeach
            </select>
            <button type="submit" class="bg-blue-600 text-white px-4 rounded">Filtrar</button>
        </form>

        <a href="{{ route('admin.employees.create') }}" class="bg-green-600 text-white px-4 py-2 rounded">Novo Funcionário</a>
    </div>

    <table class="w-full border-collapse border border-gray-300">
        <thead>
            <tr class="bg-gray-100">
                <th class="border border-gray-300 p-2">Documento</th>
                <th class="border border-gray-300 p-2">Email</th>
                <th class="border border-gray-300 p-2">Data de Nascimento</th>
                <th class="border border-gray-300 p-2">Posição</th>
                <th class="border border-gray-300 p-2">Cidade</th>
                <th class="border border-gray-300 p-2">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($employees as $employee)
            <tr>
                <td class="border border-gray-300 p-2">{{ formatDocument($employee->document) }}</td>
                <td class="border border-gray-300 p-2">{{ $employee->email }}</td>
                <td class="border border-gray-300 p-2">{{ $employee->birthdate->format('d/m/Y') }}</td>
                <td class="border border-gray-300 p-2">{{ $employee->position }}</td>
                <td class="border border-gray-300 p-2">{{ $employee->city }}</td>
                <td class="border border-gray-300 p-2">
                    <a href="{{ route('admin.employees.edit', $employee) }}" class="text-blue-600">Editar</a>
                    <form action="{{ route('admin.employees.destroy', $employee) }}" method="POST" class="inline" onsubmit="return confirm('Deseja realmente excluir este funcionário?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 ml-2">Excluir</button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" class="text-center p-4">Nenhum funcionário encontrado.</td>
            </tr>
            @endforelse
        </tbody>
    </table>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>
</div>
@endsection

@php
// Helper para formatar CPF
function formatDocument($document) {
    $document = preg_replace('/[^0-9]/', '', $document);
    if (strlen($document) !== 11) return $document;
    return substr($document, 0, 3) . '.' . substr($document, 3, 3) . '.' . substr($document, 6, 3) . '-' . substr($document, 9, 2);
}
@endphp
