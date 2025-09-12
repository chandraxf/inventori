<div class="main-content">
    
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Stok Barang</h4>
        </div>
        <div class="card-body">
            <table class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE</th>
                        <th>NAMA BARANG</th>
                        <th>STOK AWAL</th>
                        <th>JUMLAH PERMINTAAN</th>
                        <th>STOK AKHIR</th>
                        <th>WAKTU</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $d->KODE ?></td>
                            <td><?= $d->NAMA_BARANG ?></td>
                            <td><?= $d->STOK_AWAL ?></td>
                            <td><?= $d->JUMLAH ?></td>
                            <td><?= $d->STOK_AKHIR ?></td>
                            <td><?= date('d-m-Y H:i:s', strtotime($d->CREATED_AT)) ?></td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('Master') ?>" class="btn btn-secondary float-right">Kembali</a>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });
</script>