@extends('layouts.admin')

@section('title', 'Settings')
@section('page-title', 'Settings')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Settings</li>
@endsection

@section('content')
<div class="card">
    <form id="settingsForm" enctype="multipart/form-data">
        @csrf
        <div class="card-body">
            <div class="form-group">
                <label>Logo (Login Page)</label>
                @if($settings['logo'])
                <div class="mb-2">
                    <img src="{{ asset('storage/' . $settings['logo']) }}" alt="Logo" class="img-thumbnail" style="max-height: 56px; max-width: 180px; object-fit: contain;">
                </div>
                @endif
                <input type="file" name="logo" class="form-control-file" accept="image/*">
                <small class="text-muted">PNG, JPG, max 1MB. Used on login page.</small>
            </div>
            <div class="form-group">
                <label>Site Name</label>
                <input type="text" name="site_name" class="form-control" value="{{ $settings['site_name'] }}" required>
            </div>
            <div class="form-group">
                <label>Support Email</label>
                <input type="email" name="support_email" class="form-control" value="{{ $settings['support_email'] }}">
            </div>
            <div class="form-group">
                <label>Default Currency</label>
                <select name="default_currency" class="form-control">
                    <option value="USD" {{ $settings['default_currency'] === 'USD' ? 'selected' : '' }}>USD</option>
                    <option value="NPR" {{ $settings['default_currency'] === 'NPR' ? 'selected' : '' }}>NPR</option>
                </select>
            </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-primary">Save Settings</button>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
$('#settingsForm').on('submit', function(e) {
    e.preventDefault();
    var form = this;
    var fd = new FormData(form);
    $.ajax({
        url: '{{ route("admin.settings.update") }}',
        type: 'POST',
        data: fd,
        processData: false,
        contentType: false,
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
        success: function(res) {
            JBAdmin.alert({ title: 'Success', message: res.message, type: 'success' });
            if ($('input[name="logo"]')[0].files.length) location.reload();
        }
    });
});
</script>
@endpush
