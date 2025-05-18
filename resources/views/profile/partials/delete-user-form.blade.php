<section class="delete-account-section">
    <header class="mb-4">
        <h2 class="h4 fw-semibold text-danger">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ __('Hapus Akun') }}
        </h2>
        <p class="text-muted small">
            {{ __('Setelah akun Anda dihapus, semua data dan informasi akan dihapus secara permanen. Sebelum menghapus akun, silakan unduh data atau informasi yang ingin Anda simpan.') }}
        </p>
        <hr class="mt-3 border-danger-subtle">
    </header>

    <!-- Delete Account Button -->
    <div class="d-grid gap-2 col-md-4">
        <button type="button" class="btn btn-outline-danger" 
                data-bs-toggle="modal" data-bs-target="#confirmDeleteModal">
            <i class="bi bi-trash3 me-2"></i>{{ __('Hapus Akun Permanen') }}
        </button>
    </div>

    <!-- Confirmation Modal -->
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger bg-opacity-10 border-danger-subtle">
                    <h5 class="modal-title text-danger" id="confirmDeleteModalLabel">
                        <i class="bi bi-exclamation-octagon me-2"></i>{{ __('Konfirmasi Hapus Akun') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="post" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('delete')
                    
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <h5 class="alert-heading fw-bold mb-2">{{ __('Apakah Anda yakin ingin menghapus akun?') }}</h5>
                            <p>{{ __('Setelah akun Anda dihapus, semua data dan riwayat pemesanan akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.') }}</p>
                        </div>
                        
                        <p class="mb-3 fw-medium">Masukkan password Anda untuk konfirmasi:</p>
                        
                        <div class="mb-3">
                            <div class="input-group">
                                <span class="input-group-text bg-light">
                                    <i class="bi bi-lock"></i>
                                </span>
                                <input 
                                    id="password" 
                                    name="password" 
                                    type="password"
                                    class="form-control @error('password', 'userDeletion') is-invalid @enderror"
                                    placeholder="{{ __('Password') }}"
                                    required
                                >
                                <button class="btn btn-outline-secondary toggle-password" type="button" 
                                        data-target="password">
                                    <i class="bi bi-eye"></i>
                                </button>
                            </div>
                            @error('password', 'userDeletion')
                                <div class="invalid-feedback d-block mt-1">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="confirmDelete" required>
                            <label class="form-check-label" for="confirmDelete">
                                Saya memahami tindakan ini bersifat permanen dan tidak dapat dibatalkan
                            </label>
                        </div>
                    </div>
                    
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                            <i class="bi bi-x-circle me-1"></i>{{ __('Batal') }}
                        </button>
                        <button type="submit" class="btn btn-danger" id="deleteAccountBtn" disabled>
                            <i class="bi bi-trash3 me-1"></i>{{ __('Hapus Akun Saya') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- JavaScript for Password Toggle & Submit Button Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const inputField = document.getElementById(targetId);
                    const icon = this.querySelector('i');
                    
                    if (inputField.type === 'password') {
                        inputField.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        inputField.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            });
            
            // Enable delete button only when checkbox is checked
            const confirmCheckbox = document.getElementById('confirmDelete');
            const deleteButton = document.getElementById('deleteAccountBtn');
            
            confirmCheckbox.addEventListener('change', function() {
                deleteButton.disabled = !this.checked;
            });
        });
    </script>
</section>

<style>
    .delete-account-section .btn-outline-danger {
        transition: all 0.3s ease;
    }
    
    .delete-account-section .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
        box-shadow: 0 4px 8px rgba(220, 53, 69, 0.2);
    }
    
    .modal-dialog {
        max-width: 500px;
    }
    
    .modal-content {
        border-radius: 10px;
        overflow: hidden;
    }
    
    .input-group-text {
        border-right: none;
    }
    
    .form-control {
        border-left: none;
    }
    
    .toggle-password {
        z-index: 10;
    }
    
    /* Animation for the modal */
    .modal.fade .modal-dialog {
        transition: transform 0.3s ease-out;
    }
    
    .modal.show .modal-dialog {
        transform: none;
    }
</style>