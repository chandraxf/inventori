<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Data Master Barang</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Master/update') ?>" method="post" enctype="multipart/form-data">
                <input type="hidden" id="csrf_token_name" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <input type="hidden" name="ID" value="<?= $id_asli ?>">
                <div class="mb-2">
                    <label for="" class="form-label">KODE REK</label>
                    <input type="text" class="form-control" name="kode_rek" value="<?= $data['KODE_REK'] ?>" readonly>
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">NAMA</label>
                    <input type="text" class="form-control" name="nama" value="<?= $data['NAMA'] ?>">
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">KODE REK</label>
                    <input type="text" class="form-control" name="kode_rek" value="<?= $data['KODE'] ?>" readonly>
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">NAMA</label>
                    <input type="text" class="form-control" name="nama" value="<?= $data['NAMA_BARANG'] ?>">
                </div>
                <div class="mb-2">
                    <label for="" class="form-label">GAMBAR</label>
                    <input type="file" class="form-control" name="gambar" value="">
                </div>
                <?php if ($data['GAMBAR']) : ?>
                <div class="mb-2">
                    <img src="<?= base_url('assets/img/gambar/' . $data['GAMBAR']) ?>" alt="" width="500" class="img-fluid">
                </div>
                <?php else : ?>
                    <span class="badge badge-secondary">Tidak Ada Gambar</span>
                <?php endif; ?>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-right">Simpan</button>
            <a href="<?= base_url('Master') ?>" class="btn btn-secondary float-right mr-2">Batal</a>
        </div>
        </form>
    </div>
</div>