<?php

namespace App\Services;

use App\Models\ContactSubmission;
use App\Models\Invoice;
use App\Models\ServicePlan;
use Illuminate\Support\Facades\DB;
use RuntimeException;

class PlanRequestApprovalService
{
    public function approve(ContactSubmission $planRequest): Invoice
    {
        return DB::transaction(function () use ($planRequest) {
            $planRequest->refresh();

            if ($planRequest->invoice_id && $planRequest->invoice) {
                return $planRequest->invoice;
            }

            $servicePlan = $this->resolveServicePlan($planRequest);
            if (!$servicePlan) {
                throw new RuntimeException('Service plan could not be found for this request.');
            }

            $invoice = Invoice::create([
                'tenant_id' => $planRequest->tenant_id,
                'subscription_id' => null,
                'invoice_number' => $this->nextInvoiceNumber(),
                'amount' => (float) $servicePlan->price,
                'tax_amount' => 0,
                'total_amount' => (float) $servicePlan->price,
                'status' => 'pending',
                'payment_method' => 'manual',
                'metadata' => [
                    'source' => 'service_plan_request',
                    'plan_request_id' => $planRequest->id,
                    'service_plan_id' => $servicePlan->id,
                    'service_id' => $servicePlan->service_id,
                    'service_name' => $servicePlan->service?->title,
                    'plan_name' => $servicePlan->name,
                    'billing_cycle' => $servicePlan->billing_cycle,
                    'customer' => [
                        'name' => $planRequest->name,
                        'email' => $planRequest->email,
                        'phone' => $planRequest->phone,
                    ],
                    'message' => $planRequest->message,
                ],
            ]);

            $planRequest->update([
                'service_plan_id' => $servicePlan->id,
                'invoice_id' => $invoice->id,
                'status' => 'approved',
                'approved_at' => now(),
                'is_read' => true,
            ]);

            return $invoice;
        });
    }

    private function resolveServicePlan(ContactSubmission $planRequest): ?ServicePlan
    {
        if ($planRequest->service_plan_id) {
            return ServicePlan::with('service')->find($planRequest->service_plan_id);
        }

        $planName = $this->planNameFromSubject((string) $planRequest->subject);
        if (!$planName) {
            return null;
        }

        return ServicePlan::with('service')
            ->where('tenant_id', $planRequest->tenant_id)
            ->where('name', $planName)
            ->first();
    }

    private function planNameFromSubject(string $subject): ?string
    {
        $prefix = 'Plan inquiry:';
        if (!str_starts_with($subject, $prefix)) {
            return null;
        }

        $separatorPosition = strrpos($subject, ' - ');
        if ($separatorPosition === false) {
            return null;
        }

        return trim(substr($subject, $separatorPosition + 3)) ?: null;
    }

    private function nextInvoiceNumber(): string
    {
        $year = date('Y');
        $seq = Invoice::whereYear('created_at', $year)->count() + 1;

        do {
            $invoiceNumber = 'INV-' . $year . '-' . str_pad($seq, 5, '0', STR_PAD_LEFT);
            $seq++;
        } while (Invoice::where('invoice_number', $invoiceNumber)->exists());

        return $invoiceNumber;
    }
}
