<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('taxes', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('type');
            $table->string('document_number')->nullable();
            $table->date('date');
            $table->decimal('amount', 20, 2);
            $table->enum('status', ['unpaid', 'paid'])->default('unpaid');
            $table->string('description')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('taxes');
    }
}; 