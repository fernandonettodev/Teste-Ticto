<div class="py-6">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header -->
        <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900">
                Registrar Ponto
            </h1>
            <p class="mt-2 text-sm text-gray-600">
                {{ $employee->name }} - {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>

        <!-- Mensagens -->
        @if (session()->has('success'))
            <div class="mb-6 bg-green-50 border border-green-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            {{ session('success') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        @if (session()->has('error'))
            <div class="mb-6 bg-red-50 border border-red-200 rounded-md p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-red-800">
                            {{ session('error') }}
                        </p>
                    </div>
                </div>
            </div>
        @endif

        <!-- Status atual -->
        @if($todayRecord)
            <div class="mb-8 bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">
                        Status de hoje
                    </h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Seus registros de ponto do dia {{ now()->format('d/m/Y') }}
                    </p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Entrada</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $todayRecord->clock_in ? $todayRecord->clock_in->format('H:i:s') : 'Não registrado' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Saída para Almoço</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $todayRecord->lunch_out ? $todayRecord->lunch_out->format('H:i:s') : 'Não registrado' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Volta do Almoço</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $todayRecord->lunch_in ? $todayRecord->lunch_in->format('H:i:s') : 'Não registrado' }}
                            </dd>
                        </div>
                        
                        <div>
                            <dt class="text-sm font-medium text-gray-500">Saída</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $todayRecord->clock_out ? $todayRecord->clock_out->format('H:i:s') : 'Não registrado' }}
                            </dd>
                        </div>

                        @if($todayRecord->total_hours)
                            <div class="sm:col-span-2">
                                <dt class="text-sm font-medium text-gray-500">Total de Horas</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ number_format($todayRecord->total_hours, 2) }} horas
                                </dd>
                            </div>
                        @endif
                    </dl>
                </div>
            </div>
        @endif

        <!-- Botões de ação -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Próximas Ações
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Clique no botão correspondente à ação que deseja realizar
                </p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                @if(count($nextActions) > 0)
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
                        @foreach($nextActions as $action => $label)
                            <button wire:click="clockAction('{{ $action }}')" 
                                    wire:loading.attr="disabled"
                                    class="relative group bg-white p-6 focus-within:ring-2 focus-within:ring-inset focus-within:ring-blue-500 border border-gray-200 rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50 disabled:cursor-not-allowed">
                                <div>
                                    <span class="rounded-lg inline-flex p-3 
                                        {{ $action === 'clock_in' ? 'bg-green-500' : '' }}
                                        {{ $action === 'lunch_out' ? 'bg-yellow-500' : '' }}
                                        {{ $action === 'lunch_in' ? 'bg-blue-500' : '' }}
                                        {{ $action === 'clock_out' ? 'bg-red-500' : '' }}
                                        group-hover:bg-opacity-80">
                                        @if($action === 'clock_in')
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1" />
                                            </svg>
                                        @elseif($action === 'lunch_out')
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        @elseif($action === 'lunch_in')
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16l-4-4m0 0l4-4m-4 4h18M13 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V4a3 3 0 013-3h4a3 3 0 013 3z" />
                                            </svg>
                                        @else
                                            <svg class="h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                        @endif
                                    </span>
                                </div>
                                <div class="mt-4">
                                    <h3 class="text-lg font-medium text-gray-900 group-hover:text-gray-700">
                                        {{ $label }}
                                    </h3>
                                    <p class="mt-1 text-sm text-gray-500">
                                        @if($action === 'clock_in')
                                            Registrar sua entrada do dia
                                        @elseif($action === 'lunch_out')
                                            Registrar saída para o almoço
                                        @elseif($action === 'lunch_in')
                                            Registrar retorno do almoço
                                        @else
                                            Registrar sua saída do dia
                                        @endif
                                    </p>
                                </div>
                                
                                <!-- Loading spinner -->
                                <div wire:loading wire:target="clockAction('{{ $action }}')" class="absolute inset-0 bg-white bg-opacity-75 flex items-center justify-center rounded-lg">
                                    <svg class="animate-spin -ml-1 mr-3 h-8 w-8 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                    </svg>
                                    <span class="text-blue-500 font-medium">Processando...</span>
                                </div>
                            </button>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-8">
                        <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <h3 class="mt-2 text-sm font-medium text-gray-900">Todos os pontos registrados!</h3>
                        <p class="mt-1 text-sm text-gray-500">
                            Você já registrou todos os pontos necessários para hoje.
                        </p>
                    </div>
                @endif
            </div>
        </div>

        <!-- Relógio em tempo real -->
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6 text-center">
                <div class="text-4xl font-bold text-gray-900" id="current-time">
                    {{ now()->format('H:i:s') }}
                </div>
                <div class="text-lg text-gray-500 mt-2">
                    {{ now()->format('l, d \d\e F \d\e Y') }}
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Atualizar relógio em tempo real
    function updateClock() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('pt-BR', {
            hour12: false,
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        
        const clockElement = document.getElementById('current-time');
        if (clockElement) {
            clockElement.textContent = timeString;
        }
    }

    // Atualizar a cada segundo
    setInterval(updateClock, 1000);
    
    // Atualizar imediatamente
    updateClock();
</script>