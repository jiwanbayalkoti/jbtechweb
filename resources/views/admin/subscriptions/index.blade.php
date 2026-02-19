@extends('layouts.admin')

@section('title', 'Subscriptions')
@section('page-title', 'Subscriptions')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Subscriptions</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tenant</th>
                    <th>Plan</th>
                    <th>Status</th>
                    <th>Billing</th>
                    <th>Starts</th>
                    <th>Ends</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subscriptions as $sub)
                <tr>
                    <td>{{ $sub->tenant->name ?? '-' }}</td>
                    <td>{{ $sub->plan->name ?? '-' }}</td>
                    <td><span class="badge badge-{{ $sub->status === 'active' || $sub->status === 'trialing' ? 'success' : 'danger' }}">{{ $sub->status }}</span></td>
                    <td>{{ $sub->billing_cycle }}</td>
                    <td>{{ $sub->starts_at->format('M d, Y') }}</td>
                    <td>{{ $sub->ends_at?->format('M d, Y') ?? '-' }}</td>
                </tr>
                @empty
                <tr><td colspan="6" class="text-center">No subscriptions</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $subscriptions->links() }}</div>
</div>
@endsection
