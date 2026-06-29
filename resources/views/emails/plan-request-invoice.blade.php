@php
    $invoice = $planRequest->invoice;
    $servicePlan = $planRequest->servicePlan;
    $invoiceMeta = $invoice?->metadata ?? [];
    $serviceName = $servicePlan?->service?->title ?? ($invoiceMeta['service_name'] ?? 'Service');
    $planName = $servicePlan?->name ?? ($invoiceMeta['plan_name'] ?? 'Plan');
    $billingCycle = $servicePlan?->billing_cycle ?? ($invoiceMeta['billing_cycle'] ?? null);
    $amount = $invoice?->total_amount ?? $servicePlan?->price;
@endphp

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>{{ $invoice ? 'Invoice ' . $invoice->invoice_number : 'Plan Request' }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #1f2937; line-height: 1.5;">
    <div style="max-width: 640px; margin: 0 auto; padding: 24px;">
        <h2 style="margin-top: 0;">Plan Request Approved</h2>

        <p>Dear {{ $planRequest->name }},</p>

        <p>
            Thank you for your interest. Your plan request has been approved.
            @if($invoice)
                Please find your invoice details below.
            @endif
        </p>

        <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
            <tbody>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Service</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ $serviceName }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Plan</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ $planName }}</td>
                </tr>
                @if($billingCycle)
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Billing</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ str_replace('_', ' ', $billingCycle) }}</td>
                </tr>
                @endif
                @if($invoice)
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Invoice No</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ $invoice->invoice_number }}</td>
                </tr>
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Invoice Status</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ ucfirst($invoice->status) }}</td>
                </tr>
                @endif
                @if($amount !== null)
                <tr>
                    <td style="padding: 8px; border: 1px solid #e5e7eb; font-weight: bold;">Amount</td>
                    <td style="padding: 8px; border: 1px solid #e5e7eb;">{{ number_format((float) $amount, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>

        @if($planRequest->public_token)
        <p>
            <a href="{{ route('public.plan-request.invoice', $planRequest->public_token) }}" style="display: inline-block; background: #6366f1; color: #ffffff; padding: 10px 16px; border-radius: 6px; text-decoration: none;">
                View Invoice Details
            </a>
        </p>
        @endif

        @if($planRequest->message)
        <p><strong>Your message:</strong></p>
        <p style="background: #f9fafb; padding: 12px; border-left: 4px solid #6366f1;">
            {!! nl2br(e($planRequest->message)) !!}
        </p>
        @endif

        <p>We will contact you soon with the next steps.</p>

        <p>
            Regards,<br>
            {{ $planRequest->tenant->name ?? config('app.name') }}
        </p>
    </div>
</body>
</html>
