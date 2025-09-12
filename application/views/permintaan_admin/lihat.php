<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Riwayat Pemintaan Barang</h4>
        </div>
        <div class="card-body">
            <div class="card bg-secondary">
                <div class="card-body">
                    Keterangan : <br>
                    <span class="ml-2">
                        <?= $ket['KET'] ?>
                    </span>
                    <br>
                    Status :
                    <?php if ($ket['STATUS'] == 1) : ?>
                        <span class="badge badge-success">Setujui</span>
                    <?php else : ?>
                        <span class="badge badge-danger">Tolak</span>
                    <?php endif ?>
                </div>
            </div>
            <a href="<?php echo base_url(); ?>Permintaan/tambah" class="btn btn-warning">Cetak</a>
            <form method="post" action="<?= base_url('Permintaan_admin/update_status') ?>">
                <?= csrf_field() ?>
                <div class="w-100 mt-4">
                    <div class="card bg-secondary">
                        <div class="card-body">
                            <table class="table table-hover text-dark">
                                <thead>
                                    <tr>
                                        <th rowspan="2" class="align-middle text-center">NO</th>
                                        <th rowspan="2" class="align-middle">KODE</th>
                                        <th rowspan="2" class="align-middle">NAMA</th>
                                        <th rowspan="2" class="align-middle text-center">STOK</th>
                                        <th colspan="2" class="text-center">JUMLAH</th>
                                        <th rowspan="2" class="align-middle text-center">#</th>
                                    </tr>
                                    <tr>
                                        <th width="10%" class="text-center">PERMINTAAN</th>
                                        <th width="10%" class="text-center">DIBERIKAN</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1;
                                    foreach ($data as $d) : ?>
                                        <tr>
                                            <td class="align-middle text-center"><?= $i++ ?></td>
                                            <td class="align-middle"><?= $d->KODE ?></td>
                                            <td class="align-middle"><?= $d->NAMA_BARANG ?></td>
                                            <td class="align-middle text-center"><?= $d->STOK ?></td>
                                            <td class="align-middle text-center"><?= $d->JUMLAH ?></td>
                                            <td class="align-middle">
                                                <input type="number" name="jumlah[]" class="form-control text-center" value="<?= isset($d->JUMLAH_DISETUJUI) && $d->JUMLAH_DISETUJUI !== null ? $d->JUMLAH_DISETUJUI : $d->JUMLAH ?>">
                                            </td>
                                            <td width="10%" class="align-middle">
                                                <input type="hidden" name="id_detail[]" value="<?= $d->ID ?>">
                                                <select name="status[]" class="form-control">
                                                    <option value="1" <?= $d->STATUS == 1 ? 'selected' : '' ?>>Setujui</option>
                                                    <option value="2" <?= $d->STATUS == 2 ? 'selected' : '' ?>>Tolak</option>
                                                </select>
                                            </td>
                                        </tr>
                                    <?php endforeach ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-success float-right">Simpan & Setujui</button>
                    <a href="<?= base_url('Permintaan_admin') ?>" class="btn btn-secondary mr-2 float-right">Kembali</a>
                    <button type="submit" class="btn btn-danger mr-2 float-right">Tolak</button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>