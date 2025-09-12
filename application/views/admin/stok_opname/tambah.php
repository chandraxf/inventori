<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Tambah Stok Opname</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/stok-opname') ?>">Stok Opname</a></div>
                <div class="breadcrumb-item">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <form action="<?= base_url('admin/stok-opname/simpan') ?>" method="POST">
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Stok Opname</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Opname</label>
                                            <input type="date" name="tanggal_opname" class="form-control" 
                                                   value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Periode</label>
                                            <input type="month" name="periode" class="form-control" 
                                                   value="<?= date('Y-m') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jenis Opname</label>
                                            <select name="jenis_opname" class="form-control" required>
                                                <option value="bulanan">Bulanan</option>
                                                <option value="triwulan">Triwulan</option>
                                                <option value="semester">Semester</option>
                                                <option value="tahunan">Tahunan</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Petugas</label>
                                            <input type="text" name="petugas" class="form-control" 
                                                   placeholder="Nama Petugas" required>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="form-group">
                                    <label>Keterangan</label>
                                    <textarea name="keterangan" class="form-control" rows="2"></textarea>
                                </div>
                                
                                <h5 class="mt-4 mb-3">Detail Barang</h5>
                                
                                <div class="alert alert-info">
                                    <strong>Petunjuk:</strong> Masukkan jumlah stok fisik yang dihitung. Sistem akan otomatis menghitung selisih dengan stok sistem.
                                </div>
                                
                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table-opname">
                                        <thead>
                                            <tr>
                                                <th width="5%">#</th>
                                                <th width="15%">Kode</th>
                                                <th width="30%">Nama Barang</th>
                                                <th width="10%">Satuan</th>
                                                <th width="10%">Stok Sistem</th>
                                                <th width="10%">Stok Fisik</th>
                                                <th width="10%">Selisih</th>
                                                <th width="10%">Kondisi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1; foreach($barang_with_stok as $b): ?>
                                            <tr>
                                                <td><?= $no++ ?></td>
                                                <td><?= $b->kode_nusp ?></td>
                                                <td><?= $b->nama_nusp ?></td>
                                                <td><?= $b->satuan ?></td>
                                                <td>
                                                    <input type="hidden" name="barang_id[]" value="<?= $b->id ?>">
                                                    <input type="hidden" name="stok_sistem[]" value="<?= $b->stok_saat_ini ?>" 
                                                           class="stok-sistem">
                                                    <input type="hidden" name="harga_satuan[]" value="<?= $b->harga_rata_rata ?>">
                                                    <span class="stok-sistem-text"><?= $b->stok_saat_ini ?></span>
                                                </td>
                                                <td>
                                                    <input type="number" name="stok_fisik[]" class="form-control stok-fisik" 
                                                           value="<?= $b->stok_saat_ini ?>" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="selisih[]" class="form-control selisih" readonly>
                                                    <small class="selisih-text"></small>
                                                </td>
                                                <td>
                                                    <select name="kondisi[]" class="form-control">
                                                        <option value="baik">Baik</option>
                                                        <option value="rusak_ringan">Rusak Ringan</option>
                                                        <option value="rusak_berat">Rusak Berat</option>
                                                    </select>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/stok-opname') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
$(document).ready(function() {
    // Calculate selisih
    $('.stok-fisik').on('input', function() {
        var row = $(this).closest('tr');
        var stok_sistem = parseInt(row.find('.stok-sistem').val()) || 0;
        var stok_fisik = parseInt($(this).val()) || 0;
        var selisih = stok_fisik - stok_sistem;
        
        row.find('.selisih').val(selisih);
        
        if (selisih > 0) {
            row.find('.selisih-text').html('<span class="text-success">Lebih ' + Math.abs(selisih) + '</span>');
        } else if (selisih < 0) {
            row.find('.selisih-text').html('<span class="text-danger">Kurang ' + Math.abs(selisih) + '</span>');
        } else {
            row.find('.selisih-text').html('<span class="text-muted">Sesuai</span>');
        }
    });
    
    // Trigger calculation on load
    $('.stok-fisik').trigger('input');
});
</script>