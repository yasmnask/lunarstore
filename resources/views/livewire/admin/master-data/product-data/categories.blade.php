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

        #categoryTable_filter {
            margin-bottom: 15px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .category-action-btn {
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

        .image-preview {
            max-width: 135px;
            max-height: 135px;
            object-fit: cover;
            border-radius: 4px;
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
    </style>
@endpush

<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master Kategori Produk</h3>
                    <p class="text-subtitle text-muted">Kelola data kategori produk sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Kategori Produk</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Kategori Produk</h4>
                    <button type="button" class="btn btn-primary" wire:click="openAddModal">
                        <span wire:loading wire:target="openAddModal">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memuat...
                        </span>
                        <span wire:loading.remove wire:target="openAddModal"><i class="fas fa-plus-circle"></i> Tambah
                            Kategori</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" wire:ignore>
                        <table class="table table-striped" id="categoryTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Judul</th>
                                    <th>Slug</th>
                                    <th>Deskripsi</th>
                                    <th>Gambar</th>
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
    <div id="categoryModal" class="modal fade" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isEditing)
                            Edit Kategori
                        @else
                            Tambah Kategori Baru
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="row">
                            <div class="col-md-8">
                                <div class="mb-3">
                                    <label for="title" class="form-label">Judul Kategori</label>
                                    <input type="text" class="form-control @error('title') is-invalid @enderror"
                                        wire:model.live="title" placeholder="Masukkan judul kategori" id="title">
                                    @error('title')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="slug" class="form-label">Slug</label>
                                    <input type="text" class="form-control @error('slug') is-invalid @enderror"
                                        wire:model="slug" placeholder="Slug akan dibuat otomatis" id="slug">
                                    @error('slug')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Slug akan dibuat otomatis dari judul kategori</div>
                                </div>

                                <div class="mb-3">
                                    <label for="description" class="form-label">Deskripsi</label>
                                    <textarea class="form-control @error('description') is-invalid @enderror" wire:model="description"
                                        placeholder="Masukkan deskripsi kategori" id="description" rows="3"></textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-4">
                                <div class="mb-3">
                                    <label class="form-label">Gambar Kategori</label>

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
            window.addEventListener('categories:field-updated', function(e) {
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
            const categoryTable = $('#categoryTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.products.categories.data') }}",
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
                        data: 'title',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="title" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'slug',
                        className: 'editable text-nowrap',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="slug" data-id="${row.id}" class="text-muted"><code>${data}</code></span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'description',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="description" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'image',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            if (data && data !== '-') {
                                return `
                                    <div class="d-flex flex-column justify-content-center align-items-center gap-2">
                                        <img src="${data}" alt="Category Image" class="image-preview" onerror="this.style.display='none'">
                                        <a href="${data}" target="_blank" class="btn btn-sm btn-outline-primary">
                                            <i class="fas fa-external-link-alt"></i>
                                        </a>
                                    </div>
                                `;
                            } else {
                                return '<span class="text-muted">-</span>';
                            }
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
                                    <button type="button" class="btn btn-sm btn-primary category-action-btn btn-edit" data-id="${row.id}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </div>
                            `;
                        }
                    }
                ],
                order: [
                    [1, 'asc']
                ],
                dom: 'Bfrtip',
            });

            // Status toggle
            $('#categoryTable').on('change', '.status-toggle', function() {
                const categoryId = $(this).data('id');
                @this.call('toggleCategoryStatus', categoryId);
            });

            // Edit button
            $('#categoryTable').on('click', '.btn-edit', function() {
                const categoryId = $(this).data('id');
                @this.call('openEditModal', categoryId);
            });

            // Inline editing (only for text fields, not image)
            $('#categoryTable').on('click', '.editable', function() {
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

                    window.addEventListener('categories:field-updated', function handler(e) {
                        const {
                            success,
                            message,
                            refresh_slug
                        } = e.detail[0];

                        if (success) {
                            const displayValue = newValue === '' ? '-' : newValue;

                            if (field === 'title') {
                                cell.text(displayValue);
                            } else if (field === 'slug') {
                                cell.html(`<code>${displayValue}</code>`);
                            } else {
                                cell.text(displayValue);
                            }

                            // Refresh table if slug was updated due to title change
                            if (refresh_slug) {
                                setTimeout(() => {
                                    categoryTable.ajax.reload();
                                }, 1000);
                            }
                        } else {
                            alert('Update gagal: ' + message);
                            cell.text(originalValue);
                        }

                        // Remove event listener after execution
                        window.removeEventListener('categories:field-updated', handler);
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

            const categoryModal = new bootstrap.Modal(document.getElementById('categoryModal'));

            // Modal events
            window.removeEventListener('categories:show-add-modal', window.__showCategoryModal);
            window.__showCategoryModal = () => categoryModal.show();
            window.addEventListener('categories:show-add-modal', window.__showCategoryModal);

            window.removeEventListener('categories:hide-modal', window.__hideCategoryModal);
            window.__hideCategoryModal = () => categoryModal.hide();
            window.addEventListener('categories:hide-modal', window.__hideCategoryModal);

            window.removeEventListener('categories:refresh-datatable', window.__refreshCategoryTable);
            window.__refreshCategoryTable = () => categoryTable.ajax.reload();
            window.addEventListener('categories:refresh-datatable', window.__refreshCategoryTable);
        });
    </script>
@endpush
