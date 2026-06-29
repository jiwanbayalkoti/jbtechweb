@extends('tenant-public.layout')

@section('title', 'Invoice - ' . ($website->name ?? $tenant->name))
@section('meta_description', 'Review your service plan invoice details.')

@section('content')
@php
    $invoice = $planRequest->invoice;
    $servicePlan = $planRequest->servicePlan;
    $invoiceMeta = $invoice?->metadata ?? [];
    $serviceName = $servicePlan?->service?->title ?? ($invoiceMeta['service_name'] ?? '-');
    $planName = $servicePlan?->name ?? ($invoiceMeta['plan_name'] ?? '-');
    $billingCycle = $servicePlan?->billing_cycle ?? ($invoiceMeta['billing_cycle'] ?? '-');
    $amount = $invoice?->total_amount ?? $servicePlan?->price;
@endphp

<main>
    <div class="container">
        <div class="page-card">
            @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="d-flex flex-column flex-lg-row justify-content-between gap-3 mb-4">
                <div>
                    <p class="text-uppercase text-primary fw-bold small mb-2">Invoice Created</p>
                    <h1 class="fw-bold mb-2">Thank you, {{ $planRequest->name }}</h1>
                    <p class="text-muted mb-0">Your service plan request has been converted into a pending invoice.</p>
                </div>
                <div class="text-lg-end">
                    @if($invoice)
                    <div class="h5 fw-bold mb-1">{{ $invoice->invoice_number }}</div>
                    <span class="badge bg-warning text-dark">{{ ucfirst($invoice->status) }}</span>
                    @else
                    <span class="badge bg-secondary">Invoice pending</span>
                    @endif
                </div>
            </div>

            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card card-custom p-4 h-100">
                        <h4 class="fw-bold mb-3">Plan Details</h4>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <div class="text-muted small">Service</div>
                                <div class="fw-semibold">{{ $serviceName }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Plan</div>
                                <div class="fw-semibold">{{ $planName }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Billing</div>
                                <div class="fw-semibold">{{ str_replace('_', ' ', ucfirst($billingCycle)) }}</div>
                            </div>
                            <div class="col-md-6">
                                <div class="text-muted small">Amount</div>
                                <div class="fw-semibold">{{ $amount !== null ? number_format((float) $amount, 2) : '-' }}</div>
                            </div>
                        </div>

                        @if($planRequest->message)
                        <hr class="my-4">
                        <h5 class="fw-bold mb-2">Your Message</h5>
                        <div class="content text-muted">{!! nl2br(e($planRequest->message)) !!}</div>
                        @endif
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card card-custom p-4 mb-4">
                        <h4 class="fw-bold mb-3">Customer</h4>
                        <p class="mb-1"><strong>{{ $planRequest->name }}</strong></p>
                        <p class="mb-1"><a href="mailto:{{ $planRequest->email }}">{{ $planRequest->email }}</a></p>
                        <p class="mb-0">{{ $planRequest->phone ?: '-' }}</p>
                    </div>

                    <div class="card card-custom p-4">
                        <h4 class="fw-bold mb-3">Next Steps</h4>
                        <p class="text-muted mb-3">Our team will contact you with payment and service activation details.</p>
                        @if($planRequest->email_sent_at)
                        <p class="small text-success mb-0">
                            <i class="fas fa-envelope-circle-check me-1"></i>
                            Email sent on {{ $planRequest->email_sent_at->format('M d, Y h:i A') }}
                        </p>
                        @else
                        <p class="small text-muted mb-0">If email delivery is unavailable, please save this page for your records.</p>
                        @endif
                    </div>
                </div>
            </div>

            @if($planRequest->servicePlan?->service)
            <hr class="my-4">
            <a href="{{ route('public.service.show', $planRequest->servicePlan->service->slug) }}" class="btn btn-primary">
                <i class="fas fa-arrow-left me-2"></i> Back to Service
            </a>
            @endif
        </div>
    </div>
</main>
@endsection
