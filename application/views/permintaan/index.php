<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Pemintaan Barang</h4>
        </div>
        <div class="card-body">
            <a href="<?php echo base_url(); ?>Permintaan/ajukan" class="btn btn-primary">Ajukan Permintaan</a>
            <a href="<?php echo base_url(); ?>Permintaan/tambah" class="btn btn-warning">Cetak</a>
            <table class="table table-striped mt-4 table-hover">
                <tr>
                    <th>NO</th>
                    <th>KETERANGAN</th>
                    <th>STATUS</th>
                    <th>JUMLAH BARANG</th>
                    <th>TANGGAL</th>
                    <th>#</th>
                </tr>
                <?php $i = 1;
                foreach ($riwayat as $r) : ?>
                    <tr>
                        <td><?= $i++ ?></td>
                        <td><?= $r->KET ?></td>
                        <td><?php
                            if ($r->STATUS == 0) {
                                echo '<span class="badge badge-info">Terkirim</span>';
                            } elseif ($r->STATUS == 1) {
                                echo '<span class="badge badge-success">Disetujui</span>';
                            } elseif ($r->STATUS == 2) {
                                echo '<span class="badge badge-danger">Ditolak</span>';
                            }
                            ?>
                        </td>
                        <td><?= $r->total_detail ?></td>
                        <td><?= date('d-m-Y H:i:s', strtotime($r->CREATED_AT)) ?></td>
                        <td>
                            <a href="<?= base_url('Permintaan/lihat/') . hash_id($r->ID) ?>" class="btn btn-primary">Lihat</a>
                            <a href="" class="btn btn-info">Edit</a>
                        </td>
                    </tr>
                <?php endforeach ?>
            </table>
        </div>
    </div>
</div>