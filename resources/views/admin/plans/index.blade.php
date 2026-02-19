@extends('layouts.admin')

@section('title', 'Plans')
@section('page-title', 'Plans')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Plans</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#planModal">
            <i class="fas fa-plus"></i> Add Plan
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Monthly</th>
                    <th>Yearly</th>
                    <th>Trial</th>
                    <th>Limits</th>
                    <th>Status</th>
                    <th width="120">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($plans as $plan)
                <tr>
                    <td>{{ $plan->name }}</td>
                    <td>${{ number_format($plan->monthly_price, 2) }}</td>
                    <td>${{ number_format($plan->yearly_price, 2) }}</td>
                    <td>{{ $plan->trial_days }} days</td>
                    <td>{{ $plan->max_pages }} pages, {{ $plan->max_users }} users</td>
                    <td><span class="badge badge-{{ $plan->is_active ? 'success' : 'secondary' }}">{{ $plan->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#planModal" onclick="editPlan({{ $plan->id }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deletePlan({{ $plan->id }}, '{{ addslashes($plan->name) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No plans found</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $plans->links() }}</div>
</div>

@include('admin.plans.modal')
@endsection

@push('scripts')
<script>
function editPlan(id) {
    $.get('{{ url("admin/plans") }}/' + id + '/edit', function(res) {
        $('#planForm').attr('action', '{{ url("admin/plans") }}/' + id).append('<input type="hidden" name="_method" value="PUT">');
        $('input[name="name"]').val(res.name);
        $('textarea[name="description"]').val(res.description);
        $('input[name="monthly_price"]').val(res.monthly_price);
        $('input[name="yearly_price"]').val(res.yearly_price);
        $('input[name="trial_days"]').val(res.trial_days || 0);
        $('input[name="max_pages"]').val(res.max_pages);
        $('input[name="max_media"]').val(res.max_media);
        $('input[name="max_users"]').val(res.max_users);
        $('input[name="is_active"]').prop('checked', res.is_active);
        $('#planModal .modal-title').text('Edit Plan');
    }, 'json');
}
$('#planModal').on('hidden.bs.modal', function() {
    $('#planForm')[0].reset();
    $('#planForm').attr('action', '{{ route("admin.plans.store") }}');
    $('#planForm input[name="_method"]').remove();
    $('#planModal .modal-title').text('Add Plan');
});
function deletePlan(id, name) {
    JBAdmin.confirm({
        title: 'Delete Plan',
        message: 'Delete plan "' + name + '"?',
        confirmClass: 'btn-danger',
        onConfirm: function() {
            $.ajax({
                url: '{{ url("admin/plans") }}/' + id,
                method: 'DELETE',
                headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
                success: function() { location.reload(); }
            });
        }
    });
}
$('#planForm').on('submit', function(e) {
    e.preventDefault();
    var form = $(this), method = form.find('input[name="_method"]').val() || 'POST';
    $.ajax({
        url: form.attr('action'),
        method: method,
        data: form.serialize(),
        success: function(res) {
            $('#planModal').modal('hide');
            location.reload();
        },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
