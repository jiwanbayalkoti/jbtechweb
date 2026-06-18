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

<div class="modal fade" id="mediaPreviewModal" tabindex="-1" aria-hidden="true" data-detail-base-url="{{ url('/s/' . $tenant->slug . '/media') }}">
    <div class="modal-dialog modal-dialog-centered modal-xl">
        <div class="modal-content border-0" style="border-radius: 18px;">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="mediaPreviewTitle">Media</h5>
                    <small class="text-muted" id="mediaPreviewMeta"></small>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="mediaPreviewLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-3 mb-0">Loading media...</p>
                </div>
                <div id="mediaPreviewContent" class="d-none">
                    <img id="mediaPreviewImage" src="" alt="" class="img-fluid w-100 rounded" style="max-height: 70vh; object-fit: contain; background: #0f172a;">
                    <p class="text-muted mt-3 mb-0" id="mediaPreviewDescription"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="mediaPreviewPrevious"><i class="fas fa-chevron-left me-1"></i> Previous</button>
                <a href="#" class="btn btn-primary" id="mediaPreviewDownload" target="_blank" rel="noopener">Open Image</a>
                <button type="button" class="btn btn-outline-secondary" id="mediaPreviewNext">Next <i class="fas fa-chevron-right ms-1"></i></button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
(function() {
    var btn = document.getElementById('mediaLoadMoreBtn');
    var grid = document.getElementById('mediaGrid');
    var wrap = document.getElementById('mediaLoadMoreWrap');
    var baseUrl = btn ? btn.getAttribute('data-base-url') : null;
    var modalEl = document.getElementById('mediaPreviewModal');
    var previewModal = modalEl ? new bootstrap.Modal(modalEl) : null;
    var detailBaseUrl = modalEl ? modalEl.getAttribute('data-detail-base-url') : '';
    var loading = document.getElementById('mediaPreviewLoading');
    var content = document.getElementById('mediaPreviewContent');
    var image = document.getElementById('mediaPreviewImage');
    var title = document.getElementById('mediaPreviewTitle');
    var meta = document.getElementById('mediaPreviewMeta');
    var description = document.getElementById('mediaPreviewDescription');
    var previous = document.getElementById('mediaPreviewPrevious');
    var next = document.getElementById('mediaPreviewNext');
    var download = document.getElementById('mediaPreviewDownload');

    function setButtonLoading(loading) {
        btn.disabled = loading;
        btn.innerHTML = loading
            ? '<span class="spinner-border spinner-border-sm me-2" role="status"></span> Loading...'
            : '<i class="fas fa-plus me-2"></i> Load More';
    }

    function setPreviewLoading(isLoading) {
        if (!loading || !content) return;
        loading.classList.toggle('d-none', !isLoading);
        content.classList.toggle('d-none', isLoading);
    }

    function loadPreview(mediaId) {
        if (!previewModal || !mediaId) return;
        setPreviewLoading(true);
        previewModal.show();

        fetch(detailBaseUrl + '/' + mediaId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'application/json' }
        })
        .then(function(r) {
            if (!r.ok) throw new Error('Request failed');
            return r.json();
        })
        .then(function(data) {
            title.textContent = data.title || 'Media';
            meta.textContent = data.position + ' of ' + data.total + ' • ' + data.date;
            image.src = data.url;
            image.alt = data.title || data.file_name || 'Media';
            description.textContent = data.description || '';
            description.classList.toggle('d-none', !data.description);
            download.href = data.download_url || data.url;

            previous.disabled = !data.previous_id;
            previous.setAttribute('data-media-id', data.previous_id || '');
            next.disabled = !data.next_id;
            next.setAttribute('data-media-id', data.next_id || '');
            setPreviewLoading(false);
        })
        .catch(function() {
            title.textContent = 'Unable to load media';
            meta.textContent = '';
            setPreviewLoading(false);
            if (description) {
                description.textContent = 'Please try again.';
                description.classList.remove('d-none');
            }
        });
    }

    document.addEventListener('click', function(event) {
        var trigger = event.target.closest('.js-media-popup');
        if (!trigger) return;
        event.preventDefault();
        loadPreview(trigger.getAttribute('data-media-id'));
    });

    [previous, next].forEach(function(button) {
        if (!button) return;
        button.addEventListener('click', function() {
            loadPreview(button.getAttribute('data-media-id'));
        });
    });

    if (!btn) return;

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
