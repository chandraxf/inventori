<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Detail Barang Masuk</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/barang-masuk') ?>">Barang Masuk</a></div>
                <div class="breadcrumb-item">Detail</div>
            </div>
        </div>

        <div class="section-body">
            <div class="invoice">
                <div class="invoice-print">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="invoice-title">
                                <h2>Barang Masuk</h2>
                                <div class="invoice-number">
                                    No. Transaksi: <strong><?= $header->nomor_transaksi ?></strong>
                                    <?php if($header->status == 'posted'): ?>
                                        <span class="badge badge-success ml-2">Posted</span>
                                    <?php elseif($header->status == 'cancelled'): ?>
                                        <span class="badge badge-danger ml-2">Cancelled</span>
                                    <?php else: ?>
                                        <span class="badge badge-warning ml-2">Draft</span>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <address>
                                        <strong>Informasi Transaksi:</strong><br>
                                        Tanggal: <?= date('d F Y', strtotime($header->tanggal_masuk)) ?><br>
                                        Jenis: <?= ucfirst(str_replace('_', ' ', $header->jenis_masuk)) ?><br>
                                        No. Faktur: <?= $header->nomor_faktur ?: '-' ?><br>
                                        Supplier: <?= $header->nama_supplier ?: '-' ?>
                                    </address>
                                </div>
                                <div class="col-md-6 text-md-right">
                                    <address>
                                        <strong>Input Oleh:</strong><br>
                                        <?= $header->user_input ?><br>
                                        <?= date('d/m/Y H:i', strtotime($header->created_at)) ?>
                                    </address>
                                </div>
                            </div>
                            
                            <?php if($header->keterangan): ?>
                            <div class="row">
                                <div class="col-md-12">
                                    <address>
                                        <strong>Keterangan:</strong><br>
                                        <?= $header->keterangan ?>
                                    </address>
                                </div>
                            </div>
                            <?php endif; ?>
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
                                            <th class="text-center">Qty</th>
                                            <th class="text-center">Satuan</th>
                                            <th class="text-right">Harga</th>
                                            <th class="text-right">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach($detail as $d): ?>
                                        <tr>
                                            <td><?= $no++ ?></td>
                                            <td><?= $d->kode_nusp ?></td>
                                            <td><?= $d->nama_nusp ?></td>
                                            <td class="text-center"><?= $d->jumlah ?></td>
                                            <td class="text-center"><?= $d->satuan ?></td>
                                            <td class="text-right">Rp <?= number_format($d->harga_satuan, 0, ',', '.') ?></td>
                                            <td class="text-right">Rp <?= number_format($d->total, 0, ',', '.') ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="row mt-4">
                                <div class="col-lg-8">
                                    <!-- Empty -->
                                </div>
                                <div class="col-lg-4 text-right">
                                    <div class="invoice-detail-item">
                                        <div class="invoice-detail-name">Total</div>
                                        <div class="invoice-detail-value invoice-detail-value-lg">
                                            Rp <?= number_format($header->total_nilai, 0, ',', '.') ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <hr>
                
                <div class="text-md-right">
                    <a href="<?= base_url('admin/barang-masuk') ?>" class="btn btn-secondary">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <?php if($header->status == 'draft'): ?>
                    <a href="<?= base_url('admin/barang-masuk/posting/'.$header->id) ?>" 
                       class="btn btn-success btn-posting">
                        <i class="fas fa-check"></i> Posting
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

<script>
$('.btn-posting').on('click', function(e) {
    e.preventDefault();
    var url = $(this).attr('href');
    
    Swal.fire({
        title: 'Konfirmasi Posting',
        text: "Transaksi yang sudah diposting tidak dapat diubah!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Posting!',
        cancelButtonText: 'Batal'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = url;
        }
    });
});
</script>