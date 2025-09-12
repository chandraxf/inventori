<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <!-- Welcome Section -->
        <div class="row">
            <div class="col-12">
                <div class="hero bg-primary text-white">
                    <div class="hero-inner">
                        <h2>Selamat Datang, <?= $user['nama'] ?>!</h2>
                        <p class="lead">Sistem Permintaan Barang - User Panel</p>
                        <div class="mt-4">
                            <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-outline-white btn-lg btn-icon icon-left">
                                <i class="fas fa-shopping-cart"></i> Buat Permintaan Baru
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="row mt-4">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="fas fa-clipboard-list"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Permintaan</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($total_permintaan) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="fas fa-hourglass-half"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Menunggu</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($permintaan_pending) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Disetujui</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($permintaan_approved) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-info">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Keranjang</h4>
                        </div>
                        <div class="card-body">
                            <?= $cart_count ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Recent Requests -->
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Permintaan Terakhir</h4>
                        <div class="card-header-action">
                            <a href="<?= base_url('user/permintaan-barang') ?>" class="btn btn-primary">
                                Lihat Semua
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <?php if (empty($recent_permintaan)) : ?>
                            <div class="empty-state" style="padding: 20px;">
                                <div class="empty-state-icon bg-primary" style="font-size: 3rem;">
                                    <i class="fas fa-clipboard-list"></i>
                                </div>
                                <h5>Belum Ada Permintaan</h5>
                                <p>Anda belum membuat permintaan barang.</p>
                                <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary mt-3">
                                    Buat Permintaan
                                </a>
                            </div>
                        <?php else : ?>
                            <div class="table-responsive">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No. Permintaan</th>
                                            <th>Tanggal</th>
                                            <th>Keperluan</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($recent_permintaan as $p) : ?>
                                            <tr>
                                                <td>
                                                    <a href="<?= base_url('user/permintaan-barang/detail/' . $p->id) ?>">
                                                        <?= $p->nomor_permintaan ?>
                                                    </a>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($p->tanggal_permintaan)) ?></td>
                                                <td><?= $p->keperluan ?></td>
                                                <td>
                                                    <?php
                                                    $badge_class = '';
                                                    $badge_icon = '';
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
                                                    <a href="<?= base_url('user/permintaan-barang/detail/' . $p->id) ?>" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
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

            <!-- Quick Actions & Info -->
            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <!-- Quick Actions -->
                <div class="card">
                    <div class="card-header">
                        <h4>Aksi Cepat</h4>
                    </div>
                    <div class="card-body">
                        <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary btn-lg btn-block btn-icon icon-left">
                            <i class="fas fa-boxes"></i> Lihat Katalog Barang
                        </a>
                        <a href="<?= base_url('user/permintaan-barang/buat') ?>" class="btn btn-success btn-lg btn-block btn-icon icon-left">
                            <i class="fas fa-shopping-cart"></i>
                            Keranjang Permintaan
                            <?php if ($cart_count > 0) : ?>
                                <span class="badge badge-light ml-2"><?= $cart_count ?></span>
                            <?php endif; ?>
                        </a>
                        <a href="<?= base_url('user/permintaan-barang') ?>" class="btn btn-info btn-lg btn-block btn-icon icon-left">
                            <i class="fas fa-history"></i> Riwayat Permintaan
                        </a>
                    </div>
                </div>

                <!-- Info Card -->
                <div class="card">
                    <div class="card-header">
                        <h4>Informasi</h4>
                    </div>
                    <div class="card-body">
                        <div class="list-group">
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Cara Permintaan</h6>
                                </div>
                                <small class="text-muted">
                                    1. Pilih barang dari katalog<br>
                                    2. Masukkan jumlah & tambah ke keranjang<br>
                                    3. Review & ajukan permintaan<br>
                                    4. Tunggu persetujuan admin
                                </small>
                            </div>
                            <div class="list-group-item flex-column align-items-start">
                                <div class="d-flex w-100 justify-content-between">
                                    <h6 class="mb-1">Status Permintaan</h6>
                                </div>
                                <small class="text-muted">
                                    <span class="badge badge-warning">Menunggu</span> Belum diproses<br>
                                    <span class="badge badge-success">Disetujui</span> Sudah disetujui<br>
                                    <span class="badge badge-info">Sebagian</span> Disetujui sebagian<br>
                                    <span class="badge badge-primary">Selesai</span> Barang sudah diterima
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>