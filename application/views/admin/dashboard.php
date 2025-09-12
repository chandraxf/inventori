<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Dashboard</h1>
        </div>

        <div class="row">
            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-primary">
                        <i class="far fa-box"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Barang</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($total_barang) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-danger">
                        <i class="far fa-building"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Unit</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($total_unit) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-warning">
                        <i class="far fa-file"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Total Stok</h4>
                        </div>
                        <div class="card-body">
                            <?= number_format($total_qty) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-md-6 col-sm-6 col-12">
                <div class="card card-statistic-1">
                    <div class="card-icon bg-success">
                        <i class="fas fa-dollar-sign"></i>
                    </div>
                    <div class="card-wrap">
                        <div class="card-header">
                            <h4>Nilai Persediaan</h4>
                        </div>
                        <div class="card-body">
                            Rp <?= number_format($total_nilai, 0, ',', '.') ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-lg-8 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Transaksi Terakhir</h4>
                        <div class="card-header-action">
                            <a href="<?= base_url('admin/barang-masuk') ?>" class="btn btn-primary">Lihat Semua</a>
                        </div>
                    </div>
                    <div class="card-body">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" id="masuk-tab" data-toggle="tab" href="#masuk" role="tab">
                                    Barang Masuk
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" id="keluar-tab" data-toggle="tab" href="#keluar" role="tab">
                                    Barang Keluar
                                </a>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="masuk" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_masuk as $rm) : ?>
                                                <tr>
                                                    <td><?= $rm->nomor_transaksi ?></td>
                                                    <td><?= date('d/m/Y', strtotime($rm->tanggal_masuk)) ?></td>
                                                    <td><?= ucfirst(str_replace('_', ' ', $rm->jenis_masuk)) ?></td>
                                                    <td>Rp <?= number_format($rm->total_nilai, 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php if ($rm->status == 'posted') : ?>
                                                            <span class="badge badge-success">Posted</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-warning">Draft</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="keluar" role="tabpanel">
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>No. Transaksi</th>
                                                <th>Tanggal</th>
                                                <th>Jenis</th>
                                                <th>Total</th>
                                                <th>Status</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($recent_keluar as $rk) : ?>
                                                <tr>
                                                    <td><?= $rk->nomor_transaksi ?></td>
                                                    <td><?= date('d/m/Y', strtotime($rk->tanggal_keluar)) ?></td>
                                                    <td><?= ucfirst(str_replace('_', ' ', $rk->jenis_keluar)) ?></td>
                                                    <td>Rp <?= number_format($rk->total_nilai, 0, ',', '.') ?></td>
                                                    <td>
                                                        <?php if ($rk->status == 'posted') : ?>
                                                            <span class="badge badge-success">Posted</span>
                                                        <?php else : ?>
                                                            <span class="badge badge-warning">Draft</span>
                                                        <?php endif; ?>
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-lg-4 col-md-12 col-12 col-sm-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Barang Perlu Restock</h4>
                    </div>
                    <div class="card-body">
                        <?php if (empty($need_restock)) : ?>
                            <p class="text-muted">Semua barang stoknya aman</p>
                        <?php else : ?>
                            <ul class="list-unstyled list-unstyled-border">
                                <?php foreach ($need_restock as $nr) : ?>
                                    <li class="media">
                                        <div class="media-body">
                                            <div class="media-title"><?= $nr->nama_nusp ?></div>
                                            <div class="text-muted text-small">
                                                Stok: <span class="text-danger"><?= $nr->stok_saat_ini ?></span> /
                                                Min: <?= $nr->stok_minimum ?> <?= $nr->satuan ?>
                                            </div>
                                        </div>
                                        <div class="media-right">
                                            <span class="badge badge-danger"><?= $nr->status_stok ?></span>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
</div>