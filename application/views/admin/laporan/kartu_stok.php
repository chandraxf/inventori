<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Kartu Stok</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Kartu Stok</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Kartu Stok</h4>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Pilih Barang</label>
                                            <select name="barang_id" class="form-control select2" required>
                                                <option value="">-- Pilih Barang --</option>
                                                <?php foreach($barang as $b): ?>
                                                <option value="<?= $b->id ?>" <?= $barang_id == $b->id ? 'selected' : '' ?>>
                                                    <?= $b->kode_nusp ?> - <?= $b->nama_nusp ?>
                                                </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="start_date" class="form-control" 
                                                   value="<?= $start_date ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Akhir</label>
                                            <input type="date" name="end_date" class="form-control" 
                                                   value="<?= $end_date ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>&nbsp;</label>
                                            <button type="submit" class="btn btn-primary btn-block">
                                                <i class="fas fa-search"></i> Tampilkan
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    <?php if(isset($kartu_stok)): ?>
                    <div class="card">
                        <div class="card-header">
                            <h4>Kartu Stok: <?= $selected_barang->nama_nusp ?></h4>
                            <div class="card-header-action">
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered table-striped">
                                    <thead>
                                        <tr>
                                            <th>Tanggal</th>
                                            <th>No. Referensi</th>
                                            <th>Jenis</th>
                                            <th>Keterangan</th>
                                            <th class="text-center">Stok Awal</th>
                                            <th class="text-center">Masuk</th>
                                            <th class="text-center">Keluar</th>
                                            <th class="text-center">Stok Akhir</th>
                                            <th class="text-right">Harga</th>
                                            <th class="text-right">Nilai Saldo</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($kartu_stok as $ks): ?>
                                        <tr>
                                            <td><?= date('d/m/Y', strtotime($ks->tanggal)) ?></td>
                                            <td><?= $ks->nomor_referensi ?></td>
                                            <td>
                                                <?php 
                                                $badge_class = '';
                                                switch($ks->jenis_transaksi) {
                                                    case 'masuk': $badge_class = 'badge-success'; break;
                                                    case 'keluar': $badge_class = 'badge-danger'; break;
                                                    case 'opname_plus': $badge_class = 'badge-info'; break;
                                                    case 'opname_minus': $badge_class = 'badge-warning'; break;
                                                    default: $badge_class = 'badge-secondary';
                                                }
                                                ?>
                                                <span class="badge <?= $badge_class ?>">
                                                    <?= ucfirst(str_replace('_', ' ', $ks->jenis_transaksi)) ?>
                                                </span>
                                            </td>
                                            <td><?= $ks->keterangan ?: '-' ?></td>
                                            <td class="text-center"><?= $ks->stok_awal ?></td>
                                            <td class="text-center"><?= $ks->masuk ?: '-' ?></td>
                                            <td class="text-center"><?= $ks->keluar ?: '-' ?></td>
                                            <td class="text-center"><strong><?= $ks->stok_akhir ?></strong></td>
                                            <td class="text-right">Rp <?= number_format($ks->harga_satuan, 0, ',', '.') ?></td>
                                            <td class="text-right">Rp <?= number_format($ks->nilai_saldo, 0, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    $('.select2').select2();
});
</script>

<style type="text/css" media="print">
    @page { size: landscape; }
    .main-sidebar, .navbar, .section-header-breadcrumb, .card-header-action { display: none !important; }
</style>