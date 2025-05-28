@extends('layouts.admin')

@section('content')
    <h2 class="text-xl font-bold mb-4">Relatório de Registros de Ponto</h2>

    <table class="min-w-full border border-gray-300 text-sm">
        <thead class="bg-gray-100">
            <tr>
                <th class="border px-2 py-1">ID</th>
                <th class="border px-2 py-1">Funcionário</th>
                <th class="border px-2 py-1">Cargo</th>
                <th class="border px-2 py-1">Idade</th>
                <th class="border px-2 py-1">Gestor</th>
                <th class="border px-2 py-1">Data</th>
                <th class="border px-2 py-1">Entrada</th>
                <th class="border px-2 py-1">Saída Almoço</th>
                <th class="border px-2 py-1">Volta Almoço</th>
                <th class="border px-2 py-1">Saída</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($registros as $r)
                <tr>
                    <td class="border px-2 py-1">{{ $r->registro_id }}</td>
                    <td class="border px-2 py-1">{{ $r->funcionario }}</td>
                    <td class="border px-2 py-1">{{ $r->cargo }}</td>
                    <td class="border px-2 py-1">{{ $r->idade }}</td>
                    <td class="border px-2 py-1">{{ $r->gestor }}</td>
                    <td class="border px-2 py-1">{{ \Carbon\Carbon::parse($r->data)->format('d/m/Y') }}</td>
                    <td class="border px-2 py-1">{{ $r->entrada }}</td>
                    <td class="border px-2 py-1">{{ $r->almoco_saida }}</td>
                    <td class="border px-2 py-1">{{ $r->almoco_volta }}</td>
                    <td class="border px-2 py-1">{{ $r->saida }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="10" class="text-center border px-2 py-2">Nenhum registro encontrado.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
@endsection
