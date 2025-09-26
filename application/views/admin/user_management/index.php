<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Manajemen User</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Manajemen User</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Flash Messages -->
            <?php if ($this->session->flashdata('success')) : ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle"></i> <?= $this->session->flashdata('success') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <?php if ($this->session->flashdata('error')) : ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="fas fa-exclamation-triangle"></i> <?= $this->session->flashdata('error') ?>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            <?php endif; ?>

            <!-- Statistics Cards -->
            <div class="row">
                <?php
                $admin_count = 0;
                $user_count = 0;
                $pptk_count = 0;

                foreach ($users as $user) {
                    switch ($user->ROLE) {
                        case 1:
                            $admin_count++;
                            break;
                        case 2:
                            $user_count++;
                            break;
                        case 3:
                            $pptk_count++;
                            break;
                    }
                }
                ?>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-primary">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Total User</h4>
                            </div>
                            <div class="card-body">
                                <?= count($users) ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-user-shield"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Administrator</h4>
                            </div>
                            <div class="card-body">
                                <?= $admin_count ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-success">
                            <i class="fas fa-user"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>User</h4>
                            </div>
                            <div class="card-body">
                                <?= $user_count ?>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                    <div class="card card-statistic-1">
                        <div class="card-icon bg-info">
                            <i class="fas fa-user-tie"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>PPTK</h4>
                            </div>
                            <div class="card-body">
                                <?= $pptk_count ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            <!-- User Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar User</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <a href="<?= base_url('admin/user-management/tambah') ?>" class="btn btn-primary">
                                        <i class="fas fa-user-plus"></i> Tambah User
                                    </a>
                                    <button type="button" class="btn btn-success" onclick="exportData()">
                                        <i class="fas fa-file-excel"></i> Export
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="refreshTable()">
                                        <i class="fas fa-sync-alt"></i> Refresh
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <?php if (empty($users)) : ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon bg-primary">
                                        <i class="fas fa-users"></i>
                                    </div>
                                    <h2>Belum Ada User</h2>
                                    <p class="lead">Belum ada user yang terdaftar dalam sistem</p>
                                    <a href="<?= base_url('admin/user-management/tambah') ?>" class="btn btn-primary mt-4">
                                        <i class="fas fa-user-plus"></i> Tambah User Pertama
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover mb-0" id="userTable">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th width="10%">Avatar</th>
                                                <th>Username</th>
                                                <th>Nama Lengkap</th>
                                                <th width="12%">Role</th>
                                                <th width="15%">Dibuat</th>
                                                <th width="15%">Diupdate</th>
                                                <th width="15%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($users as $user) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center">
                                                        <div class="avatar avatar-md">
                                                            <?php if ($user->IMG) : ?>
                                                                <img src="<?= base_url('uploads/users/' . $user->IMG) ?>" alt="Avatar" class="rounded-circle img-thumbnail user-avatar" style="width: 40px; height: 40px; margin-top: 2.2px; cursor: pointer;" onclick="showImageModal('<?= base_url('uploads/users/' . $user->IMG) ?>', '<?= htmlspecialchars($user->NAMA, ENT_QUOTES) ?>')" onerror="this.src='<?= base_url('assets/img/default-avatar.png') ?>'">
                                                            <?php else : ?>
                                                                <div class="avatar-initial rounded-circle bg-primary d-flex align-items-center justify-content-center" style="width: 40px; margin-top: 2.2px; height: 40px;">
                                                                    <span class="text-white font-weight-bold"><?= strtoupper(substr($user->NAMA, 0, 1)) ?></span>
                                                                </div>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                    <td>
                                                        <strong><?= $user->USERNAME ?></strong>
                                                        <?php if ($user->ID == 1) : ?>
                                                            <br><small class="badge badge-warning">Super Admin</small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td><?= $user->NAMA ?></td>
                                                    <td class="text-center">
                                                        <?php
                                                        switch ($user->ROLE) {
                                                            case 1:
                                                                echo '<span class="badge badge-danger"><i class="fas fa-user-shield"></i> - Administrator</span>';
                                                                break;
                                                            case 2:
                                                                echo '<span class="badge badge-success"><i class="fas fa-user"></i> - User</span>';
                                                                break;
                                                            case 3:
                                                                echo '<span class="badge badge-info"><i class="fas fa-user-tie"></i> - PPTK</span>';
                                                                break;
                                                            default:
                                                                echo '<span class="badge badge-secondary">Unknown</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                    <td>
                                                        <span><?= date('d M Y', strtotime($user->CREATED_AT)) ?></span><br>
                                                        <small class="text-muted"><?= date('H:i', strtotime($user->CREATED_AT)) ?></small>
                                                    </td>
                                                    <td>
                                                        <?php if ($user->UPDATED_AT) : ?>
                                                            <span><?= date('d M Y', strtotime($user->UPDATED_AT)) ?></span><br>
                                                            <small class="text-muted"><?= date('H:i', strtotime($user->UPDATED_AT)) ?></small>
                                                        <?php else : ?>
                                                            <span class="text-muted">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?= base_url('admin/user-management/edit/' . $user->ID) ?>" class="btn btn-sm btn-info" title="Edit">
                                                                <i class="fas fa-edit"></i>
                                                            </a>
                                                            <button type="button" class="btn btn-sm btn-warning" onclick="resetPassword(<?= $user->ID ?>, '<?= htmlspecialchars($user->USERNAME, ENT_QUOTES) ?>')" title="Reset Password">
                                                                <i class="fas fa-key"></i>
                                                            </button>
                                                            <?php if ($user->ID != 1 && $user->ID != $this->session->userdata('user_id')) : ?>
                                                                <button type="button" class="btn btn-sm btn-danger" onclick="deleteUser(<?= $user->ID ?>, '<?= htmlspecialchars($user->USERNAME, ENT_QUOTES) ?>')" title="Hapus">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Delete Confirmation -->
<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title" id="deleteModalLabel">
                    <i class="fas fa-exclamation-triangle"></i> Konfirmasi Hapus
                </h5>
                <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin menghapus user <strong id="deleteUsername"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Tindakan ini tidak dapat dibatalkan!
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <a href="#" id="deleteConfirmBtn" class="btn btn-danger">
                    <i class="fas fa-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Reset Password -->
<div class="modal fade" id="resetPasswordModal" tabindex="-1" role="dialog" aria-labelledby="resetPasswordModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header bg-warning text-dark">
                <h5 class="modal-title" id="resetPasswordModalLabel">
                    <i class="fas fa-key"></i> Reset Password
                </h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin mereset password user <strong id="resetUsername"></strong>?</p>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i>
                    Password baru akan digenerate secara otomatis dan ditampilkan setelah proses berhasil.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-times"></i> Batal
                </button>
                <a href="#" id="resetPasswordConfirmBtn" class="btn btn-warning">
                    <i class="fas fa-key"></i> Reset Password
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Modal Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Avatar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 400px;">
                <div class="mt-3">
                    <h6 id="modalImageTitle" class="text-dark"></h6>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        // Initialize DataTable with custom settings
        var table = $('#userTable').DataTable({
            "pageLength": 10,
            "lengthMenu": [
                [10, 25, 50, -1],
                [10, 25, 50, "Semua"]
            ],
            "language": {
                "lengthMenu": "Tampilkan _MENU_ data per halaman",
                "zeroRecords": "Data tidak ditemukan",
                "info": "Menampilkan halaman _PAGE_ dari _PAGES_ (_TOTAL_ total data)",
                "infoEmpty": "Tidak ada data",
                "infoFiltered": "(difilter dari _MAX_ total data)",
                "search": "Cari:",
                "searchPlaceholder": "Cari username, nama...",
                "paginate": {
                    "first": "Pertama",
                    "last": "Terakhir",
                    "next": "Selanjutnya",
                    "previous": "Sebelumnya"
                },
                "processing": "Memproses...",
                "emptyTable": "Tidak ada data yang tersedia"
            },
            "columnDefs": [{
                    "orderable": false,
                    "targets": [1, 7]
                }, // Disable sorting for avatar and action columns
                {
                    "className": "text-center",
                    "targets": [0, 1, 4, 7]
                }
            ],
            "order": [
                [5, "desc"]
            ], // Sort by created date descending
            "responsive": true,
            "dom": 'rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>'
        });

        // Custom search functionality
        $('#searchUser').on('keyup', function() {
            table.search(this.value).draw();
            toggleClearFilter();
        });

        // Role filter functionality
        $('#roleFilter').on('change', function() {
            var filterValue = this.value;
            table.column(4).search(filterValue).draw();
            toggleClearFilter();
        });

        // Clear filter functionality
        $('#clearFilter').on('click', function() {
            $('#searchUser').val('');
            $('#roleFilter').val('');
            table.search('').columns().search('').draw();
            $(this).hide();
        });

        function toggleClearFilter() {
            if ($('#searchUser').val() || $('#roleFilter').val()) {
                $('#clearFilter').show();
            } else {
                $('#clearFilter').hide();
            }
        }

        // Auto-hide flash messages
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);
    });

    // Show image modal
    function showImageModal(src, title) {
        $('#modalImage').attr('src', src);
        $('#modalImageTitle').text(title);
        $('#imageModalLabel').text('Preview Avatar - ' + title);
        $('#imageModal').modal('show');
    }

    // Delete user function
    function deleteUser(id, username) {
        $('#deleteUsername').text(username);
        $('#deleteConfirmBtn').attr('href', '<?= base_url('admin/user-management/hapus/') ?>' + id);
        $('#deleteModal').modal('show');
    }

    // Reset password function
    function resetPassword(id, username) {
        $('#resetUsername').text(username);
        $('#resetPasswordConfirmBtn').attr('href', '<?= base_url('admin/user-management/reset-password/') ?>' + id);
        $('#resetPasswordModal').modal('show');
    }

    // Refresh table
    function refreshTable() {
        location.reload();
    }

    // Export data
    function exportData() {
        // Show notification
        if (typeof iziToast !== 'undefined') {
            iziToast.info({
                title: 'Info',
                message: 'Fitur export akan segera tersedia',
                position: 'topRight'
            });
        } else {
            alert('Fitur export akan segera tersedia');
        }
    }

    // Avatar hover effect
    $(document).on('mouseenter', '.user-avatar', function() {
        $(this).css('transform', 'scale(1.1)');
    }).on('mouseleave', '.user-avatar', function() {
        $(this).css('transform', 'scale(1)');
    });

    // Button loading states
    $('#deleteConfirmBtn, #resetPasswordConfirmBtn').on('click', function() {
        var btn = $(this);
        var originalText = btn.html();

        btn.prop('disabled', true);
        btn.html('<i class="fas fa-spinner fa-spin"></i> Memproses...');

        // Simulate processing delay for better UX
        setTimeout(function() {
            window.location.href = btn.attr('href');
        }, 500);
    });
</script>

<style>
    /* Custom styling sesuai tema sebelumnya */
    .empty-state {
        padding: 80px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 120px;
        height: 120px;
        line-height: 120px;
        font-size: 48px;
        border-radius: 50%;
        display: inline-block;
        color: #fff;
        margin-bottom: 30px;
    }

    .card-statistic-1 {
        border: none;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
        margin-bottom: 20px;
        transition: transform 0.3s ease;
    }

    .card-statistic-1:hover {
        transform: translateY(-3px);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.15);
    }

    .card {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .badge {
        font-size: 11px;
        padding: 4px 8px;
    }

    .btn-group .btn {
        margin-right: 2px;
    }

    .btn-group .btn:last-child {
        margin-right: 0;
    }

    .user-avatar {
        transition: transform 0.3s ease;
        border: 2px solid #e3eaef;
    }

    .user-avatar:hover {
        border-color: #007bff;
        cursor: pointer;
    }

    .avatar-initial {
        font-size: 14px;
        font-weight: 600;
    }

    /* Alert styling */
    .alert {
        border-radius: 10px;
        border: none;
        margin-bottom: 20px;
    }

    .alert-success {
        background-color: #d4edda;
        color: #155724;
    }

    .alert-danger {
        background-color: #f8d7da;
        color: #721c24;
    }

    /* Modal customization */
    .modal-content {
        border-radius: 10px;
        border: none;
    }

    .modal-header {
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
    }

    .modal-header.bg-danger {
        background-color: #dc3545 !important;
    }

    .modal-header.bg-warning {
        background-color: #ffc107 !important;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-statistic-1 {
            margin-bottom: 15px;
        }

        .btn-group {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .btn-group .btn {
            margin-right: 0;
            margin-bottom: 2px;
        }

        .table-responsive {
            font-size: 14px;
        }

        .empty-state {
            padding: 40px 15px;
        }

        .empty-state-icon {
            width: 80px;
            height: 80px;
            line-height: 80px;
            font-size: 32px;
            margin-bottom: 20px;
        }
    }

    /* Animation for cards */
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .card {
        animation: fadeInUp 0.5s ease-out;
    }

    /* DataTables custom styling */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin: 0 2px;
        border: 1px solid #dee2e6;
        border-radius: 6px;
        color: #6c757d;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current {
        background: #007bff;
        border-color: #007bff;
        color: white !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background: #f8f9fa;
        border-color: #007bff;
        color: #007bff !important;
    }

    .dataTables_info {
        color: #6c757d;
        font-size: 14px;
    }

    /* Button hover effects */
    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }

    .btn-group .btn-sm:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    /* Loading states */
    .btn:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }

    /* Table styling improvements */
    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
        font-size: 14px;
    }

    .table td {
        vertical-align: middle;
        font-size: 14px;
    }

    .table-striped tbody tr:nth-of-type(odd) {
        background-color: rgba(0, 0, 0, 0.02);
    }
</style>