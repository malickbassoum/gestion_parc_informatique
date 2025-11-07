@if ($paginator->hasPages())
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
        {{-- Sélecteur d'éléments par page --}}
        <div class="d-flex align-items-center gap-2">
            <span class="text-muted">Afficher :</span>
            <select class="form-select form-select-sm per-page-selector" style="width: auto;">
                <option value="10" {{ request('per_page', 10) == 10 ? 'selected' : '' }}>10</option>
                <option value="25" {{ request('per_page', 10) == 25 ? 'selected' : '' }}>25</option>
                <option value="50" {{ request('per_page', 10) == 50 ? 'selected' : '' }}>50</option>
                <option value="100" {{ request('per_page', 10) == 100 ? 'selected' : '' }}>100</option>
            </select>
            <span class="text-muted">par page</span>
        </div>

        {{-- Informations --}}
        <div class="text-muted text-center">
            <strong>{{ $paginator->firstItem() }}-{{ $paginator->lastItem() }}</strong> sur 
            <strong>{{ $paginator->total() }}</strong>
        </div>

        {{-- Boutons de navigation --}}
        <div class="d-flex gap-1">
            {{-- Premier --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm btn-outline-secondary" disabled title="Première page">
                    <i class="fas fa-angle-double-left"></i>
                </button>
            @else
                <a href="{{ $paginator->url(1) }}" class="btn btn-sm btn-outline-primary" title="Première page">
                    <i class="fas fa-angle-double-left"></i>
                </a>
            @endif

            {{-- Précédent --}}
            @if ($paginator->onFirstPage())
                <button class="btn btn-sm btn-outline-secondary" disabled title="Page précédente">
                    <i class="fas fa-chevron-left"></i>
                </button>
            @else
                <a href="{{ $paginator->previousPageUrl() }}" class="btn btn-sm btn-outline-primary" title="Page précédente">
                    <i class="fas fa-chevron-left"></i>
                </a>
            @endif

            {{-- Indicateur de page --}}
            <span class="btn btn-sm btn-light" style="cursor: default;">
                {{ $paginator->currentPage() }} / {{ $paginator->lastPage() }}
            </span>

            {{-- Suivant --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->nextPageUrl() }}" class="btn btn-sm btn-outline-primary" title="Page suivante">
                    <i class="fas fa-chevron-right"></i>
                </a>
            @else
                <button class="btn btn-sm btn-outline-secondary" disabled title="Page suivante">
                    <i class="fas fa-chevron-right"></i>
                </button>
            @endif

            {{-- Dernier --}}
            @if ($paginator->hasMorePages())
                <a href="{{ $paginator->url($paginator->lastPage()) }}" class="btn btn-sm btn-outline-primary" title="Dernière page">
                    <i class="fas fa-angle-double-right"></i>
                </a>
            @else
                <button class="btn btn-sm btn-outline-secondary" disabled title="Dernière page">
                    <i class="fas fa-angle-double-right"></i>
                </button>
            @endif
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Gestion du changement d'éléments par page
            const perPageSelectors = document.querySelectorAll('.per-page-selector');
            perPageSelectors.forEach(selector => {
                selector.addEventListener('change', function() {
                    const url = new URL(window.location.href);
                    url.searchParams.set('per_page', this.value);
                    url.searchParams.delete('page'); // Retour à la page 1
                    window.location.href = url.toString();
                });
            });
        });
    </script>
@endif