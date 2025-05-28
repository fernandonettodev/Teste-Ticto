@extends('layouts.admin')

@section('content')
<div class="p-6">
    <h1 class="text-3xl font-bold text-gray-800 mb-8">Dashboard</h1>

    <!-- Estatísticas -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Funcionários Ativos</p>
            <p class="text-3xl font-bold text-blue-600">{{ $totalEmployees }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Registros Hoje</p>
            <p class="text-3xl font-bold text-green-600">{{ $todayRecords }}</p>
        </div>

        <div class="bg-white rounded-2xl shadow p-6 hover:shadow-lg transition">
            <p class="text-sm text-gray-500 mb-2">Registros Incompletos</p>
            <p class="text-3xl font-bold text-red-600">{{ $incompleteRecords }}</p>
        </div>
    </div>

    <!-- Tabela de registros -->
    <div class="bg-white rounded-2xl shadow p-6">
        <h2 class="text-xl font-semibold text-gray-700 mb-4">📋 Últimos Registros</h2>

        <div class="overflow-x-auto">
            <table class="w-full text-sm text-gray-700">
                <thead>
                    <tr class="border-b text-left bg-gray-50">
                        <th class="py-3 px-4 font-semibold">Funcionário</th>
                        <th class="py-3 px-4 font-semibold">Data</th>
                        <th class="py-3 px-4 font-semibold">Status</th>
                        <th class="py-3 px-4 font-semibold">Atualizado em</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($recentRecords as $record)
                        <tr class="border-b hover:bg-gray-50">
                            <td class="py-3 px-4">{{ $record->employee->name }}</td>
                            <td class="py-3 px-4">{{ $record->date }}</td>
                            <td class="py-3 px-4">
                                <span class="inline-block px-2 py-1 text-xs rounded-full
                                    {{ $record->status === 'Completo' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                    {{ $record->status }}
                                </span>
                            </td>
                            <td class="py-3 px-4">{{ $record->updated_at->format('H:i \d\e d/m/Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center py-6 text-gray-400">Nenhum registro encontrado.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
