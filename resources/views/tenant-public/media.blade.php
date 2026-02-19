@extends('tenant-public.layout')

@section('title', 'Media & Gallery - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'Photos and files - ' . ($website->name ?? $tenant->name))

@section('content')
<main>
    <div class="container">
        <div class="page-card mb-4">
            <nav aria-label="breadcrumb" class="mb-4">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('tenant.public.home', $tenant->slug) }}">Home</a></li>
                    <li class="breadcrumb-item active">Media</li>
                </ol>
            </nav>
            <h1 class="fw-bold mb-2">Media & Gallery</h1>
            <p class="text-muted">Photos and files we've shared.</p>
        </div>

        @if($media->isNotEmpty())
        <div id="mediaGrid" class="row g-4 mb-4">
            @include('tenant-public.partials.media-items', ['media' => $media])
        </div>
        <div id="mediaLoadMoreWrap" class="d-flex justify-content-center mb-5">
            @if($media->hasMorePages())
            <button type="button" id="mediaLoadMoreBtn" class="btn btn-primary px-4 py-2" data-next-page="2" data-base-url="{{ route('tenant.public.media', $tenant->slug) }}">
                <i class="fas fa-plus me-2"></i> Load More
            </button>
            @endif
        </div>
        @else
        <div class="page-card text-center py-5">
            <i class="fas fa-images fa-3x text-muted mb-3"></i>
            <p class="text-muted mb-0">No media yet.</p>
        </div>
        @endif

        <div class="text-center">
            <a href="{{ route('tenant.public.home', $tenant->slug) }}" class="btn btn-primary"><i class="fas fa-arrow-left me-2"></i> Back to Home</a>
        </div>
    </div>
</main>
@endsection

@push('scripts')
<script>
(function() {
    var btn = document.getElementById('mediaLoadMoreBtn');
    if (!btn) return;
    var grid = document.getElementById('mediaGrid');
    var wrap = document.getElementById('mediaLoadMoreWrap');
    var baseUrl = btn.getAttribute('data-base-url');

    function setButtonLoading(loading) {
        btn.disabled = loading;
        btn.innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Loading...'
            : '<i class="fas fa-plus me-2"></i> Load More';
    }

    btn.addEventListener('click', function() {
        var page = btn.getAttribute('data-next-page');
        if (!page) return;
        setButtonLoading(true);

        fetch(baseUrl + '?page=' + page, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) {
            if (!r.ok) throw new Error('Request failed');
            return r.json();
        })
        .then(function(data) {
            if (data.html && grid) {
                var div = document.createElement('div');
                div.innerHTML = data.html.trim();
                while (div.firstChild) grid.appendChild(div.firstChild);
            }
            if (data.has_more === true && data.next_page) {
                btn.setAttribute('data-next-page', data.next_page);
            } else {
                if (wrap) wrap.style.display = 'none';
            }
        })
        .catch(function() {
            if (wrap) wrap.style.display = 'block';
        })
        .finally(function() {
            setButtonLoading(false);
        });
    });
})();
</script>
@endpush
