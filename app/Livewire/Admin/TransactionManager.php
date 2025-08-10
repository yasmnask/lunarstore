<?php

namespace App\Livewire\Admin;

use App\Models\Transaction;
use App\Models\TransactionDetail;
use App\Models\User;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

#[Title('Transaction Management')]
class TransactionManager extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $paymentStatusFilter = 'all';
    public $dateFrom = '';
    public $dateTo = '';
    public $perPage = 10;

    // Modal properties
    public $showDetailModal = false;
    public $selectedTransaction = null;
    public $transactionDetails = [];

    // Edit properties
    public $editingField = null;
    public $editingValue = '';

    protected $queryString = [
        'search' => ['except' => ''],
        'statusFilter' => ['except' => 'all'],
        'paymentStatusFilter' => ['except' => 'all'],
        'page' => ['except' => 1]
    ];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingPaymentStatusFilter()
    {
        $this->resetPage();
    }

    public function getTransactionsProperty()
    {
        $query = Transaction::with(['user', 'transactionDetails.productDetail.product'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('transaction_code', 'like', '%' . $this->search . '%')
                        ->orWhereHas('user', function ($userQuery) {
                            $userQuery->where('name', 'like', '%' . $this->search . '%')
                                ->orWhere('email', 'like', '%' . $this->search . '%');
                        });
                });
            })
            ->when($this->statusFilter !== 'all', function ($query) {
                $query->where('status', $this->statusFilter);
            })
            ->when($this->paymentStatusFilter !== 'all', function ($query) {
                $query->where('payment_status', $this->paymentStatusFilter);
            })
            ->when($this->dateFrom, function ($query) {
                $query->whereDate('created_at', '>=', $this->dateFrom);
            })
            ->when($this->dateTo, function ($query) {
                $query->whereDate('created_at', '<=', $this->dateTo);
            })
            ->orderBy('created_at', 'desc');

        return $query->paginate($this->perPage);
    }

    public function viewTransactionDetails($transactionId)
    {
        $this->selectedTransaction = Transaction::with([
            'user',
            'transactionDetails.productDetail.product.category',
            'transactionDetails.productDetail.productType'
        ])->find($transactionId);

        if ($this->selectedTransaction) {
            $this->transactionDetails = $this->selectedTransaction->transactionDetails;
            $this->showDetailModal = true;
        }
    }

    public function closeDetailModal()
    {
        $this->showDetailModal = false;
        $this->selectedTransaction = null;
        $this->transactionDetails = [];
    }

    public function updateTransactionStatus($transactionId, $status)
    {
        try {
            $transaction = Transaction::find($transactionId);
            if ($transaction) {
                $transaction->update(['status' => $status]);

                // Update related transaction details if needed
                if ($status === 'completed') {
                    $transaction->transactionDetails()->update([
                        'status' => 'completed',
                        'processed_at' => now()
                    ]);
                } elseif ($status === 'cancelled') {
                    $transaction->transactionDetails()->update([
                        'status' => 'cancelled'
                    ]);
                }

                $this->dispatch('swal-success', [
                    'title' => 'Success!',
                    'text' => 'Transaction status updated successfully.',
                    'timer' => 3000
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Error!',
                'text' => 'Failed to update transaction status: ' . $e->getMessage(),
                'confirmButtonColor' => '#d33'
            ]);
        }
    }

    public function updatePaymentStatus($transactionId, $paymentStatus)
    {
        try {
            $transaction = Transaction::find($transactionId);
            if ($transaction) {
                $updateData = ['payment_status' => $paymentStatus];

                if ($paymentStatus === 'paid') {
                    $updateData['paid_at'] = now();
                    $updateData['status'] = 'processing';
                }

                $transaction->update($updateData);

                $this->dispatch('swal-success', [
                    'title' => 'Success!',
                    'text' => 'Payment status updated successfully.',
                    'timer' => 3000
                ]);
            }
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Error!',
                'text' => 'Failed to update payment status: ' . $e->getMessage(),
                'confirmButtonColor' => '#d33'
            ]);
        }
    }

    public function deleteTransaction($transactionId)
    {
        try {
            DB::transaction(function () use ($transactionId) {
                $transaction = Transaction::find($transactionId);
                if ($transaction) {
                    // Delete transaction details first
                    $transaction->transactionDetails()->delete();
                    // Delete transaction
                    $transaction->delete();
                }
            });

            $this->dispatch('swal-success', [
                'title' => 'Deleted!',
                'text' => 'Transaction has been deleted successfully.',
                'timer' => 3000
            ]);
        } catch (\Exception $e) {
            $this->dispatch('swal-error', [
                'title' => 'Error!',
                'text' => 'Failed to delete transaction: ' . $e->getMessage(),
                'confirmButtonColor' => '#d33'
            ]);
        }
    }

    public function exportTransactions()
    {
        // This would typically generate a CSV or Excel file
        $this->dispatch('swal-success', [
            'title' => 'Export Started!',
            'text' => 'Transaction export will be available shortly.',
            'timer' => 3000
        ]);
    }

    public function getStatusColor($status)
    {
        return match ($status) {
            'pending' => 'warning',
            'processing' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            'failed' => 'danger',
            default => 'secondary'
        };
    }

    public function getPaymentStatusColor($paymentStatus)
    {
        return match ($paymentStatus) {
            'pending' => 'warning',
            'paid' => 'success',
            'failed' => 'danger',
            'expired' => 'secondary',
            default => 'secondary'
        };
    }

    public function render()
    {
        return view('livewire.admin.transaction-manager', [
            'transactions' => $this->transactions
        ])->layout('layouts.admin');
    }
}
