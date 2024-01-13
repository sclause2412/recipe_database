<div>
    @if ($paginator->hasPages())
        <nav aria-label="Pagination Navigation" class="flex items-center justify-between" role="navigation">
            <div class="flex flex-1 justify-between sm:hidden">
                <span>
                    @if ($paginator->onFirstPage())
                        <span
                            class="tacking-widest relative inline-flex cursor-default select-none items-center rounded-md border border-gray-300 bg-white/60 px-4 py-2 text-xs font-semibold uppercase text-gray-500/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-500/60">
                            {!! __('pagination.previous') !!}
                        </span>
                    @else
                        <button
                            class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-200 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:ring-offset-gray-800 dark:active:bg-gray-900"
                            dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            type="button" wire:click="previousPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled">
                            {!! __('pagination.previous') !!}
                        </button>
                    @endif
                </span>

                <span>
                    @if ($paginator->hasMorePages())
                        <button
                            class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-200 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:ring-offset-gray-800 dark:active:bg-gray-900"
                            dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.before"
                            type="button" wire:click="nextPage('{{ $paginator->getPageName() }}')"
                            wire:loading.attr="disabled">
                            {!! __('pagination.next') !!}
                        </button>
                    @else
                        <span
                            class="relative ml-3 inline-flex cursor-default select-none items-center rounded-md border border-gray-300 bg-white/60 px-4 py-2 text-xs font-semibold uppercase tracking-widest text-gray-500/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-500/60">
                            {!! __('pagination.next') !!}
                        </span>
                    @endif
                </span>
            </div>

            <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm leading-4 text-gray-800 dark:text-gray-200">
                        <span>{!! __('Showing') !!}</span>
                        <span class="font-medium">{{ $paginator->firstItem() }}</span>
                        <span>{!! __('to') !!}</span>
                        <span class="font-medium">{{ $paginator->lastItem() }}</span>
                        <span>{!! __('of') !!}</span>
                        <span class="font-medium">{{ $paginator->total() }}</span>
                        <span>{!! __('results') !!}</span>
                    </p>
                </div>

                <div>
                    <span class="relative z-0 inline-flex rounded-md">
                        <span>
                            {{-- Previous Page Link --}}
                            @if ($paginator->onFirstPage())
                                <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                                    <span aria-hidden="true"
                                        class="relative -ml-px inline-flex cursor-default items-center rounded-l-md border border-gray-300 bg-white/60 px-4 py-2 text-sm font-semibold uppercase leading-4 tracking-widest text-gray-500/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-500/60">
                                        <x-icon class="h-4 w-4" name="caret-left" />
                                    </span>
                                </span>
                            @else
                                <button aria-label="{{ __('pagination.previous') }}"
                                    class="relative inline-flex items-center rounded-l-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase leading-4 tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-200 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:ring-offset-gray-800 dark:active:bg-gray-900"
                                    dusk="previousPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="prev" type="button"
                                    wire:click="previousPage('{{ $paginator->getPageName() }}')">
                                    <x-icon class="h-4 w-4" name="caret-left" />
                                </button>
                            @endif
                        </span>

                        {{-- Pagination Elements --}}
                        @foreach ($elements as $element)
                            {{-- "Three Dots" Separator --}}
                            @if (is_string($element))
                                <span aria-disabled="true">
                                    <span
                                        class="relative -ml-px inline-flex cursor-default select-none items-center border border-gray-300 bg-white/60 px-2 py-2 text-xs font-semibold uppercase leading-4 tracking-widest text-gray-700/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-300/60">{{ $element }}</span>
                                </span>
                            @endif

                            {{-- Array Of Links --}}
                            @if (is_array($element))
                                @foreach ($element as $page => $url)
                                    <span
                                        wire:key="paginator-{{ $paginator->getPageName() }}-page{{ $page }}">
                                        @if ($page == $paginator->currentPage())
                                            <span aria-current="page">
                                                <span
                                                    class="relative -ml-px inline-flex cursor-default select-none items-center border border-gray-300 bg-white/60 px-4 py-2 text-xs font-semibold uppercase leading-4 tracking-widest text-gray-500/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-500/60">{{ $page }}</span>
                                            </span>
                                        @else
                                            <button aria-label="{{ __('Go to page :page', ['page' => $page]) }}"
                                                class="relative -ml-px inline-flex items-center border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase leading-4 tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-200 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:ring-offset-gray-800 dark:active:bg-gray-900"
                                                type="button"
                                                wire:click="gotoPage({{ $page }}, '{{ $paginator->getPageName() }}')">
                                                {{ $page }}
                                            </button>
                                        @endif
                                    </span>
                                @endforeach
                            @endif
                        @endforeach

                        <span>
                            {{-- Next Page Link --}}
                            @if ($paginator->hasMorePages())
                                <button aria-label="{{ __('pagination.next') }}"
                                    class="relative -ml-px inline-flex items-center rounded-r-md border border-gray-300 bg-white px-4 py-2 text-xs font-semibold uppercase leading-4 tracking-widest text-gray-700 transition duration-150 ease-in-out hover:bg-gray-50 focus:z-10 focus:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 active:bg-gray-200 dark:border-gray-500 dark:bg-gray-800 dark:text-gray-300 dark:hover:bg-gray-700 dark:focus:bg-gray-700 dark:focus:ring-offset-gray-800 dark:active:bg-gray-900"
                                    dusk="nextPage{{ $paginator->getPageName() == 'page' ? '' : '.' . $paginator->getPageName() }}.after"
                                    rel="next" type="button"
                                    wire:click="nextPage('{{ $paginator->getPageName() }}')">
                                    <x-icon class="h-4 w-4" name="caret-right" />
                                </button>
                            @else
                                <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                                    <span aria-hidden="true"
                                        class="relative -ml-px inline-flex cursor-default items-center rounded-r-md border border-gray-300 bg-white/60 px-4 py-2 text-sm font-semibold uppercase leading-4 tracking-widest text-gray-500/60 dark:border-gray-500 dark:bg-gray-800/60 dark:text-gray-500/60">
                                        <x-icon class="h-4 w-4" name="caret-right" />
                                    </span>
                                </span>
                            @endif
                        </span>
                    </span>
                </div>
            </div>
        </nav>
    @endif
</div>
