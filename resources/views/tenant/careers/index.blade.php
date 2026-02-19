@extends('layouts.tenant')

@section('title', 'Careers')
@section('page-title', 'Careers')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Careers</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#careerModal" onclick="openModal()">
            <i class="fas fa-plus"></i> Add Job
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr><th>Title</th><th>Department</th><th>Location</th><th>Type</th><th>Status</th><th width="100">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($careers as $c)
                <tr>
                    <td>{{ $c->title }}</td>
                    <td>{{ $c->department ?? '-' }}</td>
                    <td>{{ $c->location ?? '-' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $c->type ?? '')) }}</td>
                    <td><span class="badge badge-{{ $c->is_active ? 'success' : 'secondary' }}">{{ $c->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#careerModal" onclick="editItem('{{ route('tenant.careers.update', $c) }}', {{ json_encode($c) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('tenant.careers.destroy', $c) }}', '{{ addslashes($c->title) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No job postings yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $careers->links() }}</div>
</div>

@include('tenant.careers.modal')
@endsection

@push('scripts')
<script>
function openModal() {
    $('#careerForm')[0].reset();
    $('#careerForm').attr('action', '{{ route("tenant.careers.store") }}').find('input[name="_method"]').remove();
    $('#careerModal .modal-title').text('Add Job');
}
function editItem(url, d) {
    $('#careerForm').attr('action', url);
    if (!$('#careerForm input[name="_method"]').length) $('#careerForm').append('<input type="hidden" name="_method" value="PUT">');
    $('input[name="title"]').val(d.title);
    $('input[name="department"]').val(d.department || '');
    $('input[name="location"]').val(d.location || '');
    $('select[name="type"]').val(d.type || 'full_time');
    $('textarea[name="description"]').val(d.description || '');
    $('textarea[name="requirements"]').val(d.requirements || '');
    $('input[name="application_deadline"]').val(d.application_deadline || '');
    $('input[name="is_active"]').prop('checked', d.is_active);
    $('#careerModal .modal-title').text('Edit Job');
}
function deleteItem(url, t) {
    JBAdmin.confirm({ title: 'Delete', message: 'Delete job "' + t + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
$('#careerForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#careerModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
