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

        #typeTable_filter {
            margin-bottom: 15px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .type-action-btn {
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

        .description-cell {
            max-width: 200px;
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

        .group-icon {
            margin-right: 8px;
            color: #6c757d;
        }

        /* Hide app_name column since it's now grouped */
        #typeTable th:nth-child(2),
        #typeTable td:nth-child(2) {
            display: none;
        }
    </style>
@endpush

<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master Tipe Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data tipe produk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Tipe Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Tipe Produk</h4>
                    <button type="button" class="btn btn-primary" wire:click="openAddModal">
                        <span wire:loading wire:target="openAddModal">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memuat...
                        </span>
                        <span wire:loading.remove wire:target="openAddModal"><i class="fas fa-plus-circle"></i> Tambah
                            Tipe</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" wire:ignore>
                        <table class="table table-striped" id="typeTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Aplikasi</th>
                                    <th>Nama Tipe</th>
                                    <th>Unit</th>
                                    <th>Deskripsi</th>
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
    <div id="typeModal" class="modal fade" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isEditing)
                            Edit Tipe Produk
                        @elseif ($bulkMode)
                            Tambah Multiple Tipe Produk
                        @else
                            Tambah Tipe Produk Baru
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
                                    <strong>Mode Multiple Tipe</strong> - Buat beberapa tipe sekaligus untuk satu
                                    aplikasi
                                </label>
                            </div>
                        </div>
                        <hr>
                    @endif

                    <form wire:submit="save">
                        @if ($bulkMode && !$isEditing)
                            {{-- Bulk Creation Mode --}}
                            <div class="mb-4">
                                <label for="app_name_bulk" class="form-label">Nama Aplikasi</label>
                                <select class="form-select @error('app_name') is-invalid @enderror"
                                    wire:model="app_name" id="app_name_bulk">
                                    <option value="">Pilih Aplikasi</option>
                                    @foreach ($appNames as $appName)
                                        <option value="{{ $appName }}">{{ $appName }}</option>
                                    @endforeach
                                </select>
                                @error('app_name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <h6 class="mb-0">Daftar Tipe yang akan dibuat:</h6>
                                <button type="button" class="btn btn-sm btn-outline-primary" wire:click="addBulkType">
                                    <i class="fas fa-plus"></i> Tambah Tipe
                                </button>
                            </div>

                            @if ($errors->has('bulkTypes'))
                                <div class="alert alert-danger">
                                    {{ $errors->first('bulkTypes') }}
                                </div>
                            @endif

                            <div class="bulk-types-container" style="max-height: 400px; overflow-y: auto;">
                                @foreach ($bulkTypes as $index => $bulkType)
                                    <div class="card mb-3" wire:key="bulk-type-{{ $index }}">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-2">
                                                <h6 class="card-title mb-0">Tipe #{{ $index + 1 }}</h6>
                                                @if (count($bulkTypes) > 1)
                                                    <button type="button" class="btn btn-sm btn-outline-danger"
                                                        wire:click="removeBulkType({{ $index }})">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                @endif
                                            </div>

                                            <div class="row">
                                                <div class="col-md-4">
                                                    <div class="mb-3">
                                                        <label class="form-label">Nama Tipe</label>
                                                        <input type="text"
                                                            class="form-control @error('bulkTypes.' . $index . '.type_name') is-invalid @enderror"
                                                            wire:model="bulkTypes.{{ $index }}.type_name"
                                                            placeholder="Masukkan nama tipe">
                                                        @error('bulkTypes.' . $index . '.type_name')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="mb-3">
                                                        <label class="form-label">Unit</label>
                                                        <input type="text"
                                                            class="form-control @error('bulkTypes.' . $index . '.unit') is-invalid @enderror"
                                                            wire:model="bulkTypes.{{ $index }}.unit"
                                                            placeholder="Unit (opsional)">
                                                        @error('bulkTypes.' . $index . '.unit')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                                <div class="col-md-5">
                                                    <div class="mb-3">
                                                        <label class="form-label">Deskripsi</label>
                                                        <textarea class="form-control @error('bulkTypes.' . $index . '.description') is-invalid @enderror"
                                                            wire:model="bulkTypes.{{ $index }}.description" placeholder="Deskripsi tipe" rows="2"></textarea>
                                                        @error('bulkTypes.' . $index . '.description')
                                                            <div class="invalid-feedback">{{ $message }}</div>
                                                        @enderror
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Single Creation/Edit Mode --}}
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="app_name" class="form-label">Nama Aplikasi</label>
                                        <select class="form-select @error('app_name') is-invalid @enderror"
                                            wire:model="app_name" id="app_name">
                                            <option value="">Pilih Aplikasi</option>
                                            @foreach ($appNames as $appName)
                                                <option value="{{ $appName }}">{{ $appName }}</option>
                                            @endforeach
                                        </select>
                                        @error('app_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="type_name" class="form-label">Nama Tipe</label>
                                        <input type="text"
                                            class="form-control @error('type_name') is-invalid @enderror"
                                            wire:model="type_name" placeholder="Masukkan nama tipe" id="type_name">
                                        @error('type_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="unit" class="form-label">Unit</label>
                                        <input type="text" class="form-control @error('unit') is-invalid @enderror"
                                            wire:model="unit" placeholder="Masukkan unit (opsional)" id="unit">
                                        @error('unit')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-3">
                                        <label for="description" class="form-label">Deskripsi</label>
                                        <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description"
                                            placeholder="Masukkan deskripsi tipe" id="description" rows="3"></textarea>
                                        @error('description')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        @endif
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        @if ($bulkMode && !$isEditing)
                            Buat Semua Tipe
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
            window.addEventListener('types:field-updated', function(e) {
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
            const typeTable = $('#typeTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('admin.product_types.data') }}",
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
                        data: 'app_name',
                        visible: false // Hidden since we're grouping by this
                    },
                    {
                        data: 'type_name',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="type_name" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'unit',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="unit" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'description',
                        className: 'editable description-cell',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="description" data-id="${row.id}" title="${data}">${data}</span>`;
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
                                    <button type="button" class="btn btn-sm btn-primary type-action-btn btn-edit" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [1, 'asc'], // Order by app_name first
                    [2, 'asc'] // Then by type_name
                ],
                rowGroup: {
                    dataSrc: 'app_name',
                    startRender: function(rows, group) {
                        const count = rows.count();
                        const countText = count === 1 ? '1 tipe' : count + ' tipe';

                        return $('<tr class="group">')
                            .append(
                                '<td colspan="8"><i class="fas fa-mobile-alt group-icon"></i><strong>' +
                                group + '</strong> <span class="text-muted">(' + countText +
                                ')</span></td>');
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
                $('#typeTable').off('change', '.status-toggle').on('change', '.status-toggle', function() {
                    const typeId = $(this).data('id');
                    @this.call('toggleTypeStatus', typeId);
                });

                // Edit button
                $('#typeTable').off('click', '.btn-edit').on('click', '.btn-edit', function() {
                    const typeId = $(this).data('id');
                    @this.call('openEditModal', typeId);
                });

                // Inline editing
                $('#typeTable').off('click', '.editable').on('click', '.editable', function() {
                    const cell = $(this).find('span');
                    const field = cell.data('field');
                    const id = cell.data('id');
                    const originalValue = cell.text().trim();
                    const currentValue = (cell.text() == '-') ? '' : cell.text();

                    // Don't allow editing if already in edit mode
                    if (cell.find('input, textarea').length > 0) {
                        return;
                    }

                    // Create input element
                    let inputElement;
                    if (field === 'description') {
                        inputElement = $(`<textarea class="edit-input">${currentValue}</textarea>`);
                    } else if (field === 'app_name') {
                        // Create select for app_name
                        let options = '<option value="">Pilih Aplikasi</option>';
                        @this.appNames.forEach(function(appName) {
                            const selected = appName === currentValue ? 'selected' : '';
                            options += `<option value="${appName}" ${selected}>${appName}</option>`;
                        });
                        inputElement = $(`<select class="edit-input">${options}</select>`);
                    } else {
                        inputElement = $(`<input type="text" class="edit-input" value="${currentValue}">`);
                    }

                    // Replace cell content with input
                    cell.html(inputElement);
                    setTimeout(() => {
                        const domElement = inputElement[0];
                        domElement.focus();
                        if (domElement.setSelectionRange) {
                            domElement.setSelectionRange(domElement.value.length, domElement.value
                                .length);
                        }
                    }, 0);

                    // Handle blur event (save)
                    inputElement.on('blur', function() {
                        const newValue = $(this).val();

                        // If value hasn't changed, restore original text
                        if (newValue === currentValue) {
                            cell.text(originalValue);
                            return;
                        }

                        // Update field via Livewire
                        @this.call('updateField', id, field, newValue);

                        window.addEventListener('types:field-updated', function handler(e) {
                            const {
                                success,
                                message
                            } = e.detail[0];

                            if (success) {
                                const displayValue = newValue === '' ? '-' : newValue;
                                cell.text(displayValue);

                                // Reload table if app_name was changed to update grouping
                                if (field === 'app_name') {
                                    setTimeout(() => {
                                        typeTable.ajax.reload();
                                    }, 1000);
                                }
                            } else {
                                alert('Update gagal: ' + message);
                                cell.text(originalValue);
                            }

                            // Remove event listener after execution
                            window.removeEventListener('types:field-updated', handler);
                        });
                    });

                    // Handle Enter key
                    inputElement.on('keypress', function(e) {
                        if (e.which === 13) {
                            $(this).blur();
                        }
                    });

                    // Handle Escape key (cancel)
                    inputElement.on('keydown', function(e) {
                        if (e.which === 27) {
                            cell.text(originalValue);
                        }
                    });
                });
            }

            // Initial binding
            bindEventHandlers();

            const typeModal = new bootstrap.Modal(document.getElementById('typeModal'));

            // Modal events
            window.removeEventListener('types:show-add-modal', window.__showTypeModal);
            window.__showTypeModal = () => typeModal.show();
            window.addEventListener('types:show-add-modal', window.__showTypeModal);

            window.removeEventListener('types:hide-modal', window.__hideTypeModal);
            window.__hideTypeModal = () => typeModal.hide();
            window.addEventListener('types:hide-modal', window.__hideTypeModal);

            window.removeEventListener('types:refresh-datatable', window.__refreshTypeTable);
            window.__refreshTypeTable = () => typeTable.ajax.reload();
            window.addEventListener('types:refresh-datatable', window.__refreshTypeTable);
        });
    </script>
@endpush
