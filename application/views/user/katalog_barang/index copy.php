<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('user') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Katalog Barang</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Search and Filter -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-body">
                            <form method="GET" action="">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Cari barang..." value="<?= $search ?>">
                                                <div class="input-group-append">
                                                    <button class="btn btn-primary" type="submit">
                                                        <i class="fas fa-search"></i>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <select name="kategori" class="form-control" onchange="this.form.submit()">
                                                <option value="">Semua Kategori</option>
                                                <?php foreach ($kategori_list as $kat) : ?>
                                                    <option value="<?= $kat->kode_rek_108 ?>" <?= $kategori == $kat->kode_rek_108 ? 'selected' : '' ?>>
                                                        <?= $kat->nama_barang_108 ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <a href="<?= base_url('user/permintaan-barang/buat') ?>" class="btn btn-success">
                                            <i class="fas fa-shopping-cart"></i>
                                            Keranjang Permintaan
                                            <span class="badge badge-light cart-badge">
                                                <?= count($this->session->userdata('cart') ?: []) ?>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Katalog Grid -->
            <div class="row">
                <?php foreach ($barang as $item) : ?>
                    <div class="col-12 col-sm-6 col-md-4 col-lg-3">
                        <article class="article article-style-b">
                            <div class="article-header">
                                <div class="article-image" data-background="<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>" style="cursor: pointer; height: 200px; background-size: cover; background-position: center;" onclick="showImage('<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>', '<?= $item->nama_nusp ?>')">
                                </div>
                                <?php if ($item->stok_minimum > 0) : ?>
                                    <div class="article-badge">
                                        <div class="article-badge-item bg-success">
                                            <i class="fas fa-check"></i> Tersedia
                                        </div>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="article-details">
                                <div class="article-title">
                                    <h6><?= $item->nama_nusp ?></h6>
                                </div>
                                <p class="text-muted small mb-2">
                                    <i class="fas fa-barcode"></i> <?= $item->kode_nusp ?><br>
                                    <i class="fas fa-cube"></i> Satuan: <?= $item->satuan ?>
                                </p>
                                <div class="article-cta">
                                    <div class="row">
                                        <div class="col-6">
                                            <input type="number" class="form-control form-control-sm qty-input" id="qty-<?= $item->id ?>" value="1" min="1">
                                        </div>
                                        <div class="col-6">
                                            <button class="btn btn-primary btn-sm btn-block btn-add-cart" data-id="<?= $item->id ?>">
                                                <i class="fas fa-plus"></i> Tambah
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </article>
                    </div>
                <?php endforeach; ?>

                <?php if (empty($barang)) : ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-state-icon">
                                <i class="fas fa-question"></i>
                            </div>
                            <h2>Barang tidak ditemukan</h2>
                            <p class="lead">
                                Coba ubah kata kunci pencarian atau filter kategori
                            </p>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <div class="row">
                <div class="col-12">
                    <nav aria-label="Page navigation">
                        <?= $pagination ?>
                    </nav>
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

    // Add to cart
    $('.btn-add-cart').click(function() {
        var id = $(this).data('id');
        var qty = $('#qty-' + id).val();
        var btn = $(this);

        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');

        $.ajax({
            url: '<?= base_url('user/permintaan-barang/add_to_cart') ?>',
            type: 'POST',
            data: {
                barang_id: id,
                jumlah: qty,
                <?= $this->security->get_csrf_token_name() ?>: '<?= $this->security->get_csrf_hash() ?>'
            },
            dataType: 'json',
            success: function(response) {
                if (response.status == 'success') {
                    // Update cart badge
                    $('.cart-badge').text(response.cart_count);

                    // Show notification
                    iziToast.success({
                        title: 'Berhasil',
                        message: response.message,
                        position: 'topRight'
                    });

                    // Reset quantity
                    $('#qty-' + id).val(1);
                } else {
                    iziToast.error({
                        title: 'Error',
                        message: response.message,
                        position: 'topRight'
                    });
                }
            },
            complete: function() {
                btn.prop('disabled', false).html('<i class="fas fa-plus"></i> Tambah');
            }
        });
    });
</script>