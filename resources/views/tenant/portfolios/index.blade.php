@extends('layouts.tenant')

@section('title', 'Portfolio')
@section('page-title', 'Portfolio')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Portfolio</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#portfolioModal" onclick="openModal()">
            <i class="fas fa-plus"></i> Add Project
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr><th>Title</th><th>Client</th><th>Category</th><th>Status</th><th width="100">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($portfolios as $p)
                <tr>
                    <td>{{ $p->title }}</td>
                    <td>{{ $p->client ?? '-' }}</td>
                    <td>{{ $p->category ?? '-' }}</td>
                    <td><span class="badge badge-{{ $p->is_active ? 'success' : 'secondary' }}">{{ $p->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#portfolioModal" onclick="editItem('{{ route('tenant.portfolios.update', $p) }}', {{ json_encode($p) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('tenant.portfolios.destroy', $p) }}', '{{ addslashes($p->title) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No portfolio items yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $portfolios->links() }}</div>
</div>

@include('tenant.portfolios.modal')
@endsection

@push('scripts')
<script>
function openModal() {
    $('#portfolioForm')[0].reset();
    $('#portfolioForm').attr('action', '{{ route("tenant.portfolios.store") }}').find('input[name="_method"]').remove();
    $('#portfolioModal .modal-title').text('Add Project');
}
function editItem(url, d) {
    $('#portfolioForm').attr('action', url);
    if (!$('#portfolioForm input[name="_method"]').length) $('#portfolioForm').append('<input type="hidden" name="_method" value="PUT">');
    $('input[name="title"]').val(d.title);
    $('textarea[name="description"]').val(d.description);
    $('input[name="client"]').val(d.client || '');
    $('input[name="category"]').val(d.category || '');
    $('input[name="project_url"]').val(d.project_url || '');
    $('input[name="sort_order"]').val(d.sort_order);
    $('input[name="is_active"]').prop('checked', d.is_active);
    $('#portfolioModal .modal-title').text('Edit Project');
}
function deleteItem(url, t) {
    JBAdmin.confirm({ title: 'Delete', message: 'Delete "' + t + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
$('#portfolioForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#portfolioModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
