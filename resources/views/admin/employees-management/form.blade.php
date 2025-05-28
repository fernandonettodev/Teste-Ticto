@php
    $employee = $employee ?? null;
@endphp

@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4" role="alert">
        {{ session('success') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        <strong>Ops! Algum erro aconteceu:</strong>
        <ul class="mt-2 list-disc list-inside">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<div class="mb-4">
    <label for="name" class="block mb-1 font-semibold">Nome Completo</label>
    <input type="text" id="name" name="name" maxlength="11" value="{{ old('name', $employee->name ?? '') }}" class="border p-2 w-full rounded" />
    @error('name')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="document" class="block mb-1 font-semibold">Documento (CPF)</label>
    <input type="text" id="document" name="document" maxlength="11" value="{{ old('document', $employee->document ?? '') }}" class="border p-2 w-full rounded" />
    @error('document')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="email" class="block mb-1 font-semibold">Email</label>
    <input type="email" id="email" name="email" value="{{ old('email', $employee->email ?? '') }}" class="border p-2 w-full rounded" />
    @error('email')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="birthdate" class="block mb-1 font-semibold">Data de Nascimento</label>
    <input type="date" id="birthdate" name="birthdate" value="{{ old('birthdate', isset($employee->birthdate) ? $employee->birthdate->format('Y-m-d') : '') }}" class="border p-2 w-full rounded" />
    @error('birthdate')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="position" class="block mb-1 font-semibold">Posição</label>
    <input type="text" id="position" name="position" value="{{ old('position', $employee->position ?? '') }}" class="border p-2 w-full rounded" />
    @error('position')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="zipcode" class="block mb-1 font-semibold">CEP</label>
    <input type="text" id="zipcode" name="zipcode" maxlength="8" value="{{ old('zipcode', $employee->zipcode ?? '') }}" class="border p-2 w-full rounded" />
    @error('zipcode')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="address" class="block mb-1 font-semibold">Endereço</label>
    <input type="text" id="address" name="address" value="{{ old('address', $employee->address ?? '') }}" class="border p-2 w-full rounded" />
    @error('address')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="number" class="block mb-1 font-semibold">Número</label>
    <input type="text" id="number" name="number" value="{{ old('number', $employee->number ?? '') }}" class="border p-2 w-full rounded" />
    @error('number')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="neighbourhood" class="block mb-1 font-semibold">Bairro</label>
    <input type="text" id="neighbourhood" name="neighbourhood" value="{{ old('neighbourhood', $employee->neighbourhood ?? '') }}" class="border p-2 w-full rounded" />
    @error('neighbourhood')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="city" class="block mb-1 font-semibold">Cidade</label>
    <input type="text" id="city" name="city" value="{{ old('city', $employee->city ?? '') }}" class="border p-2 w-full rounded" />
    @error('city')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="state" class="block mb-1 font-semibold">Estado (UF)</label>
    <input type="text" id="state" name="state" maxlength="2" value="{{ old('state', $employee->state ?? '') }}" class="border p-2 w-full rounded" />
    @error('state')
        <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
    @enderror
</div>

<div class="mb-4">
    <label for="password" class="block mb-1 font-semibold">Senha</label>
    <input type="password" id="password" name="password" class="border p-2 w-full rounded" />
    @error('password')
    <div class="text-red-600 text-sm mt-1">{{ $message }}</div>
@enderror
</div>

{{-- JQuery para consulta do CEP --}}
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $('#zipcode').on('blur', function() {
        let cep = $(this).val().replace(/\D/g, '');

        if (cep.length === 8) {
            $.ajax({
                url: '{{ route("admin.employees.fetch-cep") }}',
                method: 'GET',
                data: { cep: cep },
                success: function(data) {
                    if (!data.error) {
                        $('#address').val(data.logradouro);
                        $('#neighbourhood').val(data.bairro);
                        $('#city').val(data.localidade);
                        $('#state').val(data.uf);
                    } else {
                        alert('CEP não encontrado.');
                    }
                },
                error: function() {
                    alert('Erro ao buscar o CEP.');
                }
            });
        }
    });
</script>
