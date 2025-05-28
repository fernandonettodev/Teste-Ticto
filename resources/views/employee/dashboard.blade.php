@extends('layouts.employee')

@section('content')
    <div class="container mx-auto p-6">
        <h1 class="text-xl font-bold mb-4">Bem-vindo, {{ $employee->name }}</h1>
        <p class="mb-2">Data de hoje: {{ today()->format('d/m/Y') }}</p>

        <h2 class="text-lg font-semibold mt-6 mb-2">Registro de Ponto</h2>

        @if(session('success'))
            <div class="bg-green-100 text-green-700 p-3 mb-4 rounded">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="bg-red-100 text-red-700 p-3 mb-4 rounded">{{ session('error') }}</div>
        @endif

        <div class="bg-white p-4 rounded shadow mb-4">
            <ul class="grid grid-cols-2 gap-4">
                <li><strong>Entrada:</strong> {{ $todayRecord->clock_in ?? '-' }}</li>
                <li><strong>Saída para almoço:</strong> {{ $todayRecord->lunch_out ?? '-' }}</li>
                <li><strong>Volta do almoço:</strong> {{ $todayRecord->lunch_in ?? '-' }}</li>
                <li><strong>Saída:</strong> {{ $todayRecord->clock_out ?? '-' }}</li>
            </ul>
        </div>

        <form method="POST" action="{{ route('employee.clock', getNextAction($todayRecord)) }}">
            @csrf
            <button type="submit"
                class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                {{ getNextActionLabel($todayRecord) }}
            </button>
        </form>
    </div>
@endsection

@php
function getNextAction($record) {
    if (!$record || !$record->clock_in) return 'clock_in';
    if (!$record->lunch_out) return 'lunch_out';
    if (!$record->lunch_in) return 'lunch_in';
    if (!$record->clock_out) return 'clock_out';
    return 'clock_out'; 
}
function getNextActionLabel($record) {
    return match (getNextAction($record)) {
        'clock_in' => 'Registrar Entrada',
        'lunch_out' => 'Saída para Almoço',
        'lunch_in' => 'Volta do Almoço',
        'clock_out' => 'Registrar Saída',
        default => 'Registrar',
    };
}
@endphp
