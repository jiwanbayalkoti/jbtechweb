@extends('layouts.tenant')

@section('title', 'Services')
@section('page-title', 'Services')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Services</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#serviceModal" onclick="openModal()">
            <i class="fas fa-plus"></i> Add Service
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr><th>Title</th><th>Icon</th><th>Order</th><th>Status</th><th width="100">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->icon ?? '-' }}</td>
                    <td>{{ $s->sort_order }}</td>
                    <td><span class="badge badge-{{ $s->is_active ? 'success' : 'secondary' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#serviceModal" onclick="editItem('{{ route('tenant.services.update', $s) }}', {{ json_encode($s) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('tenant.services.destroy', $s) }}', '{{ addslashes($s->title) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No services yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $services->links() }}</div>
</div>

@include('tenant.services.modal')
@endsection

@push('scripts')
<script>
function openModal() {
    $('#serviceForm')[0].reset();
    $('#serviceForm').attr('action', '{{ route("tenant.services.store") }}').find('input[name="_method"]').remove();
    $('#serviceModal .modal-title').text('Add Service');
}
function editItem(url, d) {
    $('#serviceForm').attr('action', url);
    if (!$('#serviceForm input[name="_method"]').length) $('#serviceForm').append('<input type="hidden" name="_method" value="PUT">');
    $('input[name="title"]').val(d.title);
    $('textarea[name="description"]').val(d.description);
    $('input[name="icon"]').val(d.icon || '');
    $('input[name="sort_order"]').val(d.sort_order);
    $('input[name="is_active"]').prop('checked', d.is_active);
    $('#serviceModal .modal-title').text('Edit Service');
}
function deleteItem(url, t) {
    JBAdmin.confirm({ title: 'Delete', message: 'Delete "' + t + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
$('#serviceForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#serviceModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
