<!-- Main Content -->
<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/master-barang') ?>">Master Barang</a></div>
                <div class="breadcrumb-item">Tambah Per NUSP</div>
            </div>
        </div>

        <div class="section-body">
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
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Detail Data NUSP</h4>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('admin/Master_barang/tambah_per_nusp') ?>" method="POST" enctype="multipart/form-data">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Kode Rekening 108</label>
                                            <input type="text" name="kode_rek_108" class="form-control" value="<?= $data['kode_rek_108'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nama Barang 108</label>
                                            <input type="text" name="nama_barang_108" class="form-control" value="<?= $data['nama_barang_108'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Kode NUSP</label>
                                            <input type="text" name="kode_nusp" class="form-control" value="<?= $data['kode_nusp'] ?>" readonly>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Nama NUSP <span class="text-danger">*</span></label>
                                            <input type="text" name="nama_nusp" class="form-control border-primary" value="<?= $data['nama_nusp'] ?>" readonly>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <label>Data barang gudang pada NUSP ini :</label>
                                        <table class="table table-bordered">
                                            <tr>
                                                <th width="10px">No</th>
                                                <th>Kode</th>
                                                <th>Nama</th>
                                                <th>Satuan</th>
                                            </tr>
                                            <?php $i = 1;
                                            foreach ($data_per_nusp as $d) : ?>
                                                <tr>
                                                    <td><?= $i++ ?></td>
                                                    <td><?= $d->kode_gudang ?></td>
                                                    <td><?= $d->nama_gudang ?></td>
                                                    <td><?= $d->satuan ?></td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </table>
                                    </div>
                                </div>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="">Form Tambah Barang Gudang</h4>
                            <div class="card-header-action">
                                <span class="badge badge-info">Kode Otomatis : <?= $kode_auto ?></span>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if (validation_errors()) : ?>
                                <div class="alert alert-danger">
                                    <?= validation_errors() ?>
                                </div>
                            <?php endif; ?>
                            <div class="row">
                                <div class="col-md-8">
                                    <div class="form-group">
                                        <label>Nama Barang Gudang <span class="text-danger">*</span></label>
                                        <input type="text" name="nama_barang_gudang" class="form-control" value="<?= set_value('nama_barang_gudang') ?>" required>
                                        <small class="form-text text-muted">Nama detail/spesifikasi barang</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Satuan <span class="text-danger">*</span></label>
                                        <select name="satuan" class="form-control select2" required>
                                            <option value="">-- Pilih Satuan --</option>
                                            <?php foreach ($ref as $r) : ?>
                                                <option value="<?= $r->isi ?>" <?= set_select('satuan', $r->isi) ?>><?= $r->isi ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>



                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label>Stok Minimum</label>
                                        <input type="number" name="stok_minimum" class="form-control" value="<?= set_value('stok_minimum', 0) ?>" min="0">
                                        <small class="form-text text-muted">Untuk warning jika stok menipis</small>
                                    </div>
                                </div>
                                <div class="col-md-4">
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
                                <div class="col-md-4">
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