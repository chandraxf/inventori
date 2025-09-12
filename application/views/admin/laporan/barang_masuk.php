<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Laporan Barang Masuk</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Laporan</div>
                <div class="breadcrumb-item">Barang Masuk</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter Periode</h4>
                            <div class="card-header-action">
                                <button class="btn btn-primary" onclick="window.print()">
                                    <i class="fas fa-print"></i> Print
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Mulai</label>
                                            <input type="date" name="start_date" class="form-control" value="<?= $start_date ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Tanggal Akhir</label>
                                            <input type="date" name="end_date" class="form-control" value="<?= $end_date ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
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

                    <div class="card">
                        <div class="card-header">
                            <h4>Data Barang Masuk</h4>
                        </div>
                        <div class="card-body">
                            <div class="table-responsive">
                                <table class="table table-bordered" id="table-laporan">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th>No. Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Jenis</th>
                                            <th>Supplier</th>
                                            <th>No. Faktur</th>
                                            <th>Total Nilai</th>
                                            <th>User Input</th>
                                            <th>Detail</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $grand_total = 0;
                                        foreach ($barang_masuk as $bm) :
                                            $grand_total += $bm->total_nilai;
                                            ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $bm->nomor_transaksi ?></td>
                                                <td><?= date('d/m/Y', strtotime($bm->tanggal_masuk)) ?></td>
                                                <td><?= ucfirst(str_replace('_', ' ', $bm->jenis_masuk)) ?></td>
                                                <td><?= $bm->nama_supplier ?: '-' ?></td>
                                                <td><?= $bm->nomor_faktur ?: '-' ?></td>
                                                <td class="text-right">Rp <?= number_format($bm->total_nilai, 0, ',', '.') ?></td>
                                                <td><?= $bm->user_input ?></td>
                                                <td>
                                                    <a href="<?= base_url('admin/barang-masuk/detail/' . $bm->id) ?>" class="btn btn-sm btn-info" target="_blank">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="6" class="text-right">Grand Total</th>
                                            <th class="text-right">Rp <?= number_format($grand_total, 0, ',', '.') ?></th>
                                            <th colspan="2"></th>
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

<script>
    $(document).ready(function() {
        $('#table-laporan').DataTable({
            "pageLength": 25,
            "dom": 'Bfrtip',
            "buttons": ['excel', 'pdf']
        });
    });
</script>