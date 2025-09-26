<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item"><a href="<?= base_url('user') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('user/permintaan-barang') ?>">Permintaan Barang</a></div>
                <div class="breadcrumb-item active">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Header Info -->
            <div class="row">
                <div class="col-12">
                    <div class="card card-primary">
                        <div class="card-header">
                            <h4>
                                <i class="fas fa-file-alt"></i>
                                Detail Permintaan: <?= $permintaan->nomor_permintaan ?>
                            </h4>
                            <div class="card-header-action">
                                <a href="<?= base_url('user/permintaan-barang') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Kembali
                                </a>
                                <?php if ($permintaan->status == 'approved') : ?>
                                    <button class="btn btn-success" onclick="window.print()">
                                        <i class="fas fa-print"></i> Cetak
                                    </button>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Nomor Permintaan:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext text-primary font-weight-bold" value="<?= $permintaan->nomor_permintaan ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Tanggal Permintaan:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" value="<?= date('d F Y', strtotime($permintaan->tanggal_permintaan)) ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Pemohon:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" value="<?= $permintaan->nama_user ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Keperluan:</label>
                                        <div class="col-sm-8">
                                            <textarea class="form-control-plaintext" rows="2" readonly><?= $permintaan->keperluan ?></textarea>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6">
                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Status:</label>
                                        <div class="col-sm-8">
                                            <?php
                                            $badge_class = '';
                                            $badge_icon = '';
                                            $status_text = '';

                                            switch ($permintaan->status) {
                                                case 'pending':
                                                    $badge_class = 'badge-warning';
                                                    $badge_icon = 'fas fa-hourglass-half';
                                                    $status_text = 'Menunggu Persetujuan';
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
                                                    $status_text = 'Disetujui Sebagian';
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
                                            <span class="badge <?= $badge_class ?> badge-lg">
                                                <i class="<?= $badge_icon ?>"></i> <?= $status_text ?>
                                            </span>
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label class="col-sm-4 col-form-label font-weight-bold">Dibuat:</label>
                                        <div class="col-sm-8">
                                            <input type="text" class="form-control-plaintext" value="<?= date('d F Y H:i', strtotime($permintaan->created_at)) ?>" readonly>
                                        </div>
                                    </div>

                                    <?php if ($permintaan->keterangan) : ?>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Keterangan:</label>
                                            <div class="col-sm-8">
                                                <textarea class="form-control-plaintext" rows="2" readonly><?= $permintaan->keterangan ?></textarea>
                                            </div>
                                        </div>
                                    <?php endif; ?>

                                    <?php if ($permintaan->status != 'pending' && isset($permintaan->keterangan_admin) && $permintaan->keterangan_admin) : ?>
                                        <div class="form-group row">
                                            <label class="col-sm-4 col-form-label font-weight-bold">Catatan Admin:</label>
                                            <div class="col-sm-8">
                                                <div class="alert alert-info">
                                                    <i class="fas fa-info-circle"></i>
                                                    <?= $permintaan->keterangan_admin ?>
                                                </div>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Barang -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4><i class="fas fa-boxes"></i> Detail Barang yang Diminta</h4>
                            <div class="card-header-action">
                                <span class="badge badge-primary">
                                    Total: <?= count($detail) ?> Item
                                </span>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-striped table-hover mb-0">
                                    <thead class="thead-light">
                                        <tr>
                                            <th width="5%">No.</th>
                                            <th width="15%">Gambar</th>
                                            <th width="15%">Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th width="10%">Satuan</th>
                                            <th width="12%">Jml Diminta</th>
                                            <th width="12%">Jml Disetujui</th>
                                            <th width="10%">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (empty($detail)) : ?>
                                            <tr>
                                                <td colspan="8" class="text-center py-4">
                                                    <div class="empty-state">
                                                        <i class="fas fa-inbox fa-3x text-muted mb-3"></i>
                                                        <h5>Tidak ada detail barang</h5>
                                                    </div>
                                                </td>
                                            </tr>
                                        <?php else : ?>
                                            <?php $no = 1;
                                            foreach ($detail as $item) : ?>
                                                <tr>
                                                    <td class="text-center"><?= $no++ ?></td>
                                                    <td class="text-center">
                                                        <img src="<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>" class="img-thumbnail item-image" width="60" style="cursor: pointer;" onclick="showImageModal('<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>', '<?= htmlspecialchars($item->nama_nusp, ENT_QUOTES) ?>')">
                                                    </td>
                                                    <td>
                                                        <code class="text-dark"><?= $item->kode_nusp ?></code>
                                                    </td>
                                                    <td>
                                                        <strong><?= $item->nama_nusp ?></strong>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-light"><?= $item->satuan ?></span>
                                                    </td>
                                                    <td class="text-center">
                                                        <span class="badge badge-info badge-lg">
                                                            <?= number_format($item->jumlah_diminta) ?>
                                                        </span>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php if ($item->jumlah_disetujui > 0) : ?>
                                                            <span class="badge badge-success badge-lg">
                                                                <?= number_format($item->jumlah_disetujui) ?>
                                                            </span>
                                                        <?php else : ?>
                                                            <span class="badge badge-secondary">-</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <?php
                                                        if ($permintaan->status == 'pending') {
                                                            echo '<span class="badge badge-warning"><i class="fas fa-hourglass-half"></i> Pending</span>';
                                                        } elseif ($item->jumlah_disetujui == 0) {
                                                            echo '<span class="badge badge-danger"><i class="fas fa-times"></i> Ditolak</span>';
                                                        } elseif ($item->jumlah_disetujui < $item->jumlah_diminta) {
                                                            echo '<span class="badge badge-warning"><i class="fas fa-adjust"></i> Sebagian</span>';
                                                        } else {
                                                            echo '<span class="badge badge-success"><i class="fas fa-check"></i> Disetujui</span>';
                                                        }
                                                        ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Summary Info -->
            <?php if (!empty($detail)) : ?>
                <div class="row">
                    <div class="col-lg-4">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-primary">
                                <i class="fas fa-list"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Item</h4>
                                </div>
                                <div class="card-body">
                                    <?= count($detail) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-info">
                                <i class="fas fa-shopping-cart"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Diminta</h4>
                                </div>
                                <div class="card-body">
                                    <?= number_format(array_sum(array_column($detail, 'jumlah_diminta'))) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="card card-statistic-1">
                            <div class="card-icon bg-success">
                                <i class="fas fa-check"></i>
                            </div>
                            <div class="card-wrap">
                                <div class="card-header">
                                    <h4>Total Disetujui</h4>
                                </div>
                                <div class="card-body">
                                    <?= number_format(array_sum(array_column($detail, 'jumlah_disetujui'))) ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Timeline/History (if available) -->
            <?php if ($permintaan->status != 'pending') : ?>
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4><i class="fas fa-history"></i> Riwayat Permintaan</h4>
                            </div>
                            <div class="card-body">
                                <div class="activities">
                                    <div class="activity">
                                        <div class="activity-icon bg-primary text-white shadow-primary">
                                            <i class="fas fa-plus"></i>
                                        </div>
                                        <div class="activity-detail">
                                            <div class="mb-2">
                                                <span class="text-job"><?= date('d F Y', strtotime($permintaan->created_at)) ?></span>
                                                <span class="bullet"></span>
                                                <span class="text-job"><?= date('H:i', strtotime($permintaan->created_at)) ?></span>
                                            </div>
                                            <p>Permintaan dibuat oleh <strong><?= $permintaan->nama_user ?></strong></p>
                                        </div>
                                    </div>

                                    <?php if ($permintaan->status == 'approved') : ?>
                                        <div class="activity">
                                            <div class="activity-icon bg-success text-white shadow-success">
                                                <i class="fas fa-check"></i>
                                            </div>
                                            <div class="activity-detail">
                                                <div class="mb-2">
                                                    <span class="text-job">Disetujui Admin</span>
                                                </div>
                                                <p>Permintaan telah disetujui dan siap untuk diproses lebih lanjut</p>
                                            </div>
                                        </div>
                                    <?php elseif ($permintaan->status == 'rejected') : ?>
                                        <div class="activity">
                                            <div class="activity-icon bg-danger text-white shadow-danger">
                                                <i class="fas fa-times"></i>
                                            </div>
                                            <div class="activity-detail">
                                                <div class="mb-2">
                                                    <span class="text-job">Ditolak Admin</span>
                                                </div>
                                                <p>Permintaan ditolak oleh admin</p>
                                            </div>
                                        </div>
                                    <?php elseif ($permintaan->status == 'partial') : ?>
                                        <div class="activity">
                                            <div class="activity-icon bg-warning text-white shadow-warning">
                                                <i class="fas fa-adjust"></i>
                                            </div>
                                            <div class="activity-detail">
                                                <div class="mb-2">
                                                    <span class="text-job">Disetujui Sebagian</span>
                                                </div>
                                                <p>Beberapa item disetujui, beberapa item ditolak</p>
                                            </div>
                                        </div>
                                    <?php elseif ($permintaan->status == 'completed') : ?>
                                        <div class="activity">
                                            <div class="activity-icon bg-primary text-white shadow-primary">
                                                <i class="fas fa-check-double"></i>
                                            </div>
                                            <div class="activity-detail">
                                                <div class="mb-2">
                                                    <span class="text-job">Selesai</span>
                                                </div>
                                                <p>Permintaan telah selesai diproses dan barang telah diterima</p>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </section>
</div>

<!-- Modal Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Gambar Barang</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid" style="max-height: 500px;">
                <div class="mt-3">
                    <h6 id="modalImageTitle" class="text-dark"></h6>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
    // Show image in modal
    function showImageModal(src, title) {
        $('#modalImage').attr('src', src);
        $('#modalImageTitle').text(title);
        $('#imageModalLabel').text('Preview: ' + title);
        $('#imageModal').modal('show');
    }

    $(document).ready(function() {
        // Image hover effect
        $('.item-image').hover(
            function() {
                $(this).css('transform', 'scale(1.1)');
            },
            function() {
                $(this).css('transform', 'scale(1)');
            }
        );

        // Print functionality
        window.print = function() {
            window.open('<?= base_url('user/permintaan-barang/print/' . $permintaan->id) ?>', '_blank');
        }
    });
</script>

<style>
    /* Custom styles for detail page */
    .card-primary .card-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-lg {
        font-size: 14px;
        padding: 8px 12px;
    }

    .form-control-plaintext {
        border: none;
        background: transparent;
        font-weight: 500;
    }

    .item-image {
        transition: transform 0.3s ease;
        border-radius: 8px;
    }

    .item-image:hover {
        transform: scale(1.1);
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
    }

    .card-statistic-1 {
        border: none;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        border-radius: 10px;
    }

    .table th {
        border-top: none;
        font-weight: 600;
        background-color: #f8f9fa;
    }

    .activities .activity {
        padding: 20px 0;
        border-left: 2px solid #e9ecef;
        margin-left: 20px;
        position: relative;
    }

    .activities .activity:last-child {
        border-left: none;
    }

    .activity-icon {
        position: absolute;
        left: -25px;
        top: 20px;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .activity-detail {
        margin-left: 40px;
    }

    .text-job {
        font-size: 14px;
        font-weight: 600;
    }

    .empty-state {
        padding: 40px;
        text-align: center;
    }

    /* Print styles */
    @media print {

        .section-header,
        .card-header-action,
        .btn {
            display: none !important;
        }

        .card {
            box-shadow: none !important;
            border: 1px solid #ddd !important;
        }
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .card-statistic-1 {
            margin-bottom: 15px;
        }

        .form-group.row {
            margin-bottom: 0.5rem;
        }

        .col-sm-4 {
            padding-bottom: 0;
        }

        .table-responsive {
            font-size: 12px;
        }

        .badge-lg {
            font-size: 12px;
            padding: 6px 8px;
        }
    }

    /* Animation effects */
    .card {
        animation: fadeInUp 0.5s ease-out;
    }

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

    .badge {
        transition: all 0.3s ease;
    }

    .badge:hover {
        transform: translateY(-1px);
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }
</style>