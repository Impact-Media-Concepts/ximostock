<nav role="navigation" aria-label="{{ __('Pagination Navigation') }}"
    class="w-full flex items-center justify-between z-10 pb-[0.1rem] pl-[2.5rem]">
    <div class="flex justify-start items-center w-full">
        <div class="flex">
            <p class="text-[16px] w-72 text-white leading-5 font-light">
                {!! __('Weergeeft') !!}
                @if ($paginator->firstItem())
                    <span>{{ $paginator->firstItem() }}</span>
                    {!! __('tot') !!}
                    <span>{{ $paginator->lastItem() }}</span>
                @else
                    {{ $paginator->count() }}
                @endif
                {!! __('van') !!}
                <span>{{ $paginator->total() }}</span>
                {!! __('totaal') !!}
            </p>
        </div>
        
        <div class="flex w-full justify-end mr-7">
            <span class="relative z-0 inline-flex rtl:flex-row-reverse shadow-sm rounded-md">
                {{-- Previous Page Link --}}
                @if ($paginator->onFirstPage())
                    <span aria-disabled="true" aria-label="{{ __('pagination.previous') }}">
                        <span
                            class="h-10 relative inline-flex items-center px-2 py-2 text-sm font-medium text-white bg-[#3DABD5] border border-gray-300 cursor-default rounded-l-md leading-5"
                            aria-hidden="true">
                            <svg class="w-5 h-5" fill="#EAEAEA" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev"
                        class="hover:bg-[#3999BE] duration-100 relative inline-flex items-center px-2 py-2 text-sm font-medium text-white bg-[#3DABD5] border border-gray-300 rounded-l-md leading-5 hover:text-white focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-white transition ease-in-out duration-150"
                        aria-label="{{ __('pagination.previous') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @endif
                
                {{-- Pagination Elements --}}
                @foreach ($elements as $element)
                    {{-- "Three Dots" Separator --}}
                    @if (is_string($element))
                        <span aria-disabled="true">
                            <span
                                class="relative inline-flex items-center h-10 px-4 py-2 -ml-px text-sm font-medium text-white bg-[#3DABD5] border border-gray-300 cursor-default leading-5">{{ $element }}</span>
                        </span>
                    @endif
                    
                    {{-- Array Of Links --}}
                    @if (is_array($element))
                        @foreach ($element as $page => $url)
                            @if ($page == $paginator->currentPage())
                                <span aria-current="page">
                                    <span
                                        class=" hover:bg-[#3999BE] duration-100 hover:text-white h-10 relative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-[#3999BE] border border-gray-300 cursor-default leading-5">{{ $page }}</span>
                                </span>
                            @else
                                <a href="{{ $url }}"
                                    class=" hover:bg-[#3999BE] duration-100 hover:text-whiterelative inline-flex items-center px-4 py-2 -ml-px text-sm font-medium text-white bg-[#3DABD5] border border-gray-300 leading-5 hover:text-white focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-white transition ease-in-out duration-150"
                                    aria-label="{{ __('Go to page :page', ['page' => $page]) }}">
                                    {{ $page }}
                                </a>
                            @endif
                        @endforeach
                    @endif
                @endforeach
                
                {{-- Next Page Link --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next"
                        class="hover:bg-[#3999BE] duration-100 relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium text-white bg-[#3DABD5] border border-gray-300 rounded-r-md leading-5 focus:z-10 focus:outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-white transition ease-in-out duration-150"
                        aria-label="{{ __('pagination.next') }}">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                clip-rule="evenodd" />
                        </svg>
                    </a>
                @else
                    <span aria-disabled="true" aria-label="{{ __('pagination.next') }}">
                        <span
                            class="h-10 relative inline-flex items-center px-2 py-2 -ml-px text-sm font-medium bg-[#3DABD5] border border-gray-300 cursor-default rounded-r-md leading-5"
                            aria-hidden="true">
                            <svg class="w-5 h-5" fill="#EAEAEA" viewBox="0 0 20 20">
                                <path fill-rule="evenodd"
                                    d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z"
                                    clip-rule="evenodd" />
                            </svg>
                        </span>
                    </span>
                @endif
            </span>
        </div>
    </div>
</nav>
