<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContactSubmission extends Model
{
    protected $fillable = [
        'tenant_id', 'service_plan_id', 'invoice_id', 'name', 'email', 'phone',
        'subject', 'message', 'ip_address', 'status', 'approved_at',
        'email_sent_at', 'is_read'
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'email_sent_at' => 'datetime',
        'is_read' => 'boolean',
    ];

    public function tenant(): BelongsTo
    {
        return $this->belongsTo(Tenant::class);
    }

    public function servicePlan(): BelongsTo
    {
        return $this->belongsTo(ServicePlan::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }
}
