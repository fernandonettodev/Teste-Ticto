<div class="p-4">
    <button wire:click="$set('showModal', true)" class="bg-blue-600 text-white px-4 py-2 rounded mb-4">
        Novo Funcionário
    </button>

    <input type="text" wire:model.debounce.500ms="search" placeholder="Buscar por nome ou CPF..." class="border p-2 mb-4 w-full rounded">

    <table class="w-full border">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2 text-left">CPF</th>
                <th class="p-2 text-left">Email</th>
                <th class="p-2 text-left">Cargo</th>
                <th class="p-2 text-left">Cidade</th>
                <th class="p-2 text-left">Ações</th>
            </tr>
        </thead>
        <tbody>
            @foreach($employees as $employee)
            <tr class="border-t">
                <td class="p-2">{{ $employee->document }}</td>
                <td class="p-2">{{ $employee->email }}</td>
                <td class="p-2">{{ $employee->position }}</td>
                <td class="p-2">{{ $employee->city }}</td>
                <td class="p-2">
                    <button wire:click="edit({{ $employee->id }})" class="text-blue-600 hover:underline">Editar</button>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="mt-4">
        {{ $employees->links() }}
    </div>

    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
            <div class="bg-white p-6 rounded shadow w-full max-w-xl">
                <h2 class="text-xl font-bold mb-4">
                    {{ $editingEmployee ? 'Editar Funcionário' : 'Novo Funcionário' }}
                </h2>

                <form wire:submit.prevent="saveEmployee">

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label>CPF</label>
                            <input type="text" wire:model.defer="document" class="border p-2 w-full rounded">
                            @error('document') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Email</label>
                            <input type="email" wire:model.defer="email" class="border p-2 w-full rounded">
                            @error('email') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Data de Nascimento</label>
                            <input type="date" wire:model.defer="birthday" class="border p-2 w-full rounded">
                            @error('birthday') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>Cargo</label>
                            <input type="text" wire:model.defer="position_input" class="border p-2 w-full rounded">
                            @error('position_input') <span class="text-red-600 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label>CEP</label>
                            <input type="text" wire:model.defer="zipcode" class="border p-2 w-full rounded">
                        </div>

                        <div>
                            <label>Rua</label>
                            <input type="text" wire:model.defer="street" class="border p-2 w-full rounded">
                        </div>

                        <div>
                            <label>Número</label>
                            <input type="text" wire:model.defer="number" class="border p-2 w-full rounded">
                        </div>

                        <div>
                            <label>Bairro</label>
                            <input type="text" wire:model.defer="neighbourhood" class="border p-2 w-full rounded">
                        </div>

                        <div>
                            <label>Cidade</label>
                            <input type="text" wire:model.defer="city_input" class="border p-2 w-full rounded">
                        </div>

                        <div>
                            <label>Estado</label>
                            <input type="text" wire:model.defer="state" class="border p-2 w-full rounded">
                        </div>
                    </div>

                    <div class="mt-4 flex justify-end space-x-2">
                        <button type="button" wire:click="$set('showModal', false)" class="px-4 py-2 border rounded">
                            Cancelar
                        </button>
                        <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded">
                            Salvar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
