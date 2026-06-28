<?php

namespace App\Mail;

use App\Models\ContactSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PlanRequestInvoiceMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public ContactSubmission $planRequest)
    {
    }

    public function build(): self
    {
        $this->planRequest->loadMissing(['tenant', 'servicePlan.service', 'invoice']);

        $invoiceNumber = $this->planRequest->invoice?->invoice_number;
        $subject = $invoiceNumber
            ? 'Your plan request invoice #' . $invoiceNumber
            : 'Your plan request details';

        return $this->subject($subject)
            ->view('emails.plan-request-invoice');
    }
}
