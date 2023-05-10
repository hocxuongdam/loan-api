<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Payment extends BaseModel
{
    protected $guarded = [];


    public function loan(): BelongsTo
    {
        return $this->belongsTo(Loan::class);
    }

    public function shouldPayFutureRepayments(): bool
    {
        return $this->pay_future_repayments;
    }
}
