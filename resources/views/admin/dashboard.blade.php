@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
            <div class="inner">
                <h3>{{ $stats['tenants'] }}</h3>
                <p>Total Tenants</p>
            </div>
            <div class="icon"><i class="fas fa-building"></i></div>
            <a href="{{ route('admin.tenants.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
            <div class="inner">
                <h3>{{ $stats['active_subscriptions'] }}</h3>
                <p>Active Subscriptions</p>
            </div>
            <div class="icon"><i class="fas fa-credit-card"></i></div>
            <a href="{{ route('admin.subscriptions.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
            <div class="inner">
                <h3>{{ number_format($stats['revenue_this_month'], 2) }}</h3>
                <p>Revenue (This Month)</p>
            </div>
            <div class="icon"><i class="fas fa-dollar-sign"></i></div>
            <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
    <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
            <div class="inner">
                <h3>{{ $stats['pending_invoices'] }}</h3>
                <p>Pending Invoices</p>
            </div>
            <div class="icon"><i class="fas fa-file-invoice"></i></div>
            <a href="{{ route('admin.invoices.index') }}" class="small-box-footer">View <i class="fas fa-arrow-circle-right"></i></a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Tenants</h3>
                <a href="{{ route('admin.tenants.index') }}" class="btn btn-sm btn-primary float-right">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead><tr><th>Name</th><th>Email</th><th>Created</th></tr></thead>
                    <tbody>
                        @forelse($recentTenants as $t)
                        <tr>
                            <td>{{ $t->name }}</td>
                            <td>{{ $t->email }}</td>
                            <td>{{ $t->created_at->format('M d, Y') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No tenants yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Recent Invoices</h3>
                <a href="{{ route('admin.invoices.index') }}" class="btn btn-sm btn-primary float-right">View All</a>
            </div>
            <div class="card-body p-0">
                <table class="table table-sm">
                    <thead><tr><th>Tenant</th><th>Amount</th><th>Status</th></tr></thead>
                    <tbody>
                        @forelse($recentInvoices as $inv)
                        <tr>
                            <td>{{ $inv->tenant->name ?? '-' }}</td>
                            <td>{{ number_format($inv->total_amount, 2) }}</td>
                            <td><span class="badge badge-{{ $inv->status === 'paid' ? 'success' : ($inv->status === 'pending' ? 'warning' : 'secondary') }}">{{ $inv->status }}</span></td>
                        </tr>
                        @empty
                        <tr><td colspan="3" class="text-center">No invoices yet</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
