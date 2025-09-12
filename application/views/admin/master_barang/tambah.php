<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/master-barang') ?>">Master Barang</a></div>
                <div class="breadcrumb-item">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <form action="<?= base_url('admin/master-barang/tambah') ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="card-header">
                                <h4>Form Tambah Barang</h4>
                            </div>
                            <div class="card-body">
                                <?php if (validation_errors()) : ?>
                                    <div class="alert alert-danger">
                                        <?= validation_errors() ?>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode Rekening 108 <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_rek_108" class="form-control" value="<?= set_value('kode_rek_108') ?>" required>
                                            <small class="form-text text-muted">Contoh: 1.1.7.01.03.08.010</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Barang 108 <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_barang_108" class="form-control" value="<?= set_value('nama_barang_108') ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode NUSP <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_nusp" class="form-control" value="<?= set_value('kode_nusp') ?>" required>
                                            <small class="form-text text-muted">Sesuaikan kode.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama NUSP (Nama Lengkap Barang) <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_nusp" class="form-control" value="<?= set_value('nama_nusp') ?>" required>
                                            <small class="form-text text-muted">Nama detail/spesifikasi barang</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode Gudang <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_gudang" class="form-control" value="<?= set_value('kode_gudang') ?>" readonly>
                                            <small class="form-text text-muted">Kode dibuat Otomatis.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Gudang <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_gudang" class="form-control" value="<?= set_value('nama_gudang') ?>" required>
                                            <small class="form-text text-muted">Nama detail/spesifikasi barang</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stok Minimum</label>
                                            <input type="number" name="stok_minimum" class="form-control" value="<?= set_value('stok_minimum', 0) ?>" min="0">
                                            <small class="form-text text-muted">Untuk warning jika stok menipis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Satuan <span class="text-danger">*</span></label>
                                            <select name="satuan" class="form-control select2" required>
                                                <option value="" selected disabled>-- Pilih Satuan --</option>
                                                <?php foreach ($ref as $r) : ?>
                                                    <option value="<?= $r->isi ?>" <?= set_select('satuan', $r->isi) ?>><?= $r->isi ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Harga Terakhir</label>
                                            <div class="input-group">
                                                <div class="input-group-prepend">
                                                    <span class="input-group-text">Rp</span>
                                                </div>
                                                <input type="number" name="harga_terakhir" class="form-control" value="<?= set_value('harga_terakhir', 0) ?>" min="0">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label>Gambar Barang</label>
                                    <div class="custom-file">
                                        <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                        <label class="custom-file-label" for="gambar">Pilih gambar</label>
                                    </div>
                                    <small class="form-text text-muted">Format: JPG, JPEG, PNG. Max: 2MB</small>
                                    <div id="image-preview" class="mt-3"></div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/master-barang') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $('.select2').select2();

        // Preview image
        $('#gambar').change(function() {
            var file = this.files[0];
            if (file) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">');
                }
                reader.readAsDataURL(file);

                // Update label
                var fileName = file.name;
                $('.custom-file-label').text(fileName);
            }
        });
    });
</script>