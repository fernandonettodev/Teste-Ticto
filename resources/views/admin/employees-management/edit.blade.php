@extends('layouts.admin')

@section('title', 'Gestão de Funcionários - Editar') 

@section('content')
<div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Editar Funcionário</h1>

    <form action="{{ route('admin.employees.update', $employee) }}" method="POST">
        @csrf
        @method('PUT')

        @include('admin.employees-management.form', ['employee' => $employee])

        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded">Atualizar</button>
        <a href="{{ route('admin.employees.index') }}" class="ml-4 text-gray-600">Cancelar</a>
    </form>
</div>
@endsection
