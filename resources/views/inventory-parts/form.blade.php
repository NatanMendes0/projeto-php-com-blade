<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ $mode === 'edit' ? 'Editar peca' : 'Nova peca' }}
        </h2>
    </x-slot>

    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @if ($errors->has('api'))
                <div class="mb-5 rounded-md border border-red-200 bg-red-50 px-4 py-3 text-red-700">
                    {{ $errors->first('api') }}
                </div>
            @endif

            <div class="rounded-xl border border-slate-200 bg-white p-6 shadow-sm">
                <form method="POST"
                      action="{{ $mode === 'edit' ? route('inventory-parts.update', $part['id']) : route('inventory-parts.store') }}"
                      class="space-y-4">
                    @csrf
                    @if ($mode === 'edit')
                        @method('PUT')
                    @endif

                    <div>
                        <label for="nome_peca" class="mb-1 block text-sm font-medium text-slate-700">Nome da peca</label>
                        <input id="nome_peca" name="nome_peca" type="text" maxlength="255" required
                               value="{{ old('nome_peca', $part['nome_peca'] ?? '') }}"
                               class="w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                        @error('nome_peca')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid gap-4 sm:grid-cols-2">
                        <div>
                            <label for="preco_custo" class="mb-1 block text-sm font-medium text-slate-700">Preco de custo</label>
                            <input id="preco_custo" name="preco_custo" type="number" min="0" step="0.01" required
                                   value="{{ old('preco_custo', $part['preco_custo'] ?? '') }}"
                                   class="w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            @error('preco_custo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="preco_venda" class="mb-1 block text-sm font-medium text-slate-700">Preco de venda</label>
                            <input id="preco_venda" name="preco_venda" type="number" min="0" step="0.01" required
                                   value="{{ old('preco_venda', $part['preco_venda'] ?? '') }}"
                                   class="w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                            @error('preco_venda')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div>
                        <label for="quantidade_disponivel" class="mb-1 block text-sm font-medium text-slate-700">Quantidade disponivel</label>
                        <input id="quantidade_disponivel" name="quantidade_disponivel" type="number" min="0" step="1" required
                               value="{{ old('quantidade_disponivel', $part['quantidade_disponivel'] ?? 0) }}"
                               class="w-full rounded-md border-slate-300 focus:border-teal-600 focus:ring-teal-600">
                        @error('quantidade_disponivel')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 gap-2 pt-2 sm:grid-cols-2">
                        <button type="submit"
                                class="rounded-md bg-teal-700 px-4 py-2 text-sm font-semibold text-white hover:bg-teal-800">
                            {{ $mode === 'edit' ? 'Salvar alteracoes' : 'Cadastrar peca' }}
                        </button>
                        <a href="{{ route('inventory-parts.index') }}"
                           class="inline-flex items-center justify-center rounded-md bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-800 hover:bg-slate-300">
                            Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
