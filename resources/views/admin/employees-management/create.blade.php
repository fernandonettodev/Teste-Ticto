@extends('layouts.admin')

@section('title', 'Gestão de Funcionários - Criar') 

@section('content')
<div class="p-6 max-w-xl mx-auto">
    <h1 class="text-2xl font-bold mb-4">Novo Funcionário</h1>

    <form action="{{ route('admin.employees.store') }}" method="POST">
        @csrf

        @include('admin.employees-management.form')

        <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Salvar</button>
        <a href="{{ route('admin.employees.index') }}" class="ml-4 text-gray-600">Cancelar</a>
    </form>
</div>
@endsection
