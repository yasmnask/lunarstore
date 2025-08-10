<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Transaction Management</h3>
                    <p class="text-subtitle text-muted">Manage and monitor all transactions</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Transactions</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Transaction List</h4>
                    <div class="card-header-action">
                        <button type="button" class="btn btn-success" wire:click="exportTransactions">
                            <i class="fas fa-download"></i> Export
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Filters -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label class="form-label">Search</label>
                            <input type="text" class="form-control" wire:model.live.debounce.300ms="search"
                                placeholder="Search transactions...">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Status</label>
                            <select class="form-select" wire:model.live="statusFilter">
                                <option value="all">All Status</option>
                                <option value="pending">Pending</option>
                                <option value="processing">Processing</option>
                                <option value="completed">Completed</option>
                                <option value="cancelled">Cancelled</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Payment</label>
                            <select class="form-select" wire:model.live="paymentStatusFilter">
                                <option value="all">All Payment</option>
                                <option value="pending">Pending</option>
                                <option value="paid">Paid</option>
                                <option value="failed">Failed</option>
                                <option value="expired">Expired</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date From</label>
                            <input type="date" class="form-control" wire:model.live="dateFrom">
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">Date To</label>
                            <input type="date" class="form-control" wire:model.live="dateTo">
                        </div>
                        <div class="col-md-1">
                            <label class="form-label">Per Page</label>
                            <select class="form-select" wire:model.live="perPage">
                                <option value="10">10</option>
                                <option value="25">25</option>
                                <option value="50">50</option>
                            </select>
                        </div>
                    </div>

                    <!-- Transactions Table -->
                    <div class="table-responsive">
                        <table class="table table-striped table-hover">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Transaction Code</th>
                                    <th>Customer</th>
                                    <th>Items</th>
                                    <th>Total Amount</th>
                                    <th>Payment Method</th>
                                    <th>Payment Status</th>
                                    <th>Order Status</th>
                                    <th>Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $loop->iteration + ($transactions->currentPage() - 1) * $transactions->perPage() }}
                                        </td>
                                        <td>
                                            <span class="font-bold">{{ $transaction->transaction_code }}</span>
                                        </td>
                                        <td>
                                            <div>
                                                <div class="font-bold">{{ $transaction->user->name }}</div>
                                                <small class="text-muted">{{ $transaction->user->email }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $transaction->transactionDetails->count() }}
                                                items</span>
                                        </td>
                                        <td>
                                            <span class="font-bold">{{ $transaction->formatted_total }}</span>
                                        </td>
                                        <td>
                                            <span
                                                class="badge bg-secondary">{{ ucfirst(str_replace('_', ' ', $transaction->payment_method)) }}</span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-{{ $this->getPaymentStatusColor($transaction->payment_status) }} dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    {{ ucfirst($transaction->payment_status) }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updatePaymentStatus({{ $transaction->id }}, 'pending')">Pending</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updatePaymentStatus({{ $transaction->id }}, 'paid')">Paid</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updatePaymentStatus({{ $transaction->id }}, 'failed')">Failed</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updatePaymentStatus({{ $transaction->id }}, 'expired')">Expired</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button
                                                    class="btn btn-sm btn-{{ $this->getStatusColor($transaction->status) }} dropdown-toggle"
                                                    type="button" data-bs-toggle="dropdown">
                                                    {{ ucfirst($transaction->status) }}
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updateTransactionStatus({{ $transaction->id }}, 'pending')">Pending</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updateTransactionStatus({{ $transaction->id }}, 'processing')">Processing</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updateTransactionStatus({{ $transaction->id }}, 'completed')">Completed</a>
                                                    </li>
                                                    <li><a class="dropdown-item" href="#"
                                                            wire:click="updateTransactionStatus({{ $transaction->id }}, 'cancelled')">Cancelled</a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                        <td>
                                            <div>
                                                <div>{{ $transaction->created_at->format('M j, Y') }}</div>
                                                <small
                                                    class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="btn-group" role="group">
                                                <button type="button" class="btn btn-sm btn-info"
                                                    wire:click="viewTransactionDetails({{ $transaction->id }})"
                                                    title="View Details">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-danger"
                                                    onclick="confirmDelete({{ $transaction->id }})" title="Delete">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="10" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="fas fa-inbox fa-3x mb-3"></i>
                                                <p>No transactions found</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            Showing {{ $transactions->firstItem() ?? 0 }} to {{ $transactions->lastItem() ?? 0 }} of
                            {{ $transactions->total() }} results
                        </div>
                        <div>
                            {{ $transactions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Transaction Details Modal -->
    @if ($showDetailModal && $selectedTransaction)
        <div class="modal fade show" style="display: block;" tabindex="-1">
            <div class="modal-dialog modal-xl">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Transaction Details - {{ $selectedTransaction->transaction_code }}
                        </h5>
                        <button type="button" class="btn-close" wire:click="closeDetailModal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Customer Information -->
                            <div class="col-md-6">
                                <h6 class="fw-bold">Customer Information</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="30%">Name:</td>
                                        <td>{{ $selectedTransaction->user->name }}</td>
                                    </tr>
                                    <tr>
                                        <td>Email:</td>
                                        <td>{{ $selectedTransaction->user->email }}</td>
                                    </tr>
                                    <tr>
                                        <td>Phone:</td>
                                        <td>{{ $selectedTransaction->user->phone ?? 'N/A' }}</td>
                                    </tr>
                                </table>
                            </div>

                            <!-- Transaction Information -->
                            <div class="col-md-6">
                                <h6 class="fw-bold">Transaction Information</h6>
                                <table class="table table-borderless table-sm">
                                    <tr>
                                        <td width="30%">Code:</td>
                                        <td>{{ $selectedTransaction->transaction_code }}</td>
                                    </tr>
                                    <tr>
                                        <td>Date:</td>
                                        <td>{{ $selectedTransaction->created_at->format('F j, Y H:i') }}</td>
                                    </tr>
                                    <tr>
                                        <td>Payment Method:</td>
                                        <td>{{ ucfirst(str_replace('_', ' ', $selectedTransaction->payment_method)) }}
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Payment Status:</td>
                                        <td><span
                                                class="badge bg-{{ $this->getPaymentStatusColor($selectedTransaction->payment_status) }}">{{ ucfirst($selectedTransaction->payment_status) }}</span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>Order Status:</td>
                                        <td><span
                                                class="badge bg-{{ $this->getStatusColor($selectedTransaction->status) }}">{{ ucfirst($selectedTransaction->status) }}</span>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>

                        <hr>

                        <!-- Transaction Items -->
                        <h6 class="fw-bold">Order Items</h6>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Type</th>
                                        <th>Duration</th>
                                        <th>Quantity</th>
                                        <th>Unit Price</th>
                                        <th>Total</th>
                                        <th>Customer Data</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($transactionDetails as $detail)
                                        <tr>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    @if ($detail->productDetail->product->cover_img)
                                                        <img src="{{ asset('uploads/products/' . $detail->productDetail->product->cover_img) }}"
                                                            alt="{{ $detail->productDetail->product->app_name }}"
                                                            class="me-2"
                                                            style="width: 40px; height: 40px; object-fit: cover;">
                                                    @endif
                                                    <div>
                                                        <div class="fw-bold">
                                                            {{ $detail->productDetail->product->app_name }}</div>
                                                        <small
                                                            class="text-muted">{{ $detail->productDetail->product->category->title }}</small>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>{{ $detail->productDetail->productType->type_name ?? 'N/A' }}</td>
                                            <td>{{ $detail->productDetail->duration ?? 'N/A' }}</td>
                                            <td>{{ $detail->quantity }}</td>
                                            <td>{{ $detail->formatted_unit_price }}</td>
                                            <td>{{ $detail->formatted_total_price }}</td>
                                            <td>
                                                @if ($detail->customer_data)
                                                    <button type="button" class="btn btn-sm btn-outline-info"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#customerDataModal{{ $detail->id }}">
                                                        View Data
                                                    </button>
                                                @else
                                                    <span class="text-muted">No data</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span
                                                    class="badge bg-{{ $this->getStatusColor($detail->status) }}">{{ ucfirst($detail->status) }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr class="table-active">
                                        <td colspan="5" class="text-end fw-bold">Total:</td>
                                        <td class="fw-bold">{{ $selectedTransaction->formatted_total }}</td>
                                        <td colspan="2"></td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" wire:click="closeDetailModal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal-backdrop fade show"></div>
    @endif
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('livewire:initialized', function() {
            // SweetAlert event listeners
            window.addEventListener('swal-success', function(e) {
                Swal.fire({
                    icon: 'success',
                    title: e.detail[0].title,
                    text: e.detail[0].text,
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: e.detail[0].timer || 3000
                });
            });

            window.addEventListener('swal-error', function(e) {
                Swal.fire({
                    icon: 'error',
                    title: e.detail[0].title,
                    text: e.detail[0].text,
                    confirmButtonColor: e.detail[0].confirmButtonColor || '#d33'
                });
            });
        });

        function confirmDelete(transactionId) {
            Swal.fire({
                title: 'Are you sure?',
                text: "You won't be able to revert this!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    @this.call('deleteTransaction', transactionId);
                }
            });
        }
    </script>
@endpush
