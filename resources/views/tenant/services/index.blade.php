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
                <tr><th>Title</th><th>Icon</th><th>Plans</th><th>Order</th><th>Status</th><th width="160">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($services as $s)
                <tr>
                    <td>{{ $s->title }}</td>
                    <td>{{ $s->icon ?? '-' }}</td>
                    <td><span class="badge badge-info">{{ $s->plans->count() }}</span></td>
                    <td>{{ $s->sort_order }}</td>
                    <td><span class="badge badge-{{ $s->is_active ? 'success' : 'secondary' }}">{{ $s->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-primary" data-toggle="modal" data-target="#servicePlanModal" onclick='openPlanModal(@json($s->title), @json($s->plans), "{{ route('tenant.services.plans.store', $s) }}", "{{ url('tenant/services/' . $s->id . '/plans') }}")'><i class="fas fa-tags"></i></button>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#serviceModal" onclick="editItem('{{ route('tenant.services.update', $s) }}', {{ json_encode($s) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('tenant.services.destroy', $s) }}', '{{ addslashes($s->title) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No services yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $services->links() }}</div>
</div>

@include('tenant.services.modal')
@include('tenant.services.plan-modal')
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

var servicePlanBaseUrl = '';

function openPlanModal(serviceTitle, plans, storeUrl, baseUrl) {
    servicePlanBaseUrl = baseUrl;
    $('#servicePlanTitle').text(serviceTitle);
    $('#servicePlanForm')[0].reset();
    $('#servicePlanForm').attr('action', storeUrl).find('input[name="_method"]').remove();
    $('#servicePlanModal .plan-form-title').text('Add Plan');
    renderPlans(plans || []);
}

function renderPlans(plans) {
    var tbody = $('#servicePlanTable tbody');
    tbody.empty();

    if (!plans.length) {
        tbody.append('<tr><td colspan="5" class="text-center text-muted">No plans yet</td></tr>');
        return;
    }

    plans.forEach(function(plan) {
        var features = Array.isArray(plan.features) ? plan.features.join('\n') : '';
        var status = plan.is_active ? '<span class="badge badge-success">Active</span>' : '<span class="badge badge-secondary">Inactive</span>';
        tbody.append(
            '<tr>' +
                '<td>' + escapeHtml(plan.name) + '</td>' +
                '<td>' + Number(plan.price || 0).toFixed(2) + '</td>' +
                '<td>' + escapeHtml((plan.billing_cycle || '').replace("_", " ")) + '</td>' +
                '<td>' + status + '</td>' +
                '<td>' +
                    '<button type="button" class="btn btn-xs btn-info mr-1" onclick="editServicePlan(' + JSON.stringify(encodeURIComponent(JSON.stringify(plan))) + ', ' + JSON.stringify(features) + ')"><i class="fas fa-edit"></i></button>' +
                    '<button type="button" class="btn btn-xs btn-danger" onclick="deleteServicePlan(' + plan.id + ', ' + JSON.stringify(plan.name) + ')"><i class="fas fa-trash"></i></button>' +
                '</td>' +
            '</tr>'
        );
    });
}

function editServicePlan(encodedPlan, features) {
    var plan = JSON.parse(decodeURIComponent(encodedPlan));
    $('#servicePlanForm').attr('action', servicePlanBaseUrl + '/' + plan.id);
    if (!$('#servicePlanForm input[name="_method"]').length) $('#servicePlanForm').append('<input type="hidden" name="_method" value="PUT">');
    $('#servicePlanForm input[name="name"]').val(plan.name);
    $('#servicePlanForm textarea[name="description"]').val(plan.description || '');
    $('#servicePlanForm input[name="price"]').val(plan.price);
    $('#servicePlanForm select[name="billing_cycle"]').val(plan.billing_cycle || 'one_time');
    $('#servicePlanForm textarea[name="features"]').val(features || '');
    $('#servicePlanForm input[name="sort_order"]').val(plan.sort_order || 0);
    $('#servicePlanForm input[name="is_active"]').prop('checked', !!plan.is_active);
    $('#servicePlanModal .plan-form-title').text('Edit Plan');
}

function deleteServicePlan(planId, name) {
    JBAdmin.confirm({ title: 'Delete Plan', message: 'Delete "' + name + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({
            url: servicePlanBaseUrl + '/' + planId,
            method: 'DELETE',
            headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
            success: function() { location.reload(); }
        });
    }});
}

function escapeHtml(value) {
    return $('<div>').text(value || '').html();
}

$('#serviceForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#serviceModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});

$('#servicePlanForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#servicePlanModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
