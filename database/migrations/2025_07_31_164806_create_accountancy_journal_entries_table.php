<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Enums\JournalEntryStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('accountancy_journal_entries', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('journal_number')->nullable();
            $table->date('date');
            $table->string('description');
            $table->string('reference')->nullable();
            $table->string('attachment')->nullable();
            $table->enum('status', JournalEntryStatus::values())->default(JournalEntryStatus::DRAFT->value);
            $table->json('history')->nullable();
            $table->uuid('created_by')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accountancy_journal_entries');
    }
};
