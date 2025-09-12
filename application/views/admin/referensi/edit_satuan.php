<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Referensi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-4">
                    <div class="card">
                        <div class="card-body">
                            <?php if ($this->session->flashdata('success')) : ?>
                                <div class="alert alert-success alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <?= $this->session->flashdata('success') ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($this->session->flashdata('error')) : ?>
                                <div class="alert alert-danger alert-dismissible show fade">
                                    <div class="alert-body">
                                        <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                        <?= $this->session->flashdata('error') ?>
                                    </div>
                                </div>
                            <?php endif; ?>
                            <form action="<?= base_url('admin/referensi/update_satuan') ?>" method="post">
                                <?= csrf_field() ?>
                                <input type="hidden" name="id" value="<?= $ref['id'] ?>">
                                <label for="">Nama Satuan</label>
                                <input type="text" name="satuan" class="form-control" value="<?= $ref['isi'] ?>">
                                <div class="mt-4">
                                    <a href="<?= base_url("admin/Referensi") ?>" class="btn btn-secondary float-left">Kembali</a>
                                    <button type="submit" class="btn btn-success float-right">Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>