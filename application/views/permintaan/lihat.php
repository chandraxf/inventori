<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Pemintaan Barang</h4>
        </div>
        <div class="card-body">
            <a href="<?php echo base_url(); ?>Permintaan/tambah" class="btn btn-warning">Cetak</a>
            <table class="table table-striped table-hover">
                <tr>
                    <th>NO</th>
                    <th>KODE</th>
                    <th>NAMA</th>
                    <th class="text-center">JUMLAH PEMINTAAN</th>
                    <th class="text-center">JUMLAH DISETUJUI</th>
                    <th>STATUS</th>
                </tr>
                <?php $i=1; foreach($data as $d) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $d->KODE ?></td>
                        <td><?= $d->NAMA_BARANG ?></td>
                        <td class="text-center"><?= $d->JUMLAH ?></td>
                        <td class="text-center"><?= ($d->JUMLAH_DISETUJUI) ? $d->JUMLAH_DISETUJUI : '-' ?></td>
                        <td>
                            <?php if($d->STATUS == 0) { ?>
                                <span class="badge badge-info">Terkirim</span>
                            <?php } else if($d->STATUS == 1) { ?>
                                <span class="badge badge-success">Disetujui</span>
                            <?php } else { ?>
                                <span class="badge badge-danger">Ditolak</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
            </table>
        </div>
        <div class="card-footer">
            <a href="<?= base_url('Permintaan') ?>" class="btn btn-secondary float-right">Kembali</a>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>
