@extends('layouts.tenant')

@section('title', 'Plan Request Details')
@section('page-title', 'Plan Request Details')
@section('breadcrumb')
<li class="breadcrumb-item"><a href="{{ route('tenant.dashboard') }}">Dashboard</a></li>
<li class="breadcrumb-item"><a href="{{ route('tenant.plan-requests.index') }}">Plan Requests</a></li>
<li class="breadcrumb-item active">Details</li>
@endsection

@section('content')
@php
    $invoice = $planRequest->invoice;
    $servicePlan = $planRequest->servicePlan;
    $invoiceMeta = $invoice?->metadata ?? [];
    $serviceName = $servicePlan?->service?->title ?? ($invoiceMeta['service_name'] ?? '-');
    $planName = $servicePlan?->name ?? ($invoiceMeta['plan_name'] ?? '-');
    $billingCycle = $servicePlan?->billing_cycle ?? ($invoiceMeta['billing_cycle'] ?? '-');
    $price = $servicePlan?->price ?? $invoice?->total_amount;
@endphp

<div class="mb-3">
    <a href="{{ route('tenant.plan-requests.index') }}" class="btn btn-secondary btn-sm">
        <i class="fas fa-arrow-left"></i> Back
    </a>
</div>

<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Request Information</h3>
            </div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-3">Subject</dt>
                    <dd class="col-sm-9">{{ $planRequest->subject }}</dd>

                    <dt class="col-sm-3">Service</dt>
                    <dd class="col-sm-9">{{ $serviceName }}</dd>

                    <dt class="col-sm-3">Plan</dt>
                    <dd class="col-sm-9">{{ $planName }}</dd>

                    <dt class="col-sm-3">Billing</dt>
                    <dd class="col-sm-9">{{ str_replace('_', ' ', ucfirst($billingCycle)) }}</dd>

                    <dt class="col-sm-3">Price</dt>
                    <dd class="col-sm-9">{{ $price !== null ? number_format((float) $price, 2) : '-' }}</dd>

                    <dt class="col-sm-3">Message</dt>
                    <dd class="col-sm-9">{!! nl2br(e($planRequest->message ?: '-')) !!}</dd>
                </dl>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Invoice</h3>
            </div>
            <div class="card-body">
                @if($invoice)
                <dl class="row mb-0">
                    <dt class="col-sm-3">Invoice No</dt>
                    <dd class="col-sm-9">{{ $invoice->invoice_number }}</dd>

                    <dt class="col-sm-3">Status</dt>
                    <dd class="col-sm-9">
                        <span class="badge badge-{{ $invoice->status === 'paid' ? 'success' : 'warning' }}">
                            {{ ucfirst($invoice->status) }}
                        </span>
                    </dd>

                    <dt class="col-sm-3">Amount</dt>
                    <dd class="col-sm-9">{{ number_format($invoice->total_amount, 2) }}</dd>

                    <dt class="col-sm-3">Created</dt>
                    <dd class="col-sm-9">{{ $invoice->created_at->format('M d, Y h:i A') }}</dd>
                </dl>
                @else
                <p class="text-muted mb-0">Invoice has not been created yet.</p>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Customer</h3>
            </div>
            <div class="card-body">
                <p class="mb-1"><strong>{{ $planRequest->name }}</strong></p>
                <p class="mb-1"><a href="mailto:{{ $planRequest->email }}">{{ $planRequest->email }}</a></p>
                <p class="mb-0">{{ $planRequest->phone ?: '-' }}</p>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title mb-0">Status</h3>
            </div>
            <div class="card-body">
                <p>
                    <span class="badge badge-{{ $planRequest->status === 'approved' ? 'success' : ($planRequest->is_read ? 'secondary' : 'warning') }}">
                        {{ $planRequest->status === 'approved' ? 'Approved' : ($planRequest->is_read ? 'Read' : 'Pending') }}
                    </span>
                </p>
                <p class="text-muted small mb-1">Requested: {{ $planRequest->created_at->format('M d, Y h:i A') }}</p>
                @if($planRequest->approved_at)
                <p class="text-muted small mb-0">Approved: {{ $planRequest->approved_at->format('M d, Y h:i A') }}</p>
                @endif
                @if($planRequest->email_sent_at)
                <p class="text-success small mb-0">Email sent: {{ $planRequest->email_sent_at->format('M d, Y h:i A') }}</p>
                @endif
            </div>
            <div class="card-footer">
                @if($planRequest->status !== 'approved')
                <form method="POST" action="{{ route('tenant.plan-requests.approve', $planRequest) }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-primary btn-block">
                        <i class="fas fa-check-circle"></i> Approve & Create Invoice
                    </button>
                </form>
                @endif
                @if($invoice)
                <form method="POST" action="{{ route('tenant.plan-requests.send-email', $planRequest) }}" class="mb-2">
                    @csrf
                    <button type="submit" class="btn btn-warning btn-block">
                        <i class="fas fa-envelope"></i> Send Email to Customer
                    </button>
                </form>
                @endif
                @if(!$planRequest->is_read && $planRequest->status !== 'approved')
                <form method="POST" action="{{ route('tenant.plan-requests.mark-read', $planRequest) }}">
                    @csrf
                    <button type="submit" class="btn btn-success btn-block">
                        <i class="fas fa-check"></i> Mark Read
                    </button>
                </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
