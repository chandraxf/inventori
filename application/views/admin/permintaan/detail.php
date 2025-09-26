<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Permintaan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/permintaan') ?>">Permintaan</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Detail Permintaan Barang</h2>
                                <div class="invoice-number">
                                    No. Permintaan: <strong><?= $permintaan->nomor_permintaan ?></strong>
                                    <?php
                                    $badge_class = '';
                                    switch ($permintaan->status) {
                                        case 'pending':
                                            $badge_class = 'badge-warning';
                                            $status_text = 'Menunggu Persetujuan';
                                            break;
                                        case 'approved':
                                            $badge_class = 'badge-success';
                                            $status_text = 'Disetujui';
                                            break;
                                        case 'rejected':
                                            $badge_class = 'badge-danger';
                                            $status_text = 'Ditolak';
                                            break;
                                        case 'partial':
                                            $badge_class = 'badge-info';
                                            $status_text = 'Disetujui Sebagian';
                                            break;
                                        default:
                                            $badge_class = 'badge-secondary';
                                            $status_text = ucfirst($permintaan->status);
                                    }
                                    ?>
                                    <span class="badge <?= $badge_class ?> ml-2"><?= $status_text ?></span>
                                </div>
                            </div>
                            <hr>

                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Informasi Permintaan:</strong><br>
                                        Tanggal: <?= date('d F Y', strtotime($permintaan->tanggal_permintaan)) ?><br>
                                        Pemohon: <?= $permintaan->nama_user ?><br>
                                        Keperluan: <?= $permintaan->keperluan ?><br>
                                        <?php if ($permintaan->keterangan) : ?>
                                            Keterangan: <?= $permintaan->keterangan ?>
                                        <?php endif; ?>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <?php if ($permintaan->approved_by) : ?>
                                        <address>
                                            <strong>Diproses Oleh:</strong><br>
                                            <?= $permintaan->approved_by_name ?? '-' ?><br>
                                            <?= $permintaan->approved_at ? date('d/m/Y H:i', strtotime($permintaan->approved_at)) : '-' ?>
                                        </address>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="section-title">Detail Barang</div>
                            <div class="table-responsive">
                                <table class="table table-striped table-hover table-md">
                                    <thead>
                                        <tr>
                                            <th data-width="40">#</th>
                                            <th>Kode Barang</th>
                                            <th>Nama Barang</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-center">Jumlah Diminta</th>
                                            <th class="text-center">Jumlah Disetujui</th>
                                            <th class="text-center">Status</th>
                                            <th>Keterangan</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($detail as $d) : ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $d->kode_nusp ?></td>
                                                <td><?= $d->nama_nusp ?></td>
                                                <td class="text-center"><?= $d->satuan ?></td>
                                                <td class="text-center">
                                                    <strong><?= $d->jumlah_diminta ?></strong>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($permintaan->status == 'pending') : ?>
                                                        <span class="text-muted">-</span>
                                                    <?php else : ?>
                                                        <?php if ($d->jumlah_disetujui == $d->jumlah_diminta) : ?>
                                                            <span class="text-success"><strong><?= $d->jumlah_disetujui ?></strong></span>
                                                        <?php elseif ($d->jumlah_disetujui > 0) : ?>
                                                            <span class="text-warning"><strong><?= $d->jumlah_disetujui ?></strong></span>
                                                        <?php else : ?>
                                                            <span class="text-danger"><strong>0</strong></span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <?php if ($permintaan->status == 'pending') : ?>
                                                        <span class="badge badge-warning">Menunggu</span>
                                                    <?php else : ?>
                                                        <?php if ($d->jumlah_disetujui == $d->jumlah_diminta) : ?>
                                                            <span class="badge badge-success">Disetujui</span>
                                                        <?php elseif ($d->jumlah_disetujui > 0) : ?>
                                                            <span class="badge badge-info">Sebagian</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-danger">Ditolak</span>
                                                        <?php endif; ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <small><?= $d->keterangan ?: '-' ?></small>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="text-md-right">
                    <a href="<?= base_url('admin/permintaan') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php if ($permintaan->status == 'pending') : ?>
                        <a href="<?= base_url('admin/permintaan/approve/' . $permintaan->id) ?>" class="btn btn-success">
                            <i class="fas fa-check"></i> Proses Permintaan
                        </a>
                    <?php endif; ?>
                    <button class="btn btn-primary btn-icon icon-left" onclick="window.print()">
                        <i class="fas fa-print"></i> Print
                    </button>
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css" media="print">
    @page {
        size: portrait;
    }

    .main-sidebar,
    .navbar,
    .section-header-breadcrumb,
    .btn {
        display: none !important;
    }
</style>