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

    // ──────────────────────────────────────────────────────────────────────────
    // Currency configuration
    // Amounts are stored in their native currency — NO conversion.
    // Reports and totals are always separated by currency.
    // ──────────────────────────────────────────────────────────────────────────

    /**
     * Supported currencies.
     * - symbol   : display symbol
     * - name_lo  : Lao name shown in UI
     * - decimals : decimal places for displaying amounts
     */
    public const CURRENCIES = [
        'LAK' => ['symbol' => '₭',  'name_lo' => 'ກີບ',   'decimals' => 0],
        'THB' => ['symbol' => '฿',  'name_lo' => 'ບາດ',   'decimals' => 2],
        'USD' => ['symbol' => '$',  'name_lo' => 'ໂດລາ', 'decimals' => 2],
        'CNY' => ['symbol' => '¥',  'name_lo' => 'ຢວນ',   'decimals' => 2],
    ];

    /** Quick-select preset amounts per currency for the transaction form. */
    public const PRESETS = [
        'LAK' => [1000000, 5000000, 10000000, 50000000, 100000000],
        'THB' => [100, 500, 1000, 5000, 10000],
        'USD' => [10, 50, 100, 500, 1000],
        'CNY' => [50, 200, 500, 1000, 5000],
    ];

    // ──────────────────────────────────────────────────────────────────────────

    protected $fillable = [
        'category_id',
        'created_by',
        'type',
        'currency',
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

    /* ───── Currency helpers ───── */

    public function getCurrencyConfigAttribute(): array
    {
        return self::CURRENCIES[$this->currency ?? 'LAK'];
    }

    public function getCurrencySymbolAttribute(): string
    {
        return self::CURRENCIES[$this->currency ?? 'LAK']['symbol'];
    }

    public function getCurrencyNameAttribute(): string
    {
        return self::CURRENCIES[$this->currency ?? 'LAK']['name_lo'];
    }

    /**
     * Format amount with its native currency name, e.g. "100.00 ໂດລາ" / "1,000,000 ກີບ"
     */
    public function getAmountFormattedAttribute(): string
    {
        $cfg = $this->currency_config;
        return number_format((float) $this->amount, $cfg['decimals'], '.', ',')
             . ' ' . $cfg['name_lo'];
    }

    /* ───── Other accessors ───── */

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
