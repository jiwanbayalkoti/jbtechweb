<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('websites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('tagline')->nullable();
            $table->text('description')->nullable();
            $table->string('logo')->nullable();
            $table->string('favicon')->nullable();
            $table->string('primary_color', 20)->default('#007bff');
            $table->string('secondary_color', 20)->default('#6c757d');
            $table->string('font_family')->default('Inter');
            $table->boolean('dark_mode')->default(false);
            $table->string('default_language', 5)->default('en');
            $table->json('social_links')->nullable();
            $table->json('contact_info')->nullable();
            $table->json('footer_content')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('websites');
    }
};
