<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/master-barang') ?>">Master Barang</a></div>
                <div class="breadcrumb-item">Edit</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12 col-md-8 col-lg-8">
                    <div class="card">
                        <!-- Form action mengarah ke URL yang sama untuk handle POST -->
                        <form action="<?= base_url('admin/master-barang/edit/' . $barang->id) ?>" method="POST" enctype="multipart/form-data">
                            <?= csrf_field() ?>
                            <div class="card-header">
                                <h4>Form Edit Barang</h4>
                            </div>
                            <div class="card-body">
                                <?php if (validation_errors()) : ?>
                                    <div class="alert alert-danger">
                                        <?= validation_errors() ?>
                                    </div>
                                <?php endif; ?>

                                <?php if ($this->session->flashdata('error')) : ?>
                                    <div class="alert alert-danger">
                                        <?= $this->session->flashdata('error') ?>
                                    </div>
                                <?php endif; ?>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode Rekening 108 <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_rek_108" class="form-control" value="<?= set_value('kode_rek_108', $barang->kode_rek_108) ?>" required>
                                            <small class="form-text text-muted">Contoh: 1.1.7.01.03.08.010</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Barang 108 <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_barang_108" class="form-control" value="<?= set_value('nama_barang_108', $barang->nama_barang_108) ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode NUSP <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_nusp" id="kode_nusp" class="form-control" value="<?= set_value('kode_nusp', $barang->kode_nusp) ?>" required>
                                            <small class="form-text text-muted">Kode harus unik</small>
                                            <div id="kode-nusp-feedback"></div>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama NUSP (Nama Lengkap Barang) <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_nusp" class="form-control" value="<?= set_value('nama_nusp', $barang->nama_nusp) ?>" required>
                                            <small class="form-text text-muted">Nama detail/spesifikasi barang</small>
                                        </div>

                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Kode Gudang <span class="text-danger">*</span></label>
                                            <input type="text" name="kode_gudang" class="form-control" value="<?= set_value('kode_gudang', $barang->kode_gudang) ?>">
                                            <small class="form-text text-muted">Kode dibuat Otomatis.</small>
                                        </div>
                                    </div>
                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label>Nama Gudang <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_gudang" class="form-control" value="<?= set_value('nama_gudang', $barang->nama_gudang) ?>" required>
                                            <small class="form-text text-muted">Nama detail/spesifikasi barang</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-2">
                                        <div class="form-group">
                                            <label>Stok Minimum</label>
                                            <input type="number" name="stok_minimum" class="form-control" value="<?= set_value('stok_minimum', $barang->stok_minimum) ?>" min="0">
                                            <small class="form-text text-muted">Untuk warning jika stok menipis</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label>Satuan <span class="text-danger">*</span></label>
                                            <select name="satuan" class="form-control select2" required>
                                                <option value="">-- Pilih Satuan --</option>
                                                <?php foreach ($ref as $r) : ?>
                                                    <option value="<?= $r->isi ?>" <?= set_select('satuan', $r->isi, ($barang->satuan == $r->isi)) ?>>
                                                        <?= $r->isi ?>
                                                    </option>
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
                                                <input type="number" name="harga_terakhir" class="form-control" step="0.01" lang="id" value="<?= set_value('harga_terakhir', $barang->harga_terakhir) ?>">
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Status</label>
                                            <select name="status" class="form-control">
                                                <option value="aktif" <?= ($barang->status == 'aktif') ? 'selected' : '' ?>>Aktif</option>
                                                <option value="nonaktif" <?= ($barang->status == 'nonaktif') ? 'selected' : '' ?>>Nonaktif</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Gambar Barang</label>
                                            <?php if ($barang->gambar && file_exists('./uploads/barang/' . $barang->gambar)) : ?>
                                                <div class="mb-3">
                                                    <img src="<?= base_url('uploads/barang/' . $barang->gambar) ?>" class="img-thumbnail" style="max-width: 200px;">
                                                    <p class="text-muted mt-2">Gambar saat ini</p>
                                                </div>
                                            <?php endif; ?>
                                            <div class="custom-file">
                                                <input type="file" class="custom-file-input" id="gambar" name="gambar" accept="image/*">
                                                <label class="custom-file-label" for="gambar">Pilih gambar baru (opsional)</label>
                                            </div>
                                            <small class="form-text text-muted">Kosongkan jika tidak ingin mengganti gambar. Format: JPG, JPEG, PNG. Max: 2MB</small>
                                            <div id="image-preview" class="mt-3"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/master-barang') ?>" class="btn btn-secondary">
                                    <i class="fas fa-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Update
                                </button>
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

        // Preview image on change
        $('#gambar').change(function() {
            var file = this.files[0];
            if (file) {
                // Validate file size (2MB)
                if (file.size > 2 * 1024 * 1024) {
                    alert('Ukuran file terlalu besar. Maksimal 2MB');
                    $(this).val('');
                    return;
                }

                // Validate file type
                var validTypes = ['image/jpeg', 'image/jpg', 'image/png'];
                if (!validTypes.includes(file.type)) {
                    alert('Format file tidak valid. Hanya JPG, JPEG, dan PNG yang diperbolehkan');
                    $(this).val('');
                    return;
                }

                var reader = new FileReader();
                reader.onload = function(e) {
                    $('#image-preview').html('<img src="' + e.target.result + '" class="img-thumbnail" style="max-width: 200px;">');
                }
                reader.readAsDataURL(file);

                // Update label with file name
                var fileName = file.name;
                $('.custom-file-label').text(fileName);
            }
        });

        // Check kode_nusp uniqueness (optional - real-time validation)
        var originalKode = '<?= $barang->kode_nusp ?>';
        $('#kode_nusp').on('blur', function() {
            var kode = $(this).val();

            // Skip if same as original
            if (kode == originalKode) {
                $('#kode-nusp-feedback').html('');
                return;
            }

            if (kode) {
                $.ajax({
                    url: '<?= base_url('admin/master-barang/check_kode_nusp') ?>',
                    type: 'POST',
                    data: {
                        kode_nusp: kode,
                        id: <?= $barang->id ?>
                    },
                    dataType: 'json',
                    success: function(response) {
                        if (response.status) {
                            $('#kode-nusp-feedback').html('<small class="text-success">' + response.message + '</small>');
                        } else {
                            $('#kode-nusp-feedback').html('<small class="text-danger">' + response.message + '</small>');
                        }
                    }
                });
            }
        });
    });
</script>