<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class FinanceTransaction extends Model
{
    use SoftDeletes;

    protected $table = 'finance_transactions';

    protected $fillable = [
        'category_id',
        'created_by',
        'type',
        'amount',
        'description',
        'reference_number',
        'transaction_date',
        'receipt_path',
        'note',
    ];

    protected $casts = [
        'amount'           => 'decimal:2',
        'transaction_date' => 'date',
    ];

    /* ───── Accessors ───── */

    public function getAmountFormattedAttribute(): string
    {
        return number_format((float) $this->amount, 0, '.', ',') . ' ກີບ';
    }

    public function getTransactionDateFormattedAttribute(): string
    {
        return $this->transaction_date
            ? $this->transaction_date->format('d/m/Y')
            : '—';
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_path ? Storage::url($this->receipt_path) : null;
    }

    public function getIsIncomeAttribute(): bool
    {
        return $this->type === 'income';
    }

    public function getIsExpenseAttribute(): bool
    {
        return $this->type === 'expense';
    }

    /* ───── Relationships ───── */

    public function category(): BelongsTo
    {
        return $this->belongsTo(FinanceCategory::class, 'category_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /* ───── Scopes ───── */

    public function scopeIncome(Builder $query): Builder
    {
        return $query->where('type', 'income');
    }

    public function scopeExpense(Builder $query): Builder
    {
        return $query->where('type', 'expense');
    }

    public function scopeForMonth(Builder $query, int $year, int $month): Builder
    {
        return $query->whereYear('transaction_date', $year)
                     ->whereMonth('transaction_date', $month);
    }

    public function scopeForYear(Builder $query, int $year): Builder
    {
        return $query->whereYear('transaction_date', $year);
    }

    public function scopeDateBetween(Builder $query, ?string $from, ?string $to): Builder
    {
        if ($from) {
            $query->where('transaction_date', '>=', $from);
        }
        if ($to) {
            $query->where('transaction_date', '<=', $to);
        }
        return $query;
    }

    public function scopeSearch(Builder $query, ?string $term): Builder
    {
        if (!$term) {
            return $query;
        }
        return $query->where(function (Builder $q) use ($term) {
            $q->where('description', 'like', "%{$term}%")
              ->orWhere('reference_number', 'like', "%{$term}%")
              ->orWhereHas('category', fn($c) => $c->where('name_lo', 'like', "%{$term}%")
                                                    ->orWhere('name_en', 'like', "%{$term}%"));
        });
    }
}
