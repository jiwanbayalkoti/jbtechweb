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
            @forelse($media as $m)
            <div class="col-md-2 col-sm-3 col-4 mb-3">
                <div class="border rounded p-2 text-center">
                    @if(in_array(strtolower($m->file_type), ['jpg','jpeg','png','gif','webp']))
                    <img src="{{ asset('storage/' . $m->file_path) }}" alt="{{ $m->title ?? $m->file_name }}" class="img-fluid" style="max-height:80px;object-fit:cover;">
                    @else
                    <i class="fas fa-file fa-3x text-muted"></i>
                    @endif
                    <small class="d-block text-truncate" title="{{ $m->title ?? $m->file_name }}">{{ $m->title ?? $m->file_name }}</small>
                    <button type="button" class="btn btn-sm btn-danger mt-1" onclick="deleteMedia({{ $m->id }}, '{{ addslashes($m->title ?? $m->file_name) }}')"><i class="fas fa-trash"></i></button>
                </div>
            </div>
            @empty
            <div class="col-12 text-center py-5 text-muted">No files yet. Upload to get started.</div>
            @endforelse
        </div>
        <div class="d-flex justify-content-center">{{ $media->links() }}</div>
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
                        <label>Title <small class="text-muted">(optional, for single file only)</small></label>
                        <input type="text" name="title" class="form-control" placeholder="e.g. Company logo" maxlength="255">
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
