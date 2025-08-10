@push('styles')
    <style>
        .editable:hover {
            background-color: #f8f9fa;
            cursor: pointer;
        }

        .edit-input {
            width: 100%;
            padding: 5px;
            border: 1px solid #ced4da;
            border-radius: 4px;
        }

        #detailTable_filter {
            margin-bottom: 15px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .detail-action-btn {
            margin: 0 3px;
        }

        .select-info {
            margin-left: 10px;
        }

        .loading-overlay {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .status-badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        .notes-cell {
            max-width: 150px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        /* Row grouping styles */
        .group {
            background-color: #f8f9fa !important;
            font-weight: bold;
            color: #495057;
            border-top: 2px solid #dee2e6;
        }

        .group td {
            padding: 12px 8px !important;
            background: linear-gradient(90deg, #e9ecef 0%, #f8f9fa 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .subgroup {
            background-color: #f1f3f4 !important;
            font-weight: 600;
            color: #6c757d;
        }

        .subgroup td {
            padding: 8px 8px 8px 20px !important;
            background: linear-gradient(90deg, #f1f3f4 0%, #f8f9fa 100%);
            border-bottom: 1px solid #dee2e6;
        }

        .group-icon {
            margin-right: 8px;
            color: #6c757d;
        }

        .price-input {
            text-align: right;
        }
    </style>
@endpush

<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master Detail Produk</h3>
                    <p class="text-subtitle text-muted">Kelola detail produk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Detail Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Detail Produk</h4>
                    <button type="button" class="btn btn-primary" wire:click="openAddModal">
                        <span wire:loading wire:target="openAddModal">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memuat...
                        </span>
                        <span wire:loading.remove wire:target="openAddModal"><i class="fas fa-plus-circle"></i> Tambah
                            Detail</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" wire:ignore>
                        <table class="table table-striped" id="detailTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Aplikasi</th>
                                    <th>Tipe Produk</th>
                                    <th>Durasi</th>
                                    <th>Harga</th>
                                    <th>Catatan</th>
                                    <th>Status</th>
                                    <th>Tanggal Dibuat</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </section>
    </div>

    {{-- Add/Edit Modal --}}
    <div id="detailModal" class="modal fade" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isEditing)
                            Edit Detail Produk
                        @elseif ($bulkMode)
                            Tambah Multiple Detail Produk
                        @else
                            Tambah Detail Produk Baru
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if (!$isEditing)
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" wire:model.live="bulkMode"
                                    id="bulkMode">
                                <label class="form-check-label" for="bulkMode">
                                    <strong>Mode Multiple Detail</strong> - Buat beberapa detail sekaligus untuk satu
                                    produk dan tipe
                                </label>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <form wire:submit="save">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label for="product_id" class="form-label">Produk</label>
                                <select class="form-select @error('product_id') is-invalid @enderror"
                                    wire:model.live="product_id" id="product_id">
                                    <option value="">Pilih Produk</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}">{{ $product->app_name }}</option>
                                    @endforeach
                                </select>
                                @error('product_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="product_type_id" class="form-label">Tipe Produk <small
                                        class="text-muted">(Opsional)</small></label>
                                <select class="form-select @error('product_type_id') is-invalid @enderror"
                                    wire:model="product_type_id" id="product_type_id">
                                    <option value="">Tanpa Tipe</option>
                                    @foreach ($productTypes as $type)
                                        <option value="{{ $type->id }}">{{ $type->type_name }}</option>
                                    @endforeach
                                </select>
                                @error('product_type_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if (empty($productTypes) && $product_id)
                                    <div class="form-text text-muted">Tidak ada tipe produk untuk aplikasi ini</div>
                                @endif
                            </div>
                        </div>

                        @if (!$bulkMode || $isEditing)
                            {{-- Single Mode --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="duration" class="form-label">Durasi</label>
                                    <input type="text" class="form-control @error('duration') is-invalid @enderror"
                                        wire:model="duration" placeholder="Masukkan durasi" id="duration">
                                    @error('duration')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="price" class="form-label">Harga</label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number"
                                            class="form-control price-input @error('price') is-invalid @enderror"
                                            wire:model="price" placeholder="0" id="price" min="0">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="col-md-4">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <input type="text" class="form-control @error('notes') is-invalid @enderror"
                                        wire:model="notes" placeholder="Catatan (opsional)" id="notes"
                                        maxlength="10">
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                        @else
                            {{-- Bulk Mode --}}
                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Daftar Detail yang akan dibuat:</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                    wire:click="addBulkDetail">
                                    <i class="fas fa-plus"></i> Tambah Detail
                                </button>
                            </div>

                            @if ($errors->has('bulkDetails'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('bulkDetails') }}
                                </div>
                            @endif

                            <div class="bulk-details-container" style="max-height: 400px; overflow-y: auto;">
                                @foreach ($bulkDetails as $index => $bulkDetail)
                                    <div class="card mb-3" wire:key="bulk-detail-{{ $index }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">Detail #{{ $index + 1 }}</h6>
                                                @if (count($bulkDetails) > 1)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        wire:click="removeBulkDetail({{ $index }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Durasi</label>
                                                        <input type="text"
                                                            class="form-control @error('bulkDetails.' . $index . '.duration') is-invalid @enderror"
                                                            wire:model="bulkDetails.{{ $index }}.duration"
                                                            placeholder="Masukkan durasi">
                                                        @error('bulkDetails.' . $index . '.duration')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Harga</label>
                                                        <div class="input-group">
                                                            <span class="input-group-text">Rp</span>
                                                            <input type="number"
                                                                class="form-control price-input @error('bulkDetails.' . $index . '.price') is-invalid @enderror"
                                                                wire:model="bulkDetails.{{ $index }}.price"
                                                                placeholder="0" min="0">
                                                        </div>
                                                        @error('bulkDetails.' . $index . '.price')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Catatan</label>
                                                        <input type="text"
                                                            class="form-control @error('bulkDetails.' . $index . '.notes') is-invalid @enderror"
                                                            wire:model="bulkDetails.{{ $index }}.notes"
                                                            placeholder="Catatan (opsional)" maxlength="10">
                                                        @error('bulkDetails.' . $index . '.notes')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        @if ($bulkMode && !$isEditing)
                            Buat Semua Detail
                        @else
                            Simpan
                        @endif
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        $(document).ready(function() {
            // SWEETALERT
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
                    confirmButtonColor: e.detail[0].confirmButtonColor
                });
            });

            // Field update response
            window.addEventListener('details:field-updated', function(e) {
                if (e.detail[0].success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: e.detail[0].message,
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: e.detail[0].message
                    });
                }
            });

            // Initialize DataTable with Row Grouping
            const detailTable = $('#detailTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.product_details.data') }}",
                    type: 'GET'
                },
                columns: [{
                        data: null,
                        orderable: false,
                        searchable: false,
                        className: 'dt-center',
                        render: function(data, type, row, meta) {
                            return meta.row + 1 + meta.settings._iDisplayStart;
                        }
                    },
                    {
                        data: 'product_name',
                        visible: false // Hidden since we're grouping by this
                    },
                    {
                        data: 'type_name',
                        visible: false // Hidden since we're grouping by this
                    },
                    {
                        data: 'duration',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="duration" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'price_formatted',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="price" data-id="${row.id}" data-value="${row.price}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'notes',
                        className: 'editable notes-cell',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="notes" data-id="${row.id}" title="${data}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_deleted',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const checked = data ? 'checked' : '';
                            const badgeClass = data ? 'bg-danger' : 'bg-success';
                            const statusText = data ? 'Dihapus' : 'Aktif';

                            return `
                                <div class="d-flex align-items-center">
                                    <span class="badge ${badgeClass} status-badge me-2">${statusText}</span>
                                    <div class="form-check form-switch">
                                        <input class="form-check-input status-toggle" type="checkbox" ${checked} data-id="${row.id}">
                                    </div>
                                </div>
                            `;
                        }
                    },
                    {
                        data: 'created_at'
                    },
                    {
                        data: null,
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            return `
                                <div class="d-flex">
                                    <button type="button" class="btn btn-sm btn-primary detail-action-btn btn-edit" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [1, 'asc'], // Order by product_name first
                    [2, 'asc'], // Then by type_name
                    [4, 'asc'] // Then by price
                ],
                rowGroup: {
                    dataSrc: function(row) {
                        return row.product_name + '|' + row.type_name;
                    },
                    startRender: function(rows, group) {
                        const parts = group.split('|');
                        const productName = parts[0];
                        const typeName = parts[1];
                        const count = rows.count();
                        const countText = count === 1 ? '1 detail' : count + ' detail';

                        // Handle "Tanpa Tipe" case with different styling
                        const typeDisplay = typeName === 'Tanpa Tipe' ?
                            '<em class="text-muted">' + typeName + '</em>' :
                            '<em>' + typeName + '</em>';

                        return $('<tr class="group">')
                            .append('<td colspan="7"><i class="fas fa-box group-icon"></i><strong>' +
                                productName + '</strong> - ' + typeDisplay +
                                ' <span class="text-muted">(' + countText + ')</span></td>');
                    }
                },
                dom: 'Bfrtip',
                drawCallback: function(settings) {
                    // Re-apply event handlers after each draw
                    bindEventHandlers();
                }
            });

            // Function to bind event handlers
            function bindEventHandlers() {
                // Status toggle
                $('#detailTable').off('change', '.status-toggle').on('change', '.status-toggle', function() {
                    const detailId = $(this).data('id');
                    @this.call('toggleDetailStatus', detailId);
                });

                // Edit button
                $('#detailTable').off('click', '.btn-edit').on('click', '.btn-edit', function() {
                    const detailId = $(this).data('id');
                    @this.call('openEditModal', detailId);
                });

                // Inline editing
                $('#detailTable').off('click', '.editable').on('click', '.editable', function() {
                    const cell = $(this).find('span');
                    const field = cell.data('field');
                    const id = cell.data('id');
                    const originalValue = cell.text().trim();
                    let currentValue = (cell.text() == '-') ? '' : cell.text();

                    // For price field, use the raw value
                    if (field === 'price') {
                        currentValue = cell.data('value') || '';
                    }

                    // Don't allow editing if already in edit mode
                    if (cell.find('input, textarea').length > 0) {
                        return;
                    }

                    // Create input element
                    let inputElement;
                    if (field === 'price') {
                        inputElement = $(
                            `<div class="input-group"><span class="input-group-text">Rp</span><input type="number" class="form-control edit-input price-input" value="${currentValue}" min="0"></div>`
                            );
                    } else {
                        inputElement = $(`<input type="text" class="edit-input" value="${currentValue}">`);
                    }

                    // Replace cell content with input
                    cell.html(inputElement);

                    const actualInput = field === 'price' ? inputElement.find('input') : inputElement;

                    setTimeout(() => {
                        const domElement = actualInput[0];
                        domElement.focus();
                        if (domElement.setSelectionRange) {
                            domElement.setSelectionRange(domElement.value.length, domElement.value
                                .length);
                        }
                    }, 0);

                    // Handle blur event (save)
                    actualInput.on('blur', function() {
                        const newValue = $(this).val();

                        // If value hasn't changed, restore original text
                        if (newValue == currentValue) {
                            cell.text(originalValue);
                            return;
                        }

                        // Update field via Livewire
                        @this.call('updateField', id, field, newValue);

                        window.addEventListener('details:field-updated', function handler(e) {
                            const {
                                success,
                                message
                            } = e.detail[0];

                            if (success) {
                                if (field === 'price') {
                                    const formattedPrice = 'Rp ' + parseInt(newValue)
                                        .toLocaleString('id-ID');
                                    cell.text(formattedPrice);
                                    cell.data('value', newValue);
                                } else {
                                    const displayValue = newValue === '' ? '-' : newValue;
                                    cell.text(displayValue);
                                }
                            } else {
                                alert('Update gagal: ' + message);
                                cell.text(originalValue);
                            }

                            // Remove event listener after execution
                            window.removeEventListener('details:field-updated', handler);
                        });
                    });

                    // Handle Enter key
                    actualInput.on('keypress', function(e) {
                        if (e.which === 13) {
                            $(this).blur();
                        }
                    });

                    // Handle Escape key (cancel)
                    actualInput.on('keydown', function(e) {
                        if (e.which === 27) {
                            cell.text(originalValue);
                        }
                    });
                });
            }

            // Initial binding
            bindEventHandlers();

            const detailModal = new bootstrap.Modal(document.getElementById('detailModal'));

            // Modal events
            window.removeEventListener('details:show-add-modal', window.__showDetailModal);
            window.__showDetailModal = () => detailModal.show();
            window.addEventListener('details:show-add-modal', window.__showDetailModal);

            window.removeEventListener('details:hide-modal', window.__hideDetailModal);
            window.__hideDetailModal = () => detailModal.hide();
            window.addEventListener('details:hide-modal', window.__hideDetailModal);

            window.removeEventListener('details:refresh-datatable', window.__refreshDetailTable);
            window.__refreshDetailTable = () => detailTable.ajax.reload();
            window.addEventListener('details:refresh-datatable', window.__refreshDetailTable);
        });
    </script>
@endpush
