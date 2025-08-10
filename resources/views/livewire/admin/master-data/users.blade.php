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

        #userTable_filter {
            margin-bottom: 15px;
        }

        .dt-buttons {
            margin-bottom: 15px;
        }

        .user-action-btn {
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
    </style>
@endpush

<div>
    <div class="page-heading">
        <div class="page-title">
            <div class="row">
                <div class="col-12 col-md-6 order-md-1 order-last">
                    <h3>Data Master Pengguna</h3>
                    <p class="text-subtitle text-muted">Kelola data pengguna sistem</p>
                </div>
                <div class="col-12 col-md-6 order-md-2 order-first">
                    <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}"
                                    wire:navigate>Dashboard</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Pengguna</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <section class="section">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="card-title">Daftar Pengguna</h4>
                    <button type="button" class="btn btn-primary" wire:click="openAddModal">
                        <span wire:loading wire:target="openAddModal">
                            <span class="spinner-border spinner-border-sm me-1"></span>
                            Memuat...
                        </span>
                        <span wire:loading.remove wire:target="openAddModal"><i class="fas fa-plus-circle"></i> Tambah
                            Pengguna</span>
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive" wire:ignore>
                        <table class="table table-striped" id="userTable">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Username</th>
                                    <th>Nama Lengkap</th>
                                    <th>Email</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat</th>
                                    <th>Status</th>
                                    <th>Tanggal Bergabung</th>
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
    <div id="userModal" class="modal fade" tabindex="-1" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">
                        @if ($isEditing)
                            Edit Pengguna
                        @else
                            Tambah Pengguna Baru
                        @endif
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form wire:submit="save">
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control @error('username') is-invalid @enderror"
                                wire:model="username" placeholder="Masukkan username" id="username">
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="name" class="form-label">Nama Lengkap</label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror"
                                wire:model="name" placeholder="Masukkan nama lengkap" id="name">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror"
                                wire:model="email" placeholder="Masukkan email" id="email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        @if (!$isEditing)
                            <div class="mb-3">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" id="password"
                                    class="form-control @error('password') is-invalid @enderror" wire:model="password"
                                    placeholder="Masukkan password">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password_confirmation" class="form-label">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation"
                                    class="form-control @error('password_confirmation') is-invalid @enderror"
                                    wire:model="password_confirmation" placeholder="Konfirmasi password">
                                @error('password_confirmation')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        @endif

                        <div class="mb-3">
                            <label for="phone" class="form-label">No. Telepon</label>
                            <input type="text" class="form-control @error('phone') is-invalid @enderror"
                                wire:model="phone" placeholder="Masukkan no. telepon" id="phone">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="address" class="form-label">Alamat</label>
                            <textarea class="form-control @error('address') is-invalid @enderror" wire:model="address"
                                placeholder="Masukkan alamat" id="address" rows="3"></textarea>
                            @error('address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                        <span wire:loading wire:target="save" class="spinner-border spinner-border-sm me-1"></span>
                        Simpan
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
            window.addEventListener('users:field-updated', function(e) {
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

            // Initialize DataTable
            const userTable = $('#userTable').DataTable({
                processing: true,
                serverSide: true,
                responsive: false,
                scrollX: true,
                ajax: {
                    url: "{{ route('admin.users.data') }}",
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
                        data: 'username',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="username" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'name',
                        className: 'editable text-nowrap',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="name" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'email',
                        className: 'editable',
                        render: function(data, type, row) {
                            if (type === 'display') {
                                return `<span data-field="email" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'phone',
                        className: 'editable',
                        render: function(data, type, row) {
                            data = data || '-';
                            if (type === 'display') {
                                return `<span data-field="phone" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'address',
                        className: 'editable',
                        render: function(data, type, row) {
                            data = data || '-';
                            if (type === 'display') {
                                return `<span data-field="address" data-id="${row.id}">${data}</span>`;
                            }
                            return data;
                        }
                    },
                    {
                        data: 'is_active',
                        orderable: false,
                        searchable: false,
                        render: function(data, type, row) {
                            const checked = data ? 'checked' : '';
                            const badgeClass = data ? 'bg-success' : 'bg-danger';
                            const statusText = data ? 'Aktif' : 'Nonaktif';

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
                                    <button type="button" class="btn btn-sm btn-primary user-action-btn btn-edit" data-id="${row.id}">
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
            $('#userTable').on('change', '.status-toggle', function() {
                const userId = $(this).data('id');
                @this.call('toggleUserStatus', userId);
            });

            // Edit button
            $('#userTable').on('click', '.btn-edit', function() {
                const userId = $(this).data('id');
                @this.call('openEditModal', userId);
            });

            // Inline editing
            $('#userTable').on('click', '.editable', function() {
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
                if (field === 'address') {
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

                    window.addEventListener('users:field-updated', function handler(e) {
                        const {
                            success,
                            message
                        } = e.detail[0];

                        if (success) {
                            const displayValue = newValue === '' ? '-' :
                                newValue;
                            cell.text(displayValue);
                        } else {
                            alert('Update gagal: ' + message);
                            cell.text(originalValue);
                        }

                        // Hapus event listener setelah dieksekusi sekali
                        window.removeEventListener('users:field-updated', handler);
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

            const userModal = new bootstrap.Modal(document.getElementById('userModal'));

            // Modal events
            window.removeEventListener('users:show-add-modal', window.__showUserModal);
            window.__showUserModal = () => userModal.show();
            window.addEventListener('users:show-add-modal', window.__showUserModal);

            window.removeEventListener('users:hide-modal', window.__hideUserModal);
            window.__hideUserModal = () => userModal.hide();
            window.addEventListener('users:hide-modal', window.__hideUserModal);

            window.removeEventListener('users:refresh-datatable', window.__refreshUserTable);
            window.__refreshUserTable = () => userTable.ajax.reload();
            window.addEventListener('users:refresh-datatable', window.__refreshUserTable);
        });
    </script>
@endpush
