<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Loan extends BaseModel
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'approved_at' => 'datetime:Y-m-d H:i:s',
        'created_at' => 'datetime:Y-m-d H:i:s',
        'updated_at' => 'datetime:Y-m-d H:i:s',
    ];

    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_PAID = 'paid';

    public const STATUSES = [
        self::STATUS_PENDING,
        self::STATUS_APPROVED,
        self::STATUS_REJECTED,
        self::STATUS_PAID,
    ];

    public const TERM_UNIT_WEEK = 'week';

    public const TERM_UNITS = [
        self::TERM_UNIT_WEEK,
    ];

    public function borrower(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function repayments(): HasMany
    {
        return $this->hasMany(Repayment::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    private function updateStatus(string $status): void
    {
        $this->status = $status;
        $this->save();
    }

    public function approve(): void
    {
        $this->updateStatus(self::STATUS_APPROVED);
    }

    public function reject(): void
    {
        $this->updateStatus(self::STATUS_REJECTED);
    }

    public function paid(): void
    {
        $this->updateStatus(self::STATUS_PAID);
    }

    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }

    public function unpaidRepayments(): HasMany
    {
        return $this->repayments()->where('status', '!=', Repayment::STATUS_PAID);
    }
}
