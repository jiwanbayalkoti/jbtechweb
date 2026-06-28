@php
    $serviceInvoiceMeta = $invoice->metadata['source'] ?? null;
    $servicePlanLabel = $serviceInvoiceMeta === 'service_plan_request'
        ? trim(($invoice->metadata['service_name'] ?? 'Service') . ' - ' . ($invoice->metadata['plan_name'] ?? 'Plan'))
        : null;
    $invoiceLineDescription = $invoice->subscription && $invoice->subscription->plan
        ? $invoice->subscription->plan->name . ' - ' . ucfirst($invoice->subscription->billing_cycle ?? 'monthly') . ' subscription'
        : ($servicePlanLabel ?: 'Subscription');
    $billTo = $serviceInvoiceMeta === 'service_plan_request'
        ? ($invoice->metadata['customer'] ?? [])
        : [];
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Invoice {{ $invoice->invoice_number }}</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #333; padding: 24px; }
        .invoice { max-width: 700px; margin: 0 auto; }
        .header { display: table; width: 100%; margin-bottom: 24px; padding-bottom: 16px; border-bottom: 2px solid #6366f1; }
        .header-left { display: table-cell; width: 50%; vertical-align: top; }
        .header-right { display: table-cell; width: 50%; vertical-align: top; text-align: right; }
        .company { font-size: 18px; font-weight: bold; color: #6366f1; }
        .invoice-title { font-size: 24px; font-weight: bold; color: #1f2937; margin-top: 8px; }
        .invoice-number { font-size: 14px; color: #6b7280; }
        .section { margin-bottom: 20px; }
        .section-title { font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; color: #9ca3af; margin-bottom: 6px; }
        .bill-to strong { display: block; margin-bottom: 4px; }
        table.items { width: 100%; border-collapse: collapse; margin-top: 24px; }
        table.items th { text-align: left; padding: 10px 12px; background: #f3f4f6; font-size: 10px; text-transform: uppercase; letter-spacing: 0.05em; }
        table.items td { padding: 12px; border-bottom: 1px solid #e5e7eb; }
        table.items tr:last-child td { border-bottom: none; }
        .text-right { text-align: right; }
        .totals { margin-top: 24px; width: 280px; margin-left: auto; }
        .totals-row { display: table; width: 100%; padding: 8px 0; }
        .totals-row .label { display: table-cell; }
        .totals-row .value { display: table-cell; text-align: right; font-weight: 600; }
        .totals-row.grand .value { font-size: 16px; color: #6366f1; }
        .status { display: inline-block; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 600; }
        .status-paid { background: #d1fae5; color: #065f46; }
        .status-pending { background: #fef3c7; color: #92400e; }
        .footer { margin-top: 40px; padding-top: 16px; border-top: 1px solid #e5e7eb; font-size: 10px; color: #9ca3af; text-align: center; }
    </style>
</head>
<body>
<div class="invoice">
    <div class="header">
        <div class="header-left">
            <div class="company">{{ config('app.name') }}</div>
            <div class="invoice-title">INVOICE</div>
            <div class="invoice-number">#{{ $invoice->invoice_number }}</div>
        </div>
        <div class="header-right">
            <span class="status status-{{ $invoice->status }}">{{ strtoupper($invoice->status) }}</span>
        </div>
    </div>

    <div style="display: table; width: 100%;">
        <div style="display: table-cell; width: 50%; vertical-align: top;">
            <div class="section">
                <div class="section-title">Bill To</div>
                <div class="bill-to">
                    @if($billTo)
                    <strong>{{ $billTo['name'] ?? 'Customer' }}</strong>
                    @if(!empty($billTo['email']))
                        <span>{{ $billTo['email'] }}</span><br>
                    @endif
                    @if(!empty($billTo['phone']))
                        <span>{{ $billTo['phone'] }}</span>
                    @endif
                    @else
                    <strong>{{ $invoice->tenant->name ?? '—' }}</strong>
                    @if($invoice->tenant->email)
                        <span>{{ $invoice->tenant->email }}</span><br>
                    @endif
                    @if($invoice->tenant->address)
                        <span>{{ $invoice->tenant->address }}</span>
                    @endif
                    @endif
                </div>
            </div>
        </div>
        <div style="display: table-cell; width: 50%; vertical-align: top; text-align: right;">
            <div class="section">
                <div class="section-title">Invoice Date</div>
                <div>{{ $invoice->created_at->format('M d, Y') }}</div>
            </div>
            @if($invoice->subscription && $invoice->subscription->plan)
            <div class="section">
                <div class="section-title">Plan</div>
                <div>{{ $invoice->subscription->plan->name }}</div>
            </div>
            @elseif($servicePlanLabel)
            <div class="section">
                <div class="section-title">Service Plan</div>
                <div>{{ $servicePlanLabel }}</div>
            </div>
            @endif
        </div>
    </div>

    <table class="items">
        <thead>
            <tr>
                <th>Description</th>
                <th class="text-right">Amount</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $invoiceLineDescription }}</td>
                <td class="text-right">${{ number_format($invoice->amount, 2) }}</td>
            </tr>
            @if((float) $invoice->tax_amount > 0)
            <tr>
                <td>Tax</td>
                <td class="text-right">${{ number_format($invoice->tax_amount, 2) }}</td>
            </tr>
            @endif
        </tbody>
    </table>

    <div class="totals">
        <div class="totals-row grand">
            <span class="label">Total</span>
            <span class="value">${{ number_format($invoice->total_amount, 2) }}</span>
        </div>
    </div>

    @if($invoice->status === 'paid' && $invoice->paid_at)
    <div class="section" style="margin-top: 24px;">
        <div class="section-title">Paid On</div>
        <div>{{ $invoice->paid_at->format('M d, Y') }}</div>
    </div>
    @endif

    <div class="footer" style="margin-top: 48px;">
        Thank you for your business.
    </div>
</div>
</body>
</html>
