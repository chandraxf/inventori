<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('user') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Permintaan Barang</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Quick Actions -->
            <!-- Summary Statistics -->
            <?php if (!empty($permintaan)) : ?>
                <div class="row">
                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-clipboard-list"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Permintaan</h4>
                                </div>
                                <div class="card-body">
                                    <?= count($permintaan) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-warning">
                                <i class="fas fa-hourglass-half"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Menunggu</h4>
                                </div>
                                <div class="card-body">
                                    <?= count(array_filter($permintaan, function ($p) {
                                        return $p->status == 'pending';
                                    })) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Disetujui</h4>
                                </div>
                                <div class="card-body">
                                    <?= count(array_filter($permintaan, function ($p) {
                                        return $p->status == 'approved';
                                    })) ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-danger">
                                <i class="fas fa-times"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Ditolak</h4>
                                </div>
                                <div class="card-body">
                                    <?= count(array_filter($permintaan, function ($p) {
                                        return $p->status == 'rejected';
                                    })) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center">
                                <h4 class="mb-0">Aksi Cepat</h4>
                                <div class="btn-group">
                                    <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary">
                                        <i class="fas fa-plus"></i> Buat Permintaan Baru
                                    </a>
                                    <a href="<?= base_url('user/permintaan-barang/buat') ?>" class="btn btn-success">
                                        <i class="fas fa-shopping-cart"></i>
                                        Keranjang
                                        <?php
                                        $cart_count = count($this->session->userdata('cart') ?: []);
                                        if ($cart_count > 0) :
                                            ?>
                                            <span class="badge badge-light"><?= $cart_count ?></span>
                                        <?php endif; ?>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Permintaan List -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Daftar Permintaan Barang Saya</h4>
                        </div>
                        <div class="card-body">
                            <?php if (empty($permintaan)) : ?>
                                <div class="empty-state">
                                    <div class="empty-state-icon bg-primary">
                                        <i class="fas fa-clipboard-list"></i>
                                    </div>
                                    <h2>Belum Ada Permintaan</h2>
                                    <p class="lead">
                                        Anda belum membuat permintaan barang apapun
                                    </p>
                                    <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary mt-4">
                                        <i class="fas fa-plus"></i> Buat Permintaan Pertama
                                    </a>
                                </div>
                            <?php else : ?>
                                <div class="table-responsive">
                                    <table class="table table-striped table-hover">
                                        <thead>
                                            <tr>
                                                <th width="5%">No.</th>
                                                <th>No. Permintaan</th>
                                                <th>Tanggal</th>
                                                <th>Keperluan</th>
                                                <th>Status</th>
                                                <th width="10%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($permintaan as $p) : ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td>
                                                        <strong class="text-primary"><?= $p->nomor_permintaan ?></strong>
                                                    </td>
                                                    <td>
                                                        <span><?= date('d M Y', strtotime($p->tanggal_permintaan)) ?></span><br>
                                                        <small class="text-muted"><?= date('H:i', strtotime($p->created_at)) ?></small>
                                                    </td>
                                                    <td>
                                                        <div><?= $p->keperluan ?></div>
                                                        <?php if ($p->keterangan) : ?>
                                                            <small class="text-muted"><?= $p->keterangan ?></small>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td>
                                                        <?php
                                                        $badge_class = '';
                                                        $badge_icon = '';
                                                        $status_text = '';

                                                        switch ($p->status) {
                                                            case 'pending':
                                                                $badge_class = 'badge-warning';
                                                                $badge_icon = 'fas fa-hourglass-half';
                                                                $status_text = 'Menunggu';
                                                                break;
                                                            case 'approved':
                                                                $badge_class = 'badge-success';
                                                                $badge_icon = 'fas fa-check';
                                                                $status_text = 'Disetujui';
                                                                break;
                                                            case 'rejected':
                                                                $badge_class = 'badge-danger';
                                                                $badge_icon = 'fas fa-times';
                                                                $status_text = 'Ditolak';
                                                                break;
                                                            case 'partial':
                                                                $badge_class = 'badge-info';
                                                                $badge_icon = 'fas fa-adjust';
                                                                $status_text = 'Sebagian';
                                                                break;
                                                            case 'completed':
                                                                $badge_class = 'badge-primary';
                                                                $badge_icon = 'fas fa-check-double';
                                                                $status_text = 'Selesai';
                                                                break;
                                                            default:
                                                                $badge_class = 'badge-secondary';
                                                                $badge_icon = 'fas fa-question';
                                                                $status_text = 'Unknown';
                                                        }
                                                        ?>
                                                        <span class="badge <?= $badge_class ?>">
                                                            <i class="<?= $badge_icon ?>"></i> <?= $status_text ?>
                                                        </span>
                                                    </td>
                                                    <td>
                                                        <div class="btn-group" role="group">
                                                            <a href="<?= base_url('user/permintaan-barang/detail/' . $p->id) ?>" class="btn btn-sm btn-info" title="Detail">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                            <?php if ($p->status == 'pending') : ?>
                                                                <button type="button" class="btn btn-sm btn-danger btn-cancel" data-id="<?= $p->id ?>" data-nomor="<?= $p->nomor_permintaan ?>" title="Batalkan">
                                                                    <i class="fas fa-times"></i>
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

<!-- Modal Konfirmasi Batalkan -->
<div class="modal fade" id="cancelModal" tabindex="-1" role="dialog" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cancelModalLabel">Konfirmasi Pembatalan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>Apakah Anda yakin ingin membatalkan permintaan:</p>
                <p><strong id="cancel-nomor"></strong>?</p>
                <div class="alert alert-warning">
                    <i class="fas fa-exclamation-triangle"></i>
                    Permintaan yang dibatalkan tidak dapat dikembalikan lagi.
                </div>
                <div class="form-group">
                    <label for="cancel-reason">Alasan pembatalan (opsional):</label>
                    <textarea id="cancel-reason" class="form-control" rows="3" placeholder="Masukkan alasan pembatalan..."></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">
                    <i class="fas fa-arrow-left"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirm-cancel">
                    <i class="fas fa-times"></i> Ya, Batalkan
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Flash Messages -->
<?php if ($this->session->flashdata('success')) : ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
        <strong>Berhasil!</strong> <?= $this->session->flashdata('success') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<?php if ($this->session->flashdata('error')) : ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">
        <strong>Error!</strong> <?= $this->session->flashdata('error') ?>
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
<?php endif; ?>

<script>
    $(document).ready(function() {
        // Auto-hide flash messages
        setTimeout(function() {
            $('.alert').fadeOut('slow');
        }, 5000);

        // Cancel button click
        $('.btn-cancel').on('click', function() {
            var id = $(this).data('id');
            var nomor = $(this).data('nomor');

            $('#cancel-nomor').text(nomor);
            $('#cancelModal').data('id', id).modal('show');
        });

        // Confirm cancel
        $('#confirm-cancel').on('click', function() {
            var id = $('#cancelModal').data('id');
            var reason = $('#cancel-reason').val();
            var btn = $(this);

            // Disable button and show loading
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Membatalkan...');

            // For demo purposes - you would implement the actual cancel functionality
            // This would typically be an AJAX call to cancel the request
            setTimeout(function() {
                $('#cancelModal').modal('hide');

                // Show success message
                var successAlert = '<div class="alert alert-info alert-dismissible fade show" role="alert" style="position: fixed; top: 80px; right: 20px; z-index: 9999; min-width: 300px;">' +
                    '<strong>Info!</strong> Fitur pembatalan akan segera diimplementasikan.' +
                    '<button type="button" class="close" data-dismiss="alert" aria-label="Close">' +
                    '<span aria-hidden="true">&times;</span></button></div>';
                $('body').append(successAlert);

                // Auto-hide after 3 seconds
                setTimeout(function() {
                    $('.alert').fadeOut('slow');
                }, 3000);

                // Reset button
                btn.prop('disabled', false);
                btn.html('<i class="fas fa-times"></i> Ya, Batalkan');
            }, 1500);
        });

        // Reset modal when closed
        $('#cancelModal').on('hidden.bs.modal', function() {
            $('#cancel-reason').val('');
            $('#confirm-cancel').prop('disabled', false);
            $('#confirm-cancel').html('<i class="fas fa-times"></i> Ya, Batalkan');
        });
    });
</script>

<style>
    /* Custom styles for better UX */
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
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 123, 255, 0.05);
    }

    .badge {
        font-size: 11px;
        padding: 5px 8px;
    }

    .btn-group {
        display: flex;
        gap: 2px;
    }

    .btn-sm {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }

    /* Status badge colors */
    .badge-warning {
        background-color: #ffc107;
        color: #212529;
    }

    .badge-success {
        background-color: #28a745;
    }

    .badge-danger {
        background-color: #dc3545;
    }

    .badge-info {
        background-color: #17a2b8;
    }

    .badge-primary {
        background-color: #007bff;
    }

    .badge-secondary {
        background-color: #6c757d;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-statistic-1 {
            margin-bottom: 15px;
        }

        .btn-group {
            flex-direction: column;
            gap: 5px;
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

    /* Animation for better UX */
    .card {
        transition: transform 0.2s ease-in-out;
    }

    .card:hover {
        transform: translateY(-2px);
    }

    .btn {
        transition: all 0.3s ease;
    }

    .btn:hover {
        transform: translateY(-1px);
    }
</style>