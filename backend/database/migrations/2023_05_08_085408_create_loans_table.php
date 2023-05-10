<?php

use App\Models\Loan;
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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index();
            $table->unsignedDecimal('amount', total: 15);
            $table->unsignedSmallInteger('term');
            $table->enum('term_unit', Loan::TERM_UNITS)->default(Loan::TERM_UNIT_WEEK);
            $table->enum('status', Loan::STATUSES)->default(Loan::STATUS_PENDING);
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
