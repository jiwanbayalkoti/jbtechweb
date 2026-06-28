<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\PlanRequestInvoiceMail;
use App\Models\ContactSubmission;
use App\Services\PlanRequestApprovalService;
use Illuminate\Support\Facades\Mail;
use RuntimeException;
use Throwable;

class PlanRequestController extends Controller
{
    public function index()
    {
        $planRequests = ContactSubmission::with(['tenant', 'servicePlan.service', 'invoice'])
            ->where('subject', 'like', 'Plan inquiry:%')
            ->latest()
            ->paginate(15);

        return view('admin.plan-requests.index', compact('planRequests'));
    }

    public function markRead(ContactSubmission $planRequest)
    {
        abort_unless(str_starts_with((string) $planRequest->subject, 'Plan inquiry:'), 404);

        $planRequest->update(['is_read' => true]);

        return back()->with('success', 'Plan request marked as read.');
    }

    public function show(ContactSubmission $planRequest)
    {
        abort_unless(str_starts_with((string) $planRequest->subject, 'Plan inquiry:'), 404);

        $planRequest->load(['tenant', 'servicePlan.service', 'invoice']);

        return view('admin.plan-requests.show', compact('planRequest'));
    }

    public function approve(ContactSubmission $planRequest, PlanRequestApprovalService $approvalService)
    {
        abort_unless(str_starts_with((string) $planRequest->subject, 'Plan inquiry:'), 404);

        try {
            $invoice = $approvalService->approve($planRequest);
        } catch (RuntimeException $exception) {
            return back()->with('error', $exception->getMessage());
        }

        return back()->with('success', 'Plan request approved and invoice ' . $invoice->invoice_number . ' created.');
    }

    public function sendEmail(ContactSubmission $planRequest)
    {
        abort_unless(str_starts_with((string) $planRequest->subject, 'Plan inquiry:'), 404);

        $planRequest->load(['tenant', 'servicePlan.service', 'invoice']);

        if (!$planRequest->invoice) {
            return back()->with('error', 'Approve this request and create an invoice before sending email.');
        }

        try {
            Mail::to($planRequest->email)->send(new PlanRequestInvoiceMail($planRequest));
        } catch (Throwable $exception) {
            return back()->with('error', 'Email could not be sent: ' . $exception->getMessage());
        }

        $planRequest->update(['email_sent_at' => now()]);

        return back()->with('success', 'Email sent to ' . $planRequest->email . '.');
    }
}
