<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Edit User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></div>
                <div class="breadcrumb-item active"><a href="<?= base_url('admin/user-management') ?>">User Management</a></div>
                <div class="breadcrumb-item">Edit User</div>
            </div>
        </div>

        <div class="section-body">
            <h2 class="section-title">Edit Data User</h2>
            <p class="section-lead">Form untuk mengubah data user yang sudah ada.</p>

            <!-- Alert Messages -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <i class="fas fa-check-circle"></i>
                        <?= $this->session->flashdata('success') ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible show fade">
                    <div class="alert-body">
                        <button class="close" data-dismiss="alert">
                            <span>&times;</span>
                        </button>
                        <i class="fas fa-exclamation-circle"></i>
                        <?= $this->session->flashdata('error') ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <form action="<?= base_url('admin/user-management/edit/' . $user->ID) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="card-header">
                                <h4><i class="fas fa-user-edit"></i> Form Edit User</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <!-- Username -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">
                                                Username <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-user"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control <?= form_error('username') ? 'is-invalid' : '' ?>" id="username" name="username" value="<?= set_value('username', $user->USERNAME) ?>" placeholder="Masukkan username">
                                            </div>
                                            <?php if (form_error('username')) : ?>
                                                <div class="invalid-feedback">
                                                    <?= form_error('username') ?>
                                                </div>
                                            <?php endif; ?>
                                            <small class="form-text text-muted">
                                                Username harus unik dan minimal 3 karakter
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Nama Lengkap -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nama">
                                                Nama Lengkap <span class="text-danger">*</span>
                                            </label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-id-card"></i>
                                                    </div>
                                                </div>
                                                <input type="text" class="form-control <?= form_error('nama') ? 'is-invalid' : '' ?>" id="nama" name="nama" value="<?= set_value('nama', $user->NAMA) ?>" placeholder="Masukkan nama lengkap">
                                            </div>
                                            <?php if (form_error('nama')) : ?>
                                                <div class="invalid-feedback">
                                                    <?= form_error('nama') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Role -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="role">
                                                Role <span class="text-danger">*</span>
                                            </label>
                                            <select class="form-control selectric <?= form_error('role') ? 'is-invalid' : '' ?>" id="role" name="role">
                                                <option value="">Pilih Role</option>
                                                <option value="1" <?= set_select('role', '1', $user->ROLE == 1) ?>>Super Admin</option>
                                                <option value="2" <?= set_select('role', '2', $user->ROLE == 2) ?>>Admin</option>
                                                <option value="3" <?= set_select('role', '3', $user->ROLE == 3) ?>>User</option>
                                            </select>
                                            <?php if (form_error('role')) : ?>
                                                <div class="invalid-feedback">
                                                    <?= form_error('role') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>

                                    <!-- Current Image Display -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Foto Saat Ini</label>
                                            <div class="card" style="max-width: 200px;">
                                                <div class="card-body p-2">
                                                    <?php if ($user->IMG) : ?>
                                                        <img src="<?= base_url('uploads/users/' . $user->IMG) ?>" alt="Foto User" class="img-fluid rounded" style="max-height: 150px; width: 100%; object-fit: cover;">
                                                        <div class="text-center mt-2">
                                                            <small class="text-muted"><?= $user->IMG ?></small>
                                                        </div>
                                                    <?php else : ?>
                                                        <div class="text-center p-3">
                                                            <i class="fas fa-user-circle fa-4x text-muted"></i>
                                                            <div class="mt-2">
                                                                <small class="text-muted">Tidak ada foto</small>
                                                            </div>
                                                        </div>
                                                    <?php endif; ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Password Section -->
                                <div class="section-title mt-4">Ubah Password</div>
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i>
                                    Kosongkan field password jika tidak ingin mengubah password
                                </div>

                                <div class="row">
                                    <!-- Password Baru -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password">Password Baru</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                    </div>
                                                </div>
                                                <input type="password" class="form-control <?= form_error('password') ? 'is-invalid' : '' ?>" id="password" name="password" placeholder="Masukkan password baru">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password')">
                                                        <i class="fas fa-eye" id="password-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php if (form_error('password')) : ?>
                                                <div class="invalid-feedback">
                                                    <?= form_error('password') ?>
                                                </div>
                                            <?php endif; ?>
                                            <small class="form-text text-muted">
                                                Minimal 6 karakter
                                            </small>
                                        </div>
                                    </div>

                                    <!-- Konfirmasi Password -->
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="password_confirm">Konfirmasi Password</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <div class="input-group-text">
                                                        <i class="fas fa-lock"></i>
                                                    </div>
                                                </div>
                                                <input type="password" class="form-control <?= form_error('password_confirm') ? 'is-invalid' : '' ?>" id="password_confirm" name="password_confirm" placeholder="Konfirmasi password baru">
                                                <div class="input-group-append">
                                                    <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('password_confirm')">
                                                        <i class="fas fa-eye" id="password_confirm-icon"></i>
                                                    </button>
                                                </div>
                                            </div>
                                            <?php if (form_error('password_confirm')) : ?>
                                                <div class="invalid-feedback">
                                                    <?= form_error('password_confirm') ?>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>

                                <!-- Upload Foto Section -->
                                <div class="section-title mt-4">Upload Foto Baru</div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="img">Pilih Foto</label>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="img" name="img" accept="image/*" onchange="previewImage(this)">
                                                <label class="custom-file-label" for="img">Pilih file...</label>
                                            </div>
                                            <small class="form-text text-muted">
                                                Format: JPG, JPEG, PNG, GIF. Maksimal 2MB
                                            </small>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Preview Foto Baru</label>
                                            <div class="card" style="max-width: 200px;">
                                                <div class="card-body p-2">
                                                    <div id="image-preview" class="text-center p-3">
                                                        <i class="fas fa-image fa-4x text-muted"></i>
                                                        <div class="mt-2">
                                                            <small class="text-muted">Preview akan muncul di sini</small>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/user-management') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update User
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    // Toggle password visibility
    function togglePassword(fieldId) {
        const field = document.getElementById(fieldId);
        const icon = document.getElementById(fieldId + '-icon');

        if (field.type === 'password') {
            field.type = 'text';
            icon.classList.remove('fa-eye');
            icon.classList.add('fa-eye-slash');
        } else {
            field.type = 'password';
            icon.classList.remove('fa-eye-slash');
            icon.classList.add('fa-eye');
        }
    }

    // Preview uploaded image
    function previewImage(input) {
        const preview = document.getElementById('image-preview');
        const label = document.querySelector('.custom-file-label');

        if (input.files && input.files[0]) {
            const reader = new FileReader();

            reader.onload = function(e) {
                preview.innerHTML = `
                <img src="${e.target.result}" 
                     class="img-fluid rounded" 
                     style="max-height: 150px; width: 100%; object-fit: cover;">
                <div class="mt-2">
                    <small class="text-muted">${input.files[0].name}</small>
                </div>
            `;
            }

            reader.readAsDataURL(input.files[0]);
            label.textContent = input.files[0].name;
        } else {
            preview.innerHTML = `
            <i class="fas fa-image fa-4x text-muted"></i>
            <div class="mt-2">
                <small class="text-muted">Preview akan muncul di sini</small>
            </div>
        `;
            label.textContent = 'Pilih file...';
        }
    }

    // Update custom file input label
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize selectric for better select styling
        if (typeof $.fn.selectric !== 'undefined') {
            $('.selectric').selectric();
        }
    });
</script>