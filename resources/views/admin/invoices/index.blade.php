@extends('layouts.admin')

@section('title', 'Invoices')
@section('page-title', 'Invoices')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Invoices</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Invoice #</th>
                    <th>Tenant</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th width="100">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($invoices as $inv)
                <tr>
                    <td><a href="{{ route('admin.invoices.show', $inv) }}">{{ $inv->invoice_number }}</a></td>
                    <td>{{ $inv->tenant->name ?? '-' }}</td>
                    <td>${{ number_format($inv->total_amount, 2) }}</td>
                    <td><span class="badge badge-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'pending' ? 'warning' : 'secondary') }}">{{ $inv->status }}</span></td>
                    <td>{{ $inv->created_at->format('M d, Y') }}</td>
                    <td>
                        <a href="{{ route('admin.invoices.print', $inv) }}" target="_blank" class="btn btn-sm btn-info" title="Print"><i class="fas fa-print"></i></a>
                        <a href="{{ route('admin.invoices.pdf', $inv) }}" class="btn btn-sm btn-danger" title="Download PDF"><i class="fas fa-file-pdf"></i></a>
                        @if($inv->status === 'pending')
                        <button type="button" class="btn btn-sm btn-success" onclick="markPaid({{ $inv->id }})" title="Mark as Paid"><i class="fas fa-check"></i></button>
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No invoices</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $invoices->links() }}</div>
</div>
@endsection

@push('scripts')
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
@endpush
