@extends('layouts.tenant')

@section('title', 'Media Library')
@section('page-title', 'Media Library')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Media</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#uploadModal">
            <i class="fas fa-upload"></i> Upload
        </button>
    </div>
    <div class="card-body">
        <div class="row">
            @forelse($media as $item)
            @php
                $m = $item->cover;
            @endphp
            <div class="col-auto mb-3">
                <div class="border rounded p-2 text-center position-relative" style="width:85px;">
                    @if($item->is_image)
                    <button type="button" class="btn btn-link p-0 border-0 d-inline-block js-media-preview" data-media-id="{{ $m->id }}">
                        <span class="d-block position-relative mx-auto" style="width:55px;height:70px;">
                            <img src="{{ asset('storage/' . $m->file_path) }}" alt="{{ $item->title }}" class="img-fluid rounded" style="width:55px;height:70px;object-fit:cover;">
                            @if($item->type === 'album')
                            <span class="badge badge-dark position-absolute" style="top:4px;right:4px;"><i class="fas fa-images"></i> {{ $item->count }}</span>
                            @endif
                        </span>
                    </button>
                    @else
                    <div class="d-inline-flex align-items-center justify-content-center bg-light rounded" style="width:55px;height:70px;">
                        <i class="fas fa-file fa-2x text-muted"></i>
                    </div>
                    @endif
                    <small class="d-block text-truncate font-weight-bold" title="{{ $item->title }}">{{ $item->title }}</small>
                    @if($item->description)
                    <small class="d-block text-muted text-truncate" title="{{ $item->description }}">{{ $item->description }}</small>
                    @endif
                    <button type="button" class="close text-danger position-absolute" style="top:2px;right:6px;" aria-label="Delete" onclick="deleteMedia({{ $m->id }}, '{{ addslashes($item->title) }}')">&times;</button>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">No files yet. Upload to get started.</div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">{{ $media->links() }}</div>
    </div>
</div>

<div class="modal fade" id="mediaPreviewModal" tabindex="-1" data-detail-base-url="{{ url('tenant/media') }}">
    <div class="modal-dialog modal-xl modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <div>
                    <h5 class="modal-title mb-0" id="mediaPreviewTitle">Media</h5>
                    <small class="text-muted" id="mediaPreviewMeta"></small>
                </div>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body">
                <div id="mediaPreviewLoading" class="text-center py-5">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="text-muted mt-3 mb-0">Loading media...</p>
                </div>
                <div id="mediaPreviewContent" class="d-none">
                    <img id="mediaPreviewImage" src="" alt="" class="img-fluid w-100 rounded bg-dark" style="max-height:70vh;object-fit:contain;">
                    <p class="text-muted mt-3 mb-0" id="mediaPreviewDescription"></p>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-outline-secondary" id="mediaPreviewPrevious"><i class="fas fa-chevron-left mr-1"></i> Previous</button>
                <a href="#" class="btn btn-primary" id="mediaPreviewDownload" target="_blank" rel="noopener">Open Image</a>
                <button type="button" class="btn btn-outline-secondary" id="mediaPreviewNext">Next <i class="fas fa-chevron-right ml-1"></i></button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="uploadModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="{{ route('tenant.media.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Upload File(s)</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Title <small class="text-muted">(album title for multiple files)</small></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Company logo or Event album" maxlength="255">
                    </div>
                    <div class="form-group">
                        <label>Description <small class="text-muted">(optional)</small></label>
                        <textarea name="description" class="form-control" rows="3" placeholder="Short description about this file"></textarea>
                    </div>
                    <div class="form-group">
                        <label>File(s) (max 10MB each) *</label>
                        <input type="file" name="files[]" class="form-control-file" multiple required>
                    </div>
                    <small class="text-muted">Select multiple files with Ctrl/Cmd + click</small>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Upload</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    var $modal = $('#mediaPreviewModal');
    var detailBaseUrl = $modal.data('detail-base-url');
    var $loading = $('#mediaPreviewLoading');
    var $content = $('#mediaPreviewContent');
    var $image = $('#mediaPreviewImage');
    var $title = $('#mediaPreviewTitle');
    var $meta = $('#mediaPreviewMeta');
    var $description = $('#mediaPreviewDescription');
    var $previous = $('#mediaPreviewPrevious');
    var $next = $('#mediaPreviewNext');
    var $download = $('#mediaPreviewDownload');

    function setPreviewLoading(isLoading) {
        $loading.toggleClass('d-none', !isLoading);
        $content.toggleClass('d-none', isLoading);
    }

    function loadPreview(mediaId) {
        if (!mediaId) return;
        setPreviewLoading(true);
        $modal.modal('show');

        $.ajax({
            url: detailBaseUrl + '/' + mediaId + '/preview',
            method: 'GET',
            headers: { 'Accept': 'application/json' },
            success: function(data) {
                $title.text(data.title || 'Media');
                $meta.text(data.position + ' of ' + data.total + ' - ' + data.date);
                $image.attr('src', data.url).attr('alt', data.title || data.file_name || 'Media');
                $description.text(data.description || '').toggleClass('d-none', !data.description);
                $download.attr('href', data.download_url || data.url);

                $previous.prop('disabled', !data.previous_id).data('media-id', data.previous_id || '');
                $next.prop('disabled', !data.next_id).data('media-id', data.next_id || '');
                setPreviewLoading(false);
            },
            error: function() {
                $title.text('Unable to load media');
                $meta.text('');
                $description.text('Please try again.').removeClass('d-none');
                setPreviewLoading(false);
            }
        });
    }

    $(document).on('click', '.js-media-preview', function() {
        loadPreview($(this).data('media-id'));
    });

    $previous.add($next).on('click', function() {
        loadPreview($(this).data('media-id'));
    });
});

function deleteMedia(id, name) {
    JBAdmin.confirm({ title: 'Delete', message: 'Delete "' + name + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({
            url: '{{ url("tenant/media") }}/' + id,
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function() { location.reload(); }
        });
    }});
}
</script>
@endpush
