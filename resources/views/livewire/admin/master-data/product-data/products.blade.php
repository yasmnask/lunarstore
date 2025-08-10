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

        #productTable_filter {
            margin-bottom: 15px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .product-action-btn {
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

        .product-image {
            width: 80px;
            height: 80px;
            object-fit: cover;
            border-radius: 4px;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .product-image:hover {
            transform: scale(1.1);
        }

        .image-upload-area {
            border: 2px dashed #dee2e6;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            transition: border-color 0.3s ease;
            cursor: pointer;
        }

        .image-upload-area:hover {
            border-color: #007bff;
        }

        .image-upload-area.dragover {
            border-color: #007bff;
            background-color: #f8f9fa;
        }

        .current-image-preview {
            max-width: 200px;
            max-height: 150px;
            object-fit: cover;
            border-radius: 8px;
            border: 1px solid #dee2e6;
        }

        .upload-progress {
            margin-top: 10px;
        }

        .description-cell,
        .notes-cell {
            max-width: 400px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
@endpush

<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data produk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Produk</h4>
                    <button type="button" class="btn btn-primary" wire:click="openAddModal">
                        <span wire:loading wire:target="openAddModal">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memuat...
                        </span>
                        <span wire:loading.remove wire:target="openAddModal"><i class="fas fa-plus-circle"></i> Tambah
                            Produk</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" wire:ignore>
                        <table class="table table-striped" id="productTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Gambar</th>
                                    <th class="text-nowrap">Nama Aplikasi</th>
                                    <th>Kategori</th>
                                    <th>Deskripsi</th>
                                    <th>Catatan</th>
                                    <th class="text-nowrap">Top Up</th>
                                    <th>Stok</th>
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
    <div id="productModal" class="modal fade" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isEditing)
                            Edit Produk
                        @else
                            Tambah Produk Baru
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="app_name" class="form-label">Nama Aplikasi</label>
                                            <input type="text"
                                                class="form-control @error('app_name') is-invalid @enderror"
                                                wire:model="app_name" placeholder="Masukkan nama aplikasi"
                                                id="app_name">
                                            @error('app_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="category_id" class="form-label">Kategori</label>
                                            <select class="form-select @error('category_id') is-invalid @enderror"
                                                wire:model="category_id" id="category_id">
                                                <option value="">Pilih Kategori</option>
                                                @foreach ($categories as $category)
                                                    <option value="{{ $category->id }}">{{ $category->title }}</option>
                                                @endforeach
                                            </select>
                                            @error('category_id')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description"
                                        placeholder="Masukkan deskripsi produk" id="description" rows="4"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="notes" class="form-label">Catatan</label>
                                    <textarea class="form-control @error('notes') is-invalid @enderror" wire:model="notes"
                                        placeholder="Masukkan catatan produk" id="notes" rows="3"></textarea>
                                    @error('notes')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" wire:model="is_topup"
                                                    id="is_topup">
                                                <label class="form-check-label" for="is_topup">
                                                    Produk Top Up
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox"
                                                    wire:model="ready_stock" id="ready_stock">
                                                <label class="form-check-label" for="ready_stock">
                                                    Stok Tersedia
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Gambar Produk</label>

                                    {{-- Current Image Preview --}}
                                    @if ($currentImageUrl && !$imageFile)
                                        <div class="mb-3">
                                            <img src="{{ $currentImageUrl }}" alt="Current Image"
                                                class="current-image-preview d-block">
                                            <button type="button" class="btn btn-sm btn-outline-danger mt-2"
                                                wire:click="removeCurrentImage">
                                                <i class="fas fa-trash"></i> Hapus Gambar
                                            </button>
                                        </div>
                                    @endif

                                    {{-- File Upload Area --}}
                                    <div class="image-upload-area"
                                        onclick="document.getElementById('imageFile').click()"
                                        style="overflow: hidden; word-wrap: break-word;">
                                        @if ($imageFile)
                                            <div class="mb-2">
                                                <img src="{{ $imageFile->temporaryUrl() }}" alt="Preview"
                                                    class="current-image-preview">
                                            </div>
                                            <p class="mb-0 text-success text-wrap"
                                                style="word-break: break-word; max-width: 100%;">
                                                <i class="fas fa-check-circle"></i>
                                                {{ $imageFile->getClientOriginalName() }}
                                            </p>
                                        @else
                                            <div class="mb-2">
                                                <i class="fas fa-cloud-upload-alt fa-2x text-muted"></i>
                                            </div>
                                            <p class="mb-0 text-muted">
                                                Klik untuk upload gambar<br>
                                                <small>Format: JPG, PNG, GIF, WEBP (Max: 5MB)</small>
                                            </p>
                                        @endif
                                    </div>

                                    <input type="file" id="imageFile" wire:model="imageFile" accept="image/*"
                                        style="display: none;">

                                    @error('imageFile')
                                        <div class="text-danger mt-2">{{ $message }}</div>
                                    @enderror

                                    {{-- Upload Progress --}}
                                    <div wire:loading wire:target="imageFile" class="upload-progress">
                                        <div class="progress">
                                            <div class="progress-bar progress-bar-striped progress-bar-animated"
                                                role="progressbar" style="width: 100%">
                                                Memproses gambar...
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="save">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        <span wire:loading.remove wire:target="save">Simpan</span>
                        <span wire:loading wire:target="save">
                            @if ($isUploading)
                                Mengupload...
                            @else
                                Menyimpan...
                            @endif
                        </span>
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Image View Modal --}}
    <div id="imageViewModal" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Gambar Produk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="fullImage" src="https://placehold.co/300x200" alt="Product Image" class="img-fluid">
                </div>
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
            window.addEventListener('products:field-updated', function(e) {
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

            // Drag and drop functionality
            $('.image-upload-area').on('dragover', function(e) {
                e.preventDefault();
                $(this).addClass('dragover');
            });

            $('.image-upload-area').on('dragleave', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');
            });

            $('.image-upload-area').on('drop', function(e) {
                e.preventDefault();
                $(this).removeClass('dragover');

                const files = e.originalEvent.dataTransfer.files;
                if (files.length > 0) {
                    document.getElementById('imageFile').files = files;
                    document.getElementById('imageFile').dispatchEvent(new Event('change'));
                }
            });

            // Initialize DataTable
            const productTable = $('#productTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.products.data') }}",
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
                        data: 'cover_img',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data) {
                                return `<img src="${data}" alt="${row.app_name}" class="product-image" onclick="viewImage('${data}')">`;
                            } else {
                                return '<span class="text-muted">-</span>';
                            }
                        }
                    },
                    {
                        data: 'app_name',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="app_name" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'category_name'
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
                        data: 'is_topup',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const badgeClass = data ? 'bg-success' : 'bg-secondary';
                            const statusText = data ? 'Ya' : 'Tidak';
                            return `<span class="badge ${badgeClass} status-badge">${statusText}</span>`;
                        }
                    },
                    {
                        data: 'ready_stock',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const badgeClass = data ? 'bg-success' : 'bg-danger';
                            const statusText = data ? 'Tersedia' : 'Habis';
                            return `<span class="badge ${badgeClass} status-badge">${statusText}</span>`;
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
                                    <button type="button" class="btn btn-sm btn-primary product-action-btn btn-edit" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ],
                dom: 'Bfrtip',
            });

            // View image function
            window.viewImage = function(src) {
                $('#fullImage').attr('src', src);
                $('#imageViewModal').modal('show');
            };

            // Status toggle
            $('#productTable').on('change', '.status-toggle', function() {
                const productId = $(this).data('id');
                @this.call('toggleProductStatus', productId);
            });

            // Edit button
            $('#productTable').on('click', '.btn-edit', function() {
                const productId = $(this).data('id');
                @this.call('openEditModal', productId);
            });

            // Inline editing
            $('#productTable').on('click', '.editable', function() {
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
                if (field === 'description' || field === 'notes') {
                    inputElement = $(`<textarea class="edit-input">${currentValue}</textarea>`);
                } else {
                    inputElement = $(`<input type="text" class="edit-input" value="${currentValue}">`);
                }

                // Replace cell content with input
                cell.html(inputElement);
                setTimeout(() => {
                    const domElement = inputElement[0];
                    domElement.focus();
                    domElement.setSelectionRange(domElement.value.length, domElement.value.length);
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

                    window.addEventListener('products:field-updated', function handler(e) {
                        const {
                            success,
                            message
                        } = e.detail[0];

                        if (success) {
                            const displayValue = newValue === '' ? '-' : newValue;
                            cell.text(displayValue);
                        } else {
                            alert('Update gagal: ' + message);
                            cell.text(originalValue);
                        }

                        // Remove event listener after execution
                        window.removeEventListener('products:field-updated', handler);
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

            const productModal = new bootstrap.Modal(document.getElementById('productModal'));

            // Modal events
            window.removeEventListener('products:show-add-modal', window.__showProductModal);
            window.__showProductModal = () => productModal.show();
            window.addEventListener('products:show-add-modal', window.__showProductModal);

            window.removeEventListener('products:hide-modal', window.__hideProductModal);
            window.__hideProductModal = () => productModal.hide();
            window.addEventListener('products:hide-modal', window.__hideProductModal);

            window.removeEventListener('products:refresh-datatable', window.__refreshProductTable);
            window.__refreshProductTable = () => productTable.ajax.reload();
            window.addEventListener('products:refresh-datatable', window.__refreshProductTable);
        });
    </script>
@endpush
