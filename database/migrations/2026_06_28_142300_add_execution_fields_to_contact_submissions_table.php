<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->foreignId('service_plan_id')->nullable()->after('tenant_id')->constrained('service_plans')->nullOnDelete();
            $table->foreignId('invoice_id')->nullable()->after('service_plan_id')->constrained('invoices')->nullOnDelete();
            $table->string('status')->default('pending')->after('ip_address');
            $table->timestamp('approved_at')->nullable()->after('status');
        });
    }

    public function down(): void
    {
        Schema::table('contact_submissions', function (Blueprint $table) {
            $table->dropConstrainedForeignId('service_plan_id');
            $table->dropConstrainedForeignId('invoice_id');
            $table->dropColumn(['status', 'approved_at']);
        });
    }
};
