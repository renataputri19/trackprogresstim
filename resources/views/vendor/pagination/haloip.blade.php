@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination Navigation" class="haloip-pagination-modern">
        <div class="haloip-pagination-container">
            {{-- Previous Button --}}
            @if ($paginator->onFirstPage())
                <button class="haloip-pagination-btn haloip-pagination-btn-disabled" disabled aria-label="Sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    <span class="haloip-pagination-btn-text">Sebelumnya</span>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="haloip-pagination-btn haloip-pagination-btn-active" rel="prev" aria-label="Sebelumnya">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m15 18-6-6 6-6"/>
                    </svg>
                    <span class="haloip-pagination-btn-text">Sebelumnya</span>
                </a>
            @endif

            {{-- Page Information --}}
            <div class="haloip-pagination-info">
                <div class="haloip-pagination-page-text">
                    Halaman <span class="haloip-pagination-current">{{ $paginator->currentPage() }}</span> dari <span class="haloip-pagination-total">{{ $paginator->lastPage() }}</span>
                </div>
                <div class="haloip-pagination-items-text">
                    ({{ $paginator->firstItem() ?? 0 }}-{{ $paginator->lastItem() ?? 0 }} dari {{ $paginator->total() }} item)
                </div>
            </div>

            {{-- Next Button --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="haloip-pagination-btn haloip-pagination-btn-active" rel="next" aria-label="Selanjutnya">
                    <span class="haloip-pagination-btn-text">Selanjutnya</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </a>
            @else
                <button class="haloip-pagination-btn haloip-pagination-btn-disabled" disabled aria-label="Selanjutnya">
                    <span class="haloip-pagination-btn-text">Selanjutnya</span>
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="m9 18 6-6-6-6"/>
                    </svg>
                </button>
            @endif
        </div>
    </nav>
@endif

