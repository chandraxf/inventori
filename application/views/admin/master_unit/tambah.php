<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/master-unit') ?>">Master Unit</a></div>
                <div class="breadcrumb-item">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-6 col-lg-6">
                    <div class="card">
                        <form action="<?= base_url('admin/master-unit/tambah') ?>" method="POST">
                            <div class="card-header">
                                <h4>Form Tambah Unit</h4>
                            </div>
                            <div class="card-body">
                                <?php if (validation_errors()) : ?>
                                    <div class="alert alert-danger">
                                        <?= validation_errors() ?>
                                    </div>
                                <?php endif; ?>

                                <div class="form-group">
                                    <label>Kode Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="kode_unit" class="form-control" value="<?= set_value('kode_unit') ?>" required>
                                    <small class="form-text text-muted">Contoh: U001, IT, KEU</small>
                                </div>

                                <div class="form-group">
                                    <label>Nama Unit <span class="text-danger">*</span></label>
                                    <input type="text" name="nama_unit" class="form-control" value="<?= set_value('nama_unit') ?>" required>
                                </div>

                                <div class="form-group">
                                    <label>Penanggung Jawab</label>
                                    <input type="text" name="penanggung_jawab" class="form-control" value="<?= set_value('penanggung_jawab') ?>">
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/master-unit') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>