<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Stok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Stok</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Laporan Persediaan Barang</h4>
                            <div class="card-header-action">
                                <div class="btn-group">
                                    <button class="btn btn-primary" onclick="window.print()">
                                        <i class="fas fa-print"></i> Print
                                    </button>
                                    <a href="<?= base_url('admin/laporan/stok-excel') ?>" class="btn btn-success">
                                        <i class="fas fa-file-excel"></i> Export Excel
                                    </a>
                                    <a href="<?= base_url('admin/laporan/stok-pdf') ?>" class="btn btn-danger">
                                        <i class="fas fa-file-pdf"></i> Export PDF
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <form method="GET" action="">
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text">Periode</span>
                                            </div>
                                            <input type="month" name="periode" class="form-control" 
                                                   value="<?= $periode ?? date('Y-m') ?>">
                                            <div class="input-group-append">
                                                <button class="btn btn-primary" type="submit">Filter</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            
                            <div class="table-responsive">
                                <table class="table table-bordered table-sm" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th rowspan="2" class="text-center align-middle">#</th>
                                            <th rowspan="2" class="align-middle">Kode Rek</th>
                                            <th rowspan="2" class="align-middle">Nama Barang</th>
                                            <th rowspan="2" class="align-middle">Kode NUSP</th>
                                            <th rowspan="2" class="align-middle">Nama NUSP</th>
                                            <th rowspan="2" class="text-center align-middle">Satuan</th>
                                            <th colspan="3" class="text-center bg-light">Stok Awal</th>
                                            <th colspan="3" class="text-center bg-info text-white">Barang Masuk</th>
                                            <th colspan="3" class="text-center bg-warning">Barang Keluar</th>
                                            <th colspan="3" class="text-center bg-success text-white">Stok Akhir</th>
                                        </tr>
                                        <tr>
                                            <th class="text-center bg-light">Qty</th>
                                            <th class="text-center bg-light">Harga</th>
                                            <th class="text-center bg-light">Total</th>
                                            <th class="text-center bg-info text-white">Qty</th>
                                            <th class="text-center bg-info text-white">Harga</th>
                                            <th class="text-center bg-info text-white">Total</th>
                                            <th class="text-center bg-warning">Qty</th>
                                            <th class="text-center bg-warning">Harga</th>
                                            <th class="text-center bg-warning">Total</th>
                                            <th class="text-center bg-success text-white">Qty</th>
                                            <th class="text-center bg-success text-white">Harga</th>
                                            <th class="text-center bg-success text-white">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php 
                                        $no = 1; 
                                        $total_awal = 0;
                                        $total_masuk = 0;
                                        $total_keluar = 0;
                                        $total_akhir = 0;
                                        foreach($laporan_stok as $row): 
                                            $total_awal += $row->total_awal;
                                            $total_masuk += $row->total_masuk;
                                            $total_keluar += $row->total_keluar;
                                            $total_akhir += $row->total_akhir;
                                        ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $row->kode_rek_108 ?></td>
                                            <td><?= $row->nama_barang_108 ?></td>
                                            <td><?= $row->kode_nusp ?></td>
                                            <td><?= $row->nama_nusp ?></td>
                                            <td class="text-center"><?= $row->satuan ?></td>
                                            <td class="text-center"><?= $row->stok_awal ?></td>
                                            <td class="text-right"><?= number_format($row->harga_awal, 0, ',', '.') ?></td>
                                            <td class="text-right"><?= number_format($row->total_awal, 0, ',', '.') ?></td>
                                            <td class="text-center"><?= $row->jumlah_masuk ?></td>
                                            <td class="text-right"><?= number_format($row->harga_masuk, 0, ',', '.') ?></td>
                                            <td class="text-right"><?= number_format($row->total_masuk, 0, ',', '.') ?></td>
                                            <td class="text-center"><?= $row->jumlah_keluar ?></td>
                                            <td class="text-right"><?= number_format($row->harga_keluar, 0, ',', '.') ?></td>
                                            <td class="text-right"><?= number_format($row->total_keluar, 0, ',', '.') ?></td>
                                            <td class="text-center"><strong><?= $row->jumlah_akhir ?></strong></td>
                                            <td class="text-right"><?= number_format($row->harga_akhir, 0, ',', '.') ?></td>
                                            <td class="text-right"><strong><?= number_format($row->total_akhir, 0, ',', '.') ?></strong></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="bg-light">
                                            <th colspan="8" class="text-right">TOTAL</th>
                                            <th class="text-right"><?= number_format($total_awal, 0, ',', '.') ?></th>
                                            <th colspan="2"></th>
                                            <th class="text-right"><?= number_format($total_masuk, 0, ',', '.') ?></th>
                                            <th colspan="2"></th>
                                            <th class="text-right"><?= number_format($total_keluar, 0, ',', '.') ?></th>
                                            <th colspan="2"></th>
                                            <th class="text-right"><strong><?= number_format($total_akhir, 0, ',', '.') ?></strong></th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<style type="text/css" media="print">
    @page { size: landscape; }
    .main-sidebar, .navbar, .section-header-breadcrumb, .card-header-action, .btn { display: none !important; }
</style>