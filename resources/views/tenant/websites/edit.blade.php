@extends('layouts.tenant')

@section('title', 'Edit Website')
@section('page-title', 'Edit Website')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tenant.websites.index') }}">Website</a></li>
<li class="breadcrumb-item active">Edit</li>
@endsection

@section('content')
<div class="card">
    <form method="POST" action="{{ route('tenant.websites.update', $website) }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card-body">
            <div class="form-group">
                <label>Banner Image <small class="text-muted">(home page hero)</small></label>
                @if($website->banner_image)
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $website->banner_image) }}" alt="Banner" class="img-fluid rounded border" style="max-height: 120px;">
                    <p class="small text-muted mt-1">Current image. Upload new to replace.</p>
                </div>
                @endif
                <input type="file" name="banner_image" class="form-control-file" accept="image/*">
                <small class="text-muted">Recommended: 1920×600 or similar. Max 2MB.</small>
            </div>
            <div class="form-group">
                <label>Website Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $website->name) }}" required>
            </div>
            <div class="form-group">
                <label>Tagline</label>
                <input type="text" name="tagline" class="form-control" value="{{ old('tagline', $website->tagline) }}">
            </div>
            <div class="form-group">
                <label>Welcome / Home description (shows when no "Home" page)</label>
                <textarea name="description" class="form-control rich-editor" rows="4">{{ old('description', $website->description) }}</textarea>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Primary Color</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                @php $primary = old('primary_color', $website->primary_color ?? '#6366f1'); $primary = (strpos($primary, '#') === 0 ? $primary : '#' . ltrim($primary, '#')); if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $primary)) $primary = '#6366f1'; @endphp
                                <input type="color" id="primary_color_picker" class="border rounded-left" style="width: 48px; height: 38px; cursor: pointer; padding: 2px;" value="{{ $primary }}" title="Pick color">
                            </div>
                            <input type="text" name="primary_color" id="primary_color" class="form-control" value="{{ old('primary_color', $website->primary_color) }}" placeholder="#6366f1" maxlength="7">
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label>Secondary Color</label>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                @php $secondary = old('secondary_color', $website->secondary_color ?? '#4f46e5'); $secondary = (strpos($secondary, '#') === 0 ? $secondary : '#' . ltrim($secondary, '#')); if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $secondary)) $secondary = '#4f46e5'; @endphp
                                <input type="color" id="secondary_color_picker" class="border rounded-left" style="width: 48px; height: 38px; cursor: pointer; padding: 2px;" value="{{ $secondary }}" title="Pick color">
                            </div>
                            <input type="text" name="secondary_color" id="secondary_color" class="form-control" value="{{ old('secondary_color', $website->secondary_color) }}" placeholder="#4f46e5" maxlength="7">
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('tenant.websites.index') }}" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
(function() {
    function normHex(v) {
        if (!v) return null;
        v = v.trim();
        if (/^#[0-9A-Fa-f]{6}$/.test(v)) return v;
        if (/^[0-9A-Fa-f]{6}$/.test(v)) return '#' + v;
        return null;
    }
    function syncPickerToText(pickerId, textId) {
        var picker = document.getElementById(pickerId);
        var text = document.getElementById(textId);
        if (!picker || !text) return;
        if (!text.value.trim()) text.value = picker.value;
        picker.addEventListener('input', function() { text.value = picker.value; });
        text.addEventListener('input', function() {
            var h = normHex(text.value);
            if (h) picker.value = h;
        });
    }
    syncPickerToText('primary_color_picker', 'primary_color');
    syncPickerToText('secondary_color_picker', 'secondary_color');
})();
</script>
@endpush
