@extends('layouts.tenant')

@section('title', 'Testimonials')
@section('page-title', 'Testimonials')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Testimonials</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#testimonialModal" onclick="openModal()">
            <i class="fas fa-plus"></i> Add Testimonial
        </button>
    </div>
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr><th>Client</th><th>Company</th><th>Rating</th><th>Status</th><th width="100">Actions</th></tr>
            </thead>
            <tbody>
                @forelse($testimonials as $t)
                <tr>
                    <td>{{ $t->client_name }}</td>
                    <td>{{ $t->client_company ?? '-' }}</td>
                    <td>{{ str_repeat('★', $t->rating) }}{{ str_repeat('☆', 5 - $t->rating) }}</td>
                    <td><span class="badge badge-{{ $t->is_active ? 'success' : 'secondary' }}">{{ $t->is_active ? 'Active' : 'Inactive' }}</span></td>
                    <td>
                        <button type="button" class="btn btn-sm btn-info" data-toggle="modal" data-target="#testimonialModal" onclick="editItem('{{ route('tenant.testimonials.update', $t) }}', {{ json_encode($t) }})"><i class="fas fa-edit"></i></button>
                        <button type="button" class="btn btn-sm btn-danger" onclick="deleteItem('{{ route('tenant.testimonials.destroy', $t) }}', '{{ addslashes($t->client_name) }}')"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                @empty
                <tr><td colspan="5" class="text-center">No testimonials yet</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $testimonials->links() }}</div>
</div>

@include('tenant.testimonials.modal')
@endsection

@push('scripts')
<script>
function openModal() {
    $('#testimonialForm')[0].reset();
    $('#testimonialForm').attr('action', '{{ route("tenant.testimonials.store") }}').find('input[name="_method"]').remove();
    $('#testimonialModal .modal-title').text('Add Testimonial');
}
function editItem(url, d) {
    $('#testimonialForm').attr('action', url);
    if (!$('#testimonialForm input[name="_method"]').length) $('#testimonialForm').append('<input type="hidden" name="_method" value="PUT">');
    $('input[name="client_name"]').val(d.client_name);
    $('input[name="client_title"]').val(d.client_title || '');
    $('input[name="client_company"]').val(d.client_company || '');
    $('textarea[name="content"]').val(d.content);
    $('input[name="rating"]').val(d.rating);
    $('input[name="sort_order"]').val(d.sort_order);
    $('input[name="is_active"]').prop('checked', d.is_active);
    $('#testimonialModal .modal-title').text('Edit Testimonial');
}
function deleteItem(url, t) {
    JBAdmin.confirm({ title: 'Delete', message: 'Delete testimonial from "' + t + '"?', confirmClass: 'btn-danger', onConfirm: function() {
        $.ajax({ url: url, method: 'DELETE', headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')}, success: function() { location.reload(); } });
    }});
}
$('#testimonialForm').on('submit', function(e) {
    e.preventDefault();
    $.ajax({ url: $(this).attr('action'), method: $(this).find('input[name="_method"]').val() || 'POST', data: $(this).serialize(),
        success: function(res) { $('#testimonialModal').modal('hide'); location.href = res.redirect || location.href; },
        error: function(xhr) { alert(xhr.responseJSON?.message || 'Error'); }
    });
});
</script>
@endpush
