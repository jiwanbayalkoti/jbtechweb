@extends('layouts.tenant')

@section('title', 'Menus')
@section('page-title', 'Menus')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Menus</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#menuModal" onclick="openModal()">
            <i class="fas fa-plus"></i> Add Menu
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr><th>Name</th><th>Location</th><th>Items</th><th width="120">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($menus as $m)
                <tr>
                    <td>{{ $m->name }}</td>
                    <td><code>{{ $m->location }}</code></td>
                    <td>{{ $m->items->count() }}</td>
                    <td>
                        <a href="{{ route('tenant.menus.edit', $m) }}" class="btn btn-sm btn-info"><i class="fas fa-edit"></i></a>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteMenu('{{ route('tenant.menus.destroy', $m) }}', '{{ addslashes($m->name) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="4" class="text-center">No menus yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="menuModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="menuForm" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Add Menu</h5>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Menu Name *</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label>Location *</label>
                        <select name="location" class="form-control" required>
                            <option value="header">Header</option>
                            <option value="footer">Footer</option>
                            <option value="sidebar">Sidebar</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
function openModal() {
    $('#menuForm')[0].reset();
    $('#menuForm').attr('action', '{{ route("tenant.menus.store") }}').find('input[name="_method"]').remove();
}
function deleteMenu(url, name) {
    JBAdmin.confirm({ title: 'Delete Menu', message: 'Delete "' + name + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
$('#menuForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: 'POST', data: $(this).serialize(),
        success: function(res) { $('#menuModal').modal('hide'); location.href = res.redirect; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
