<div class="min-h-screen flex items-center justify-center bg-gray-100 px-4">
    <div class="w-full max-w-md bg-white rounded-2xl shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6 text-gray-800">Login do Funcionário</h2>

        <form wire:submit.prevent="login" class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700">E-mail</label>
                <input type="email" wire:model.lazy="email" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="funcionario@exemplo.com">
                @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700">Senha</label>
                <input type="password" wire:model.lazy="password" class="mt-1 w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-blue-500" placeholder="********">
                @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
            </div>

            <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700 transition">Entrar</button>

            @if ($errorMessage)
                <div class="mt-4 text-center text-red-600 text-sm">{{ $errorMessage }}</div>
            @endif
        </form>
    </div>
</div>
