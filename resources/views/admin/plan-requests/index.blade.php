@extends('layouts.admin')

@section('title', 'Plan Requests')
@section('page-title', 'Plan Requests')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item active">Plan Requests</li>
@endsection

@section('content')
<div class="card">
    <div class="card-body p-0">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Tenant</th>
                    <th>Plan Detail</th>
                    <th>Status</th>
                    <th>Invoice</th>
                    <th>Date</th>
                    <th width="160">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($planRequests as $request)
                <tr>
                    <td>
                        <strong>{{ $request->name }}</strong><br>
                        <a href="mailto:{{ $request->email }}">{{ $request->email }}</a>
                        @if($request->phone)
                        <br><a href="tel:{{ $request->phone }}">{{ $request->phone }}</a>
                        @endif
                    </td>
                    <td>{{ $request->tenant->name ?? '-' }}</td>
                    <td>
                        <strong>{{ $request->subject }}</strong>
                        <div class="text-muted small mt-1">{{ Str::limit(strip_tags($request->message), 90) }}</div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $request->status === 'approved' ? 'success' : ($request->is_read ? 'secondary' : 'warning') }}">
                            {{ $request->status === 'approved' ? 'Approved' : ($request->is_read ? 'Read' : 'Pending') }}
                        </span>
                        @if($request->approved_at)
                        <div class="text-muted small">{{ $request->approved_at->format('M d, Y h:i A') }}</div>
                        @endif
                        @if($request->email_sent_at)
                        <div class="text-success small">Email sent {{ $request->email_sent_at->format('M d, Y h:i A') }}</div>
                        @endif
                    </td>
                    <td>
                        @if($request->invoice)
                        <a href="{{ route('admin.invoices.show', $request->invoice) }}">{{ $request->invoice->invoice_number }}</a>
                        <div class="text-muted small">{{ ucfirst($request->invoice->status) }} - ${{ number_format($request->invoice->total_amount, 2) }}</div>
                        @else
                        <span class="text-muted">-</span>
                        @endif
                    </td>
                    <td>{{ $request->created_at->format('M d, Y h:i A') }}</td>
                    <td>
                        <a href="{{ route('admin.plan-requests.show', $request) }}" class="btn btn-sm btn-info mb-1">
                            <i class="fas fa-eye"></i> View
                        </a>
                        @if($request->status !== 'approved')
                        <form method="POST" action="{{ route('admin.plan-requests.approve', $request) }}" class="mb-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fas fa-check-circle"></i> Approve
                            </button>
                        </form>
                        @endif
                        @if($request->invoice)
                        <form method="POST" action="{{ route('admin.plan-requests.send-email', $request) }}" class="mb-1">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-warning">
                                <i class="fas fa-envelope"></i> Send Email
                            </button>
                        </form>
                        @endif
                        @if(!$request->is_read && $request->status !== 'approved')
                        <form method="POST" action="{{ route('admin.plan-requests.mark-read', $request) }}">
                            @csrf
                            <button type="submit" class="btn btn-sm btn-success">
                                <i class="fas fa-check"></i> Mark Read
                            </button>
                        </form>
                        @else
                        @if($request->status === 'approved')
                        <span class="text-muted">Done</span>
                        @endif
                        @endif
                    </td>
                </tr>
                @empty
                <tr><td colspan="7" class="text-center">No plan requests</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer">{{ $planRequests->links() }}</div>
</div>
@endsection
