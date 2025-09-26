<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>User Management</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin/user-management') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">User Management</div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <!-- Header -->

                <!-- Flash Messages -->
                <?php if ($this->session->flashdata('error')) : ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-triangle me-2"></i>
                        <?= $this->session->flashdata('error') ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <!-- Add User Form -->
                <div class="row justify-content-center">
                    <div class="col-lg-12">
                        <div class="card shadow">
                            <div class="card-header bg-primary text-white">
                                <h5 class="mb-0">
                                    <i class="fas fa-user-plus me-2"></i>
                                    Form Tambah User
                                </h5>
                            </div>
                            <div class="card-body">
                                <?= form_open_multipart('admin/user-management/tambah', ['id' => 'addUserForm']) ?>
                                <div class="row">
                                    <!-- Left Column -->
                                    <div class="col-md-6">
                                        <!-- Username -->
                                        <div class="mb-3">
                                            <label for="username" class="form-label">
                                                Username <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user"></i>
                                                </span>
                                                <input type="text" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= set_value('username') ?>" placeholder="Masukkan username" maxlength="20" required>
                                                <div class="invalid-feedback">
                                                    <?= form_error('username') ?>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                Username minimal 3 karakter, maksimal 20 karakter
                                            </small>
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label for="password" class="form-label">
                                                Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input type="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Masukkan password" required>
                                                <button type="button" class="btn btn-outline-secondary" id="togglePassword">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="invalid-feedback">
                                                    <?= form_error('password') ?>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                Password minimal 6 karakter
                                            </small>
                                        </div>

                                        <!-- Confirm Password -->
                                        <div class="mb-3">
                                            <label for="password_confirm" class="form-label">
                                                Konfirmasi Password <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-lock"></i>
                                                </span>
                                                <input type="password" class="form-control <?= form_error('password_confirm') ? 'is-invalid' : '' ?>" id="password_confirm" name="password_confirm" placeholder="Konfirmasi password" required>
                                                <button type="button" class="btn btn-outline-secondary" id="togglePasswordConfirm">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                                <div class="invalid-feedback">
                                                    <?= form_error('password_confirm') ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Right Column -->
                                    <div class="col-md-6">
                                        <!-- Full Name -->
                                        <div class="mb-3">
                                            <label for="nama" class="form-label">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-id-card"></i>
                                                </span>
                                                <input type="text" class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= set_value('nama') ?>" placeholder="Masukkan nama lengkap" maxlength="50" required>
                                                <div class="invalid-feedback">
                                                    <?= form_error('nama') ?>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Role -->
                                        <div class="mb-3">
                                            <label for="role" class="form-label">
                                                Role <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <span class="input-group-text">
                                                    <i class="fas fa-user-tag"></i>
                                                </span>
                                                <select class="form-select <?= form_error('role') ? 'is-invalid' : '' ?>" id="role" name="role" required>
                                                    <option value="">Pilih Role</option>
                                                    <option value="1" <?= set_select('role', '1') ?>>Administrator</option>
                                                    <option value="2" <?= set_select('role', '2') ?>>User</option>
                                                    <option value="3" <?= set_select('role', '3') ?>>PPTK</option>
                                                </select>
                                                <div class="invalid-feedback">
                                                    <?= form_error('role') ?>
                                                </div>
                                            </div>
                                            <small class="form-text text-muted">
                                                Pilih role sesuai dengan hak akses yang diinginkan
                                            </small>
                                        </div>

                                        <!-- Profile Photo -->
                                        <div class="mb-3">
                                            <label for="img" class="form-label">
                                                Foto Profil
                                            </label>
                                            <div class="input-group">
                                                <input type="file" class="form-control" id="img" name="img" accept="image/*">
                                                <label class="input-group-text" for="img">
                                                    <i class="fas fa-camera"></i>
                                                </label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Format yang diperbolehkan: JPG, JPEG, PNG, GIF. Maksimal 2MB.
                                            </small>

                                            <!-- Image Preview -->
                                            <div id="imagePreview" class="mt-2" style="display: none;">
                                                <img id="previewImg" src="" class="img-thumbnail" width="150" height="150" alt="Preview">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Submit Buttons -->
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <a href="<?= base_url('admin/user-management') ?>" class="btn btn-secondary">
                                        <i class="fas fa-times me-1"></i>
                                        Batal
                                    </a>
                                    <button type="submit" class="btn btn-primary" id="submitBtn">
                                        <i class="fas fa-save me-1"></i>
                                        Simpan User
                                    </button>
                                </div>

                                <?= form_close() ?>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Role Information -->
                <div class="row justify-content-center mt-4">
                    <div class="col-lg-12">
                        <div class="card border-info">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Informasi Role
                                </h6>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-danger me-2">Administrator</span>
                                            <small>Akses penuh ke semua fitur sistem</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-success me-2">User</span>
                                            <small>Dapat mengajukan permintaan barang</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="d-flex align-items-center mb-2">
                                            <span class="badge bg-info me-2">PPTK</span>
                                            <small>Pejabat Pelaksana Teknis Kegiatan</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        // Toggle password visibility
        $('#togglePassword').click(function() {
            const passwordInput = $('#password');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Toggle confirm password visibility
        $('#togglePasswordConfirm').click(function() {
            const passwordInput = $('#password_confirm');
            const icon = $(this).find('i');

            if (passwordInput.attr('type') === 'password') {
                passwordInput.attr('type', 'text');
                icon.removeClass('fa-eye').addClass('fa-eye-slash');
            } else {
                passwordInput.attr('type', 'password');
                icon.removeClass('fa-eye-slash').addClass('fa-eye');
            }
        });

        // Image preview
        $('#img').change(function() {
            const file = this.files[0];
            if (file) {
                // Check file size (2MB = 2048KB)
                if (file.size > 2048 * 1024) {
                    alert('Ukuran file terlalu besar! Maksimal 2MB.');
                    $(this).val('');
                    $('#imagePreview').hide();
                    return;
                }

                // Check file type
                const allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
                if (!allowedTypes.includes(file.type)) {
                    alert('Tipe file tidak diperbolehkan! Gunakan JPG, JPEG, PNG, atau GIF.');
                    $(this).val('');
                    $('#imagePreview').hide();
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    $('#previewImg').attr('src', e.target.result);
                    $('#imagePreview').show();
                };
                reader.readAsDataURL(file);
            } else {
                $('#imagePreview').hide();
            }
        });

        // Form validation
        $('#addUserForm').submit(function(e) {
            let isValid = true;

            // Check password match
            if ($('#password').val() !== $('#password_confirm').val()) {
                $('#password_confirm').addClass('is-invalid');
                $('#password_confirm').siblings('.invalid-feedback').text('Password tidak cocok');
                isValid = false;
            } else {
                $('#password_confirm').removeClass('is-invalid');
            }

            // Check password length
            if ($('#password').val().length < 6) {
                $('#password').addClass('is-invalid');
                $('#password').siblings('.invalid-feedback').text('Password minimal 6 karakter');
                isValid = false;
            } else {
                $('#password').removeClass('is-invalid');
            }

            // Check username length
            const username = $('#username').val();
            if (username.length < 3 || username.length > 20) {
                $('#username').addClass('is-invalid');
                $('#username').siblings('.invalid-feedback').text('Username harus 3-20 karakter');
                isValid = false;
            } else {
                $('#username').removeClass('is-invalid');
            }

            if (!isValid) {
                e.preventDefault();
            } else {
                // Show loading state
                $('#submitBtn').html('<i class="fas fa-spinner fa-spin me-1"></i>Menyimpan...').prop('disabled', true);
            }
        });

        // Real-time username validation
        $('#username').on('input', function() {
            const username = $(this).val();

            if (username.length >= 3) {
                // Check username availability via AJAX
                $.post('<?= base_url('admin/user-management/check_username') ?>', {
                    username: username
                }, function(response) {
                    if (response.status) {
                        $('#username').removeClass('is-invalid').addClass('is-valid');
                    } else {
                        $('#username').removeClass('is-valid').addClass('is-invalid');
                        $('#username').siblings('.invalid-feedback').text(response.message);
                    }
                }, 'json');
            }
        });

        // Password strength indicator
        $('#password').on('input', function() {
            const password = $(this).val();
            let strength = 0;
            let feedback = '';

            if (password.length >= 6) strength++;
            if (password.match(/[a-z]/)) strength++;
            if (password.match(/[A-Z]/)) strength++;
            if (password.match(/[0-9]/)) strength++;
            if (password.match(/[^a-zA-Z0-9]/)) strength++;

            switch (strength) {
                case 0:
                case 1:
                    feedback = '<small class="text-danger">Lemah</small>';
                    break;
                case 2:
                    feedback = '<small class="text-warning">Sedang</small>';
                    break;
                case 3:
                    feedback = '<small class="text-info">Baik</small>';
                    break;
                case 4:
                case 5:
                    feedback = '<small class="text-success">Kuat</small>';
                    break;
            }

            $(this).siblings('.form-text').html('Password minimal 6 karakter. Kekuatan: ' + feedback);
        });
    });
</script>

<style>
    .card {
        border: none;
        border-radius: 10px;
    }

    .input-group-text {
        background-color: #f8f9fa;
        border-color: #dee2e6;
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .is-invalid {
        border-color: #dc3545;
    }

    .is-valid {
        border-color: #198754;
    }

    #imagePreview {
        text-align: center;
    }

    #previewImg {
        border-radius: 10px;
        object-fit: cover;
    }

    .btn {
        border-radius: 6px;
    }

    .badge {
        font-size: 0.75em;
    }
</style>