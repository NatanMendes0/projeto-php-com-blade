<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-teal-600 dark:text-teal-400">Painel de inventario</p>
                <h2 class="mt-1 font-black text-2xl text-slate-800 dark:text-slate-100 leading-tight">
                    Estoque de Pecas 3D
                </h2>
            </div>
            <a href="{{ route('inventory-parts.create') }}"
               class="inline-flex items-center justify-center rounded-xl bg-teal-700 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-teal-900/20 transition hover:-translate-y-0.5 hover:bg-teal-800">
                + Nova peca
            </a>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <section class="overflow-hidden rounded-2xl border border-teal-200/70 bg-gradient-to-br from-teal-50 via-cyan-50 to-white p-6 shadow-sm dark:border-teal-900/70 dark:from-slate-900 dark:via-slate-900 dark:to-slate-800">
                <div class="flex flex-col gap-4 md:flex-row md:items-end md:justify-between">
                    <div>
                        <h3 class="text-xl font-extrabold text-slate-800 dark:text-slate-100">Visao rapida do inventario</h3>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-300">
                            Gerencie custos, preco de venda e disponibilidade das pecas impressas em 3D.
                        </p>
                    </div>
                    <div class="inline-flex items-center gap-2 rounded-xl border border-teal-200 bg-white/70 px-3 py-2 text-xs font-semibold uppercase tracking-wider text-teal-800 dark:border-teal-700 dark:bg-slate-800 dark:text-teal-300">
                        <span class="inline-block h-2 w-2 rounded-full bg-teal-500"></span>
                        {{ $pagination['total'] ?? 0 }} {{ ($pagination['total'] ?? 0) === 1 ? 'peca cadastrada' : 'pecas cadastradas' }}
                    </div>
                </div>
            </section>

            @if (session('status'))
                <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-emerald-700 shadow-sm dark:border-emerald-700 dark:bg-emerald-950/40 dark:text-emerald-300">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->has('api'))
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm dark:border-red-700 dark:bg-red-950/40 dark:text-red-300">
                    {{ $errors->first('api') }}
                </div>
            @endif

            @if ($errorMessage)
                <div class="rounded-xl border border-red-200 bg-red-50 px-4 py-3 text-red-700 shadow-sm dark:border-red-700 dark:bg-red-950/40 dark:text-red-300">
                    {{ $errorMessage }}
                </div>
            @endif

            @if (count($parts) === 0)
                <section class="rounded-2xl border border-dashed border-slate-300 bg-white p-10 text-center shadow-sm dark:border-slate-700 dark:bg-slate-800">
                    <p class="text-base font-semibold text-slate-700 dark:text-slate-200">Nenhuma peca cadastrada.</p>
                    <p class="mt-2 text-sm text-slate-500 dark:text-slate-400">Crie a primeira peca para iniciar o controle de estoque.</p>
                    <a href="{{ route('inventory-parts.create') }}"
                       class="mt-5 inline-flex items-center rounded-xl bg-teal-700 px-4 py-2 text-sm font-semibold text-white transition hover:bg-teal-800">
                        Cadastrar primeira peca
                    </a>
                </section>
            @else
                <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-3">
                    @foreach ($parts as $part)
                        <article class="group rounded-2xl border border-slate-200 bg-white p-5 shadow-sm transition hover:-translate-y-1 hover:shadow-xl hover:shadow-slate-900/10 dark:border-slate-700 dark:bg-slate-800">
                            <div class="flex items-start justify-between gap-3">
                                <h3 class="text-lg font-black tracking-tight text-slate-800 dark:text-slate-100">{{ $part['nome_peca'] }}</h3>
                                <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-xs font-bold text-slate-600 dark:bg-slate-700 dark:text-slate-200">#{{ $part['id'] }}</span>
                            </div>

                            <dl class="mt-4 space-y-2 text-sm">
                                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2 dark:border-slate-700">
                                    <dt class="text-slate-500 dark:text-slate-400">Preco de custo</dt>
                                    <dd class="font-bold text-slate-700 dark:text-slate-200">R$ {{ number_format((float) $part['preco_custo'], 2, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between border-b border-dashed border-slate-200 pb-2 dark:border-slate-700">
                                    <dt class="text-slate-500 dark:text-slate-400">Preco de venda</dt>
                                    <dd class="font-bold text-teal-700 dark:text-teal-300">R$ {{ number_format((float) $part['preco_venda'], 2, ',', '.') }}</dd>
                                </div>
                                <div class="flex justify-between pb-1">
                                    <dt class="text-slate-500 dark:text-slate-400">Quantidade disponivel</dt>
                                    <dd class="rounded-md bg-slate-100 px-2 py-0.5 font-bold text-slate-800 dark:bg-slate-700 dark:text-slate-100">{{ $part['quantidade_disponivel'] }} un</dd>
                                </div>
                            </dl>

                            <div class="mt-5 grid grid-cols-2 gap-2">
                                <a href="{{ route('inventory-parts.edit', $part['id']) }}"
                                   class="inline-flex items-center justify-center rounded-xl bg-slate-700 px-3 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                                    Editar
                                </a>

                                <form method="POST" action="{{ route('inventory-parts.destroy', $part['id']) }}"
                                      onsubmit="return confirm('Deseja remover esta peca?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="w-full rounded-xl bg-red-700 px-3 py-2 text-sm font-semibold text-white transition hover:bg-red-800">
                                        Deletar
                                    </button>
                                </form>
                            </div>
                        </article>
                    @endforeach
                </div>
            @endif
        </div>
    </div>

    <form method="GET" action="{{ route('inventory-parts.index') }}" class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
        <div class="flex flex-col gap-3 sm:flex-row">
            <input
                type="text"
                name="q"
                value="{{ $search ?? '' }}"
                placeholder="Buscar por nome da peca"
                class="w-full rounded-xl border-slate-300 focus:border-teal-600 focus:ring-teal-600"
            >
            <button type="submit" class="rounded-xl bg-slate-800 px-4 py-2 text-sm font-semibold text-white hover:bg-slate-900">
                Buscar
            </button>
            @if (!empty($search))
                <a href="{{ route('inventory-parts.index') }}" class="rounded-xl bg-slate-200 px-4 py-2 text-sm font-semibold text-slate-700 hover:bg-slate-300 text-center">
                    Limpar
                </a>
            @endif
        </div>
    </form>

    <div class="mt-6 flex flex-col items-center justify-between gap-3 rounded-xl border border-slate-200 bg-white px-4 py-3 sm:flex-row">
        <p class="text-sm text-slate-600">
            Pagina {{ $pagination['page'] ?? 1 }} de {{ $pagination['total_pages'] ?? 1 }}
        </p>

        <div class="flex items-center gap-2">
            @if (!empty($pagination['has_prev']))
                <a href="{{ route('inventory-parts.index', ['q' => $search, 'page' => ($pagination['page'] - 1)]) }}"
                   class="rounded-lg bg-slate-700 px-3 py-1.5 text-sm font-semibold text-white hover:bg-slate-800">
                    Anterior
                </a>
            @endif

            @if (!empty($pagination['has_next']))
                <a href="{{ route('inventory-parts.index', ['q' => $search, 'page' => ($pagination['page'] + 1)]) }}"
                   class="rounded-lg bg-teal-700 px-3 py-1.5 text-sm font-semibold text-white hover:bg-teal-800">
                    Proxima
                </a>
            @endif
        </div>
    </div>
</x-app-layout>
