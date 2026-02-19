@extends('layouts.admin')

@section('title', 'Invoice ' . $invoice->invoice_number)
@section('page-title', 'Invoice ' . $invoice->invoice_number)
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('admin.invoices.index') }}">Invoices</a></li>
<li class="breadcrumb-item active">{{ $invoice->invoice_number }}</li>
@endsection

@section('content')
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Invoice #{{ $invoice->invoice_number }}</span>
        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($invoice->status) }}</span>
    </div>
    <div class="card-body">
        <div class="mb-3">
            <a href="{{ route('admin.invoices.print', $invoice) }}" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-print"></i> Print</a>
            <a href="{{ route('admin.invoices.pdf', $invoice) }}" class="btn btn-danger btn-sm"><i class="fas fa-file-pdf"></i> Download PDF</a>
            @if($invoice->status === 'pending')
            <button type="button" class="btn btn-success btn-sm" onclick="markPaid({{ $invoice->id }})"><i class="fas fa-check"></i> Mark as Paid</button>
            @endif
        </div>
        <iframe src="{{ route('admin.invoices.print', $invoice) }}" width="100%" height="600" style="border: 1px solid #dee2e6; border-radius: 4px;"></iframe>
    </div>
</div>
@endsection

@push('scripts')
@if($invoice->status === 'pending')
<script>
function markPaid(id) {
    JBAdmin.confirm({
        title: 'Mark as Paid',
        message: 'Mark this invoice as paid?',
        confirmText: 'Yes',
        onConfirm: function() {
            $.post('{{ url("admin/invoices") }}/' + id + '/mark-paid', {_token: '{{ csrf_token() }}'}, function() {
                location.reload();
            });
        }
    });
}
</script>
@endif
@endpush
