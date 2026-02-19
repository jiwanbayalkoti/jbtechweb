@extends('layouts.admin')

@section('title', 'Tenants')
@section('page-title', 'Tenants')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Tenants</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#tenantModal" onclick="openTenantModal()">
            <i class="fas fa-plus"></i> Add Tenant
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Slug</th>
                    <th>Email</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($tenants as $tenant)
                <tr>
                    <td>{{ $tenant->name }}</td>
                    <td><code>{{ $tenant->slug }}</code></td>
                    <td>{{ $tenant->email }}</td>
                    <td>{{ $tenant->subscription?->plan?->name ?? '-' }}</td>
                    <td>
                        <span class="badge badge-{{ $tenant->is_active ? 'success' : 'danger' }}">
                            {{ $tenant->is_active ? 'Active' : 'Inactive' }}
                        </span>
                    </td>
                    <td>{{ $tenant->created_at->format('M d, Y') }}</td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#tenantModal" onclick="editTenant('{{ route('admin.tenants.show', $tenant) }}', '{{ route('admin.tenants.update', $tenant) }}')"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteTenant({{ $tenant->id }}, '{{ addslashes($tenant->name) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No tenants found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $tenants->links() }}</div>
</div>

@include('admin.tenants.modal')
@endsection

@push('scripts')
<script>
function openTenantModal() {
    $('#tenantForm')[0].reset();
    $('#tenantForm').attr('action', '{{ route("admin.tenants.store") }}');
    $('#tenantForm input[name="_method"]').remove();
    $('#tenantModal .modal-title').text('Add Tenant');
    $('#planFields').show();
    $('#tenantActiveField').addClass('d-none');
    $('input[name="admin_password"]').attr('required', true);
    $('input[name="admin_password_confirmation"]').attr('required', true);
}
function editTenant(showUrl, updateUrl) {
    $.get(showUrl, function(res) {
        $('#tenantForm').attr('action', updateUrl);
        $('#tenantForm input[name="_method"]').remove();
        $('#tenantForm').append('<input type="hidden" name="_method" value="PUT">');
        $('input[name="name"]').val(res.name);
        $('input[name="email"]').val(res.email);
        $('input[name="phone"]').val(res.phone);
        $('textarea[name="address"]').val(res.address);
        $('#planFields').hide();
        $('#tenantActiveField').removeClass('d-none');
        $('#tenantIsActive').prop('checked', res.is_active);
        $('input[name="admin_name"]').val(res.users && res.users[0] ? res.users[0].name : '');
        $('input[name="admin_email"]').val(res.users && res.users[0] ? res.users[0].email : '');
        $('input[name="admin_password"]').removeAttr('required').val('');
        $('input[name="admin_password_confirmation"]').removeAttr('required').val('');
        $('#tenantModal .modal-title').text('Edit Tenant');
        $('#tenantModal').modal('show');
    }, 'json');
}
function deleteTenant(id, name) {
    JBAdmin.confirm({
        title: 'Delete Tenant',
        message: 'Are you sure you want to delete "' + name + '"? This cannot be undone.',
        confirmText: 'Delete',
        confirmClass: 'btn-danger',
        onConfirm: function() {
            $.ajax({
                url: '/admin/tenants/' + id,
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() {
                    location.reload();
                }
            });
        }
    });
}
$('#tenantForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this);
    $.ajax({
        url: form.attr('action'),
        method: form.find('input[name="_method"]').length ? form.find('input[name="_method"]').val() : 'POST',
        data: form.serialize(),
        success: function(res) {
            $('#tenantModal').modal('hide');
            if (res.redirect) location.href = res.redirect;
            else location.reload();
        },
        error: function(xhr) {
            alert(xhr.responseJSON?.message || 'An error occurred');
        }
    });
});
</script>
@endpush
