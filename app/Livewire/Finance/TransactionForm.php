<?php

namespace App\Livewire\Finance;

use App\Models\FinanceCategory;
use App\Models\FinanceTransaction;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class TransactionForm extends Component
{
    use WithFileUploads;

    public ?int $transactionId = null;

    public string $type              = 'income';
    public string $currency          = 'LAK';
    public string $category_id       = '';
    public string $amount            = '';
    public string $description       = '';
    public string $reference_number  = '';
    public string $transaction_date  = '';
    public string $note              = '';
    public $receipt                  = null;
    public ?string $existingReceipt  = null;

    protected function rules(): array
    {
        return [
            'type'             => ['required', 'in:income,expense'],
            'currency'         => ['required', 'in:LAK,THB,USD,CNY'],
            'category_id'      => ['required', 'exists:finance_categories,id'],
            'amount'           => ['required', 'numeric', 'min:0.01'],
            'description'      => ['required', 'string', 'min:3', 'max:1000'],
            'reference_number' => ['nullable', 'string', 'max:100'],
            'transaction_date' => ['required', 'date', 'before_or_equal:today'],
            'note'             => ['nullable', 'string', 'max:2000'],
            'receipt'          => ['nullable', 'file', 'mimes:jpg,jpeg,png,pdf', 'max:5120'],
        ];
    }

    protected function messages(): array
    {
        return [
            'type.required'                    => __('messages.validation_required'),
            'currency.required'                => __('messages.validation_required'),
            'category_id.required'             => __('messages.validation_required'),
            'category_id.exists'               => __('messages.validation_invalid'),
            'amount.required'                  => __('messages.validation_required'),
            'amount.numeric'                   => __('messages.validation_numeric'),
            'amount.min'                       => __('messages.validation_min_amount'),
            'description.required'             => __('messages.validation_required'),
            'description.min'                  => __('messages.validation_min_length'),
            'transaction_date.required'        => __('messages.validation_required'),
            'transaction_date.before_or_equal' => __('messages.validation_date_not_future'),
            'receipt.mimes'                    => __('messages.validation_file_type'),
            'receipt.max'                      => __('messages.validation_file_size'),
        ];
    }

    public function mount(int $id = null): void
    {
        abort_unless(auth()->check() && auth()->user()->canManageFinance(), 403);

        $this->transactionId    = $id;
        $this->transaction_date = now()->format('Y-m-d');

        if ($id) {
            $tx = FinanceTransaction::findOrFail($id);
            $this->type             = $tx->type;
            $this->currency         = $tx->currency ?? 'LAK';
            $this->category_id      = (string) $tx->category_id;
            $this->description      = $tx->description;
            $this->reference_number = $tx->reference_number ?? '';
            $this->transaction_date = $tx->transaction_date->format('Y-m-d');
            $this->note             = $tx->note ?? '';
            $this->existingReceipt  = $tx->receipt_path;

            // Format amount for display according to the currency's decimal convention
            $cfg          = FinanceTransaction::CURRENCIES[$this->currency];
            $this->amount = number_format((float) $tx->amount, $cfg['decimals'], '.', '');
        }
    }

    public function updatedType(): void
    {
        $this->category_id = '';
    }

    /** Reset amount when currency changes — amounts are not comparable across currencies. */
    public function updatedCurrency(): void
    {
        $this->amount = '';
    }

    public function save(): void
    {
        $this->validate();

        // Strip formatting before storing
        $rawAmount = (float) str_replace(',', '', $this->amount);

        $receiptPath = $this->existingReceipt;
        if ($this->receipt) {
            if ($receiptPath) {
                Storage::disk('public')->delete($receiptPath);
            }
            $receiptPath = $this->receipt->store('finance/receipts', 'public');
        }

        $data = [
            'type'             => $this->type,
            'currency'         => $this->currency,
            'category_id'      => (int) $this->category_id,
            'amount'           => $rawAmount,
            'description'      => trim($this->description),
            'reference_number' => trim($this->reference_number) ?: null,
            'transaction_date' => $this->transaction_date,
            'receipt_path'     => $receiptPath,
            'note'             => trim($this->note) ?: null,
        ];

        if ($this->transactionId) {
            $tx = FinanceTransaction::findOrFail($this->transactionId);
            $tx->update($data);
            session()->flash('message', __('messages.updated_successfully'));
        } else {
            FinanceTransaction::create(array_merge($data, ['created_by' => auth()->id()]));
            session()->flash('message', __('messages.created_successfully'));
        }

        $this->redirect(route('finance.transactions.index'));
    }

    public function removeReceipt(): void
    {
        $this->existingReceipt = null;
    }

    public function render()
    {
        $categories = FinanceCategory::active()
            ->when($this->type, fn($q) => $q->where('type', $this->type))
            ->ordered()
            ->get();

        $currencies = FinanceTransaction::CURRENCIES;
        $presets    = FinanceTransaction::PRESETS;
        $title      = $this->transactionId
            ? __('messages.edit_transaction')
            : __('messages.add_transaction');

        return view('livewire.finance.transaction-form', compact('categories', 'currencies', 'presets'))
            ->layout('components.layouts.app', ['title' => $title]);
    }
}
