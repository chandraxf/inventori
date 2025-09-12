<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('user') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('user/permintaan-barang') ?>">Permintaan</a></div>
                <div class="breadcrumb-item">Buat</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Keranjang Permintaan Barang</h4>
                            <div class="card-header-action">
                                <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-info">
                                    <i class="fas fa-plus"></i> Tambah Barang
                                </a>
                                <?php if(!empty($cart_items)): ?>
                                <a href="<?= base_url('user/permintaan-barang/clear_cart') ?>" 
                                   class="btn btn-danger" onclick="return confirm('Kosongkan keranjang?')">
                                    <i class="fas fa-trash"></i> Kosongkan
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if(empty($cart_items)): ?>
                            <div class="empty-state">
                                <div class="empty-state-icon bg-primary">
                                    <i class="fas fa-shopping-cart"></i>
                                </div>
                                <h2>Keranjang Kosong</h2>
                                <p class="lead">
                                    Silakan pilih barang dari katalog terlebih dahulu
                                </p>
                                <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary mt-4">
                                    Lihat Katalog Barang
                                </a>
                            </div>
                            <?php else: ?>
                            
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th width="60">Gambar</th>
                                            <th>Kode</th>
                                            <th>Nama Barang</th>
                                            <th>Satuan</th>
                                            <th width="150">Jumlah</th>
                                            <th width="100">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach($cart_items as $item): ?>
                                        <tr>
                                            <td>
                                                <img src="<?= $item['gambar'] ? base_url('uploads/barang/'.$item['gambar']) : base_url('assets/img/no-image.png') ?>" 
                                                     class="img-thumbnail" width="50" style="cursor: pointer"
                                                     onclick="showImage('<?= $item['gambar'] ? base_url('uploads/barang/'.$item['gambar']) : base_url('assets/img/no-image.png') ?>', '<?= $item['nama_nusp'] ?>')">
                                            </td>
                                            <td><?= $item['kode_nusp'] ?></td>
                                            <td><?= $item['nama_nusp'] ?></td>
                                            <td><?= $item['satuan'] ?></td>
                                            <td>
                                                <div class="input-group">
                                                    <input type="number" class="form-control qty-update" 
                                                           data-id="<?= $item['id'] ?>"
                                                           value="<?= $item['jumlah'] ?>" min="1">
                                                    <div class="input-group-append">
                                                        <button class="btn btn-primary btn-update" data-id="<?= $item['id'] ?>">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('user/permintaan-barang/remove_from_cart/'.$item['id']) ?>" 
                                                   class="btn btn-danger btn-sm"
                                                   onclick="return confirm('Hapus barang ini?')">
                                                    <i class="fas fa-trash"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <hr>
                            
                            <form action="<?= base_url('user/permintaan-barang/submit') ?>" method="POST">
                                <?= csrf_field() ?>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Keperluan <span class="text-danger">*</span></label>
                                            <input type="text" name="keperluan" class="form-control" required
                                                   placeholder="Contoh: Kebutuhan operasional bulanan">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="3"
                                                      placeholder="Keterangan tambahan (opsional)"></textarea>
                                        </div>
                                    </div>
                                </div>
                                
                                <div class="text-right">
                                    <button type="submit" class="btn btn-success btn-lg">
                                        <i class="fas fa-paper-plane"></i> Ajukan Permintaan
                                    </button>
                                </div>
                            </form>
                            
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<!-- Modal Image Preview -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalTitle">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
// Show image in modal
function showImage(src, title) {
    $('#modalImage').attr('src', src);
    $('#imageModalTitle').text(title);
    $('#imageModal').modal('show');
}

// Update quantity
$('.btn-update').click(function() {
    var id = $(this).data('id');
    var qty = $('.qty-update[data-id="' + id + '"]').val();
    var btn = $(this);
    
    btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
    
    $.ajax({
        url: '<?= base_url('user/permintaan-barang/update_cart') ?>',
        type: 'POST',
        data: {
            barang_id: id,
            jumlah: qty,
            <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
        },
        dataType: 'json',
        success: function(response) {
            if (response.status == 'success') {
                iziToast.success({
                    title: 'Berhasil',
                    message: 'Jumlah diperbarui',
                    position: 'topRight'
                });
                
                if (qty == 0) {
                    location.reload();
                }
            }
        },
        complete: function() {
            btn.prop('disabled', false).html('<i class="fas fa-check"></i>');
        }
    });
});
</script>