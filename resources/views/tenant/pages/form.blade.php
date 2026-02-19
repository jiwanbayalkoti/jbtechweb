@extends('layouts.tenant')

@section('title', $page ? 'Edit Page' : 'Create Page')
@section('page-title', $page ? 'Edit Page' : 'Create Page')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tenant.pages.index') }}">Pages</a></li>
<li class="breadcrumb-item active">{{ $page ? 'Edit' : 'Create' }}</li>
@endsection

@section('content')
<div class="card">
    <form method="POST" action="{{ $page ? route('tenant.pages.update', $page) : route('tenant.pages.store') }}">
        @csrf
        @if($page) @method('PUT') @endif
        <div class="card-body">
            <div class="form-group">
                <label>Website</label>
                <select name="website_id" class="form-control" required>
                    @foreach($websites as $w)
                    <option value="{{ $w->id }}" {{ ($page && $page->website_id == $w->id) || old('website_id') == $w->id ? 'selected' : '' }}>{{ $w->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="form-group">
                <label>Title *</label>
                <input type="text" name="title" class="form-control" value="{{ old('title', $page?->title) }}" required>
            </div>
            <div class="form-group">
                <label>Slug <small class="text-muted">(optional - auto from title)</small></label>
                <input type="text" name="slug" class="form-control" value="{{ old('slug', $page?->slug) }}" placeholder="url-friendly-slug">
            </div>
            <div class="form-group">
                <label>Content</label>
                <textarea name="content" class="form-control rich-editor" rows="10">{{ old('content', $page?->content) }}</textarea>
            </div>
            <div class="form-group">
                <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="is_published" value="1" class="custom-control-input" id="isPublished" {{ ($page && $page->is_published) || old('is_published') ? 'checked' : '' }}>
                    <label class="custom-control-label" for="isPublished">Published</label>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tenant.pages.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$(function() {
    JBAdmin.autoSlugFromTitle('input[name="title"]', 'input[name="slug"]');
});
</script>
@endpush
