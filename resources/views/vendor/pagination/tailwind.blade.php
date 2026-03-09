@if ($paginator->hasPages())
    <nav role="navigation" aria-label="{{ __('Pagination Navigation') }}" class="flex items-center justify-between">

        {{-- Mobile --}}
        <div class="flex w-full gap-2 items-center justify-between sm:hidden">
            @if ($paginator->onFirstPage())
                <span class="px-4 py-2 rounded-xl text-xs font-black text-gray-300 bg-gray-100 cursor-not-allowed uppercase tracking-widest">
                    ← Précédent
                </span>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="px-4 py-2 rounded-xl text-xs font-black text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition uppercase tracking-widest">
                    ← Précédent
                </a>
            @endif

            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="px-4 py-2 rounded-xl text-xs font-black text-gray-700 bg-white border border-gray-200 hover:bg-gray-50 transition uppercase tracking-widest">
                    Suivant →
                </a>
            @else
                <span class="px-4 py-2 rounded-xl text-xs font-black text-gray-300 bg-gray-100 cursor-not-allowed uppercase tracking-widest">
                    Suivant →
                </span>
            @endif
        </div>

        {{-- Desktop --}}
        <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">

            <div>
                <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">
                    Affichage
                    @if ($paginator->firstItem())
                        <span class="font-black text-gray-700">{{ $paginator->firstItem() }}</span>
                        –
                        <span class="font-black text-gray-700">{{ $paginator->lastItem() }}</span>
                    @else
                        {{ $paginator->count() }}
                    @endif
                    sur <span class="font-black text-gray-700">{{ $paginator->total() }}</span> résultats
                </p>
            </div>

            <div>
                <div class="inline-flex items-center gap-1.5">

                    {{-- Précédent --}}
                    @if ($paginator->onFirstPage())
                        <span class="flex items-center justify-center h-9 w-9 rounded-xl bg-gray-100 text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @else
                        <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="flex items-center justify-center h-9 w-9 rounded-xl bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-ci-green transition-all" aria-label="Page précédente">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @endif

                    {{-- Pages --}}
                    @foreach ($elements as $element)
                        @if (is_string($element))
                            <span class="flex items-center justify-center h-9 w-9 text-[10px] font-black text-gray-400">···</span>
                        @endif

                        @if (is_array($element))
                            @foreach ($element as $page => $url)
                                @if ($page == $paginator->currentPage())
                                    <span aria-current="page"
                                          class="flex items-center justify-center h-9 w-9 rounded-xl text-xs font-black text-white bg-ci-orange shadow-md shadow-orange-200 cursor-default">
                                        {{ $page }}
                                    </span>
                                @else
                                    <a href="{{ $url }}"
                                       class="flex items-center justify-center h-9 w-9 rounded-xl text-xs font-black text-gray-600 bg-white border border-gray-200 hover:bg-gray-50 hover:text-ci-green transition-all"
                                       aria-label="Page {{ $page }}">
                                        {{ $page }}
                                    </a>
                                @endif
                            @endforeach
                        @endif
                    @endforeach

                    {{-- Suivant --}}
                    @if ($paginator->hasMorePages())
                        <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="flex items-center justify-center h-9 w-9 rounded-xl bg-white border border-gray-200 text-gray-500 hover:bg-gray-50 hover:text-ci-green transition-all" aria-label="Page suivante">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    @else
                        <span class="flex items-center justify-center h-9 w-9 rounded-xl bg-gray-100 text-gray-300 cursor-not-allowed">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                            </svg>
                        </span>
                    @endif
                </div>
            </div>
        </div>
    </nav>
@endif
