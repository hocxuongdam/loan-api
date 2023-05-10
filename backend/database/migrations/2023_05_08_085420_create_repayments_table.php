<?php

use App\Models\Repayment;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('repayments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->foreignId('loan_id')->index();
            $table->foreignId('payment_id')->nullable();
            $table->unsignedDecimal('amount', total: 15);
            $table->enum('status', Repayment::STATUSES)->default(Repayment::STATUS_PENDING);
            $table->date('due_date')->index();
            $table->unsignedSmallInteger('term_index');
            $table->timestamps();
            $table->index(['user_id', 'loan_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('repayments');
    }
};
