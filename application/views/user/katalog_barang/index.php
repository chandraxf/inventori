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
                            <form method="GET" action="<?= base_url('user/katalog-barang') ?>">
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <div class="input-group">
                                                <input type="text" name="search" class="form-control" placeholder="Cari nama atau kode barang..." value="<?= $search ?>">
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
                                                <option value="">-- Semua Kategori --</option>
                                                <?php foreach ($kategori_list as $kat) : ?>
                                                    <option value="<?= $kat->kode_rek_108 ?>" <?= $kategori == $kat->kode_rek_108 ? 'selected' : '' ?>>
                                                        <?= $kat->nama_barang_108 ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-5 text-right">
                                        <div class="btn-group">
                                            <?php if ($search || $kategori) : ?>
                                                <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-secondary">
                                                    <i class="fas fa-times"></i> Reset Filter
                                                </a>
                                            <?php endif; ?>
                                            <a href="<?= base_url('user/permintaan-barang/buat') ?>" class="btn btn-success">
                                                <i class="fas fa-shopping-cart"></i>
                                                Keranjang Permintaan
                                                <span class="badge badge-light cart-badge" id="cart-count">
                                                    <?= $cart_count ?>
                                                </span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-12">
                                        <p class="text-muted">
                                            Menampilkan <?= count($barang) ?> dari <?= $total_rows ?> barang
                                            <?php if ($search) : ?>
                                                | Pencarian: "<strong><?= $search ?></strong>"
                                            <?php endif; ?>
                                            <?php if ($kategori) : ?>
                                                | Kategori: <strong><?= $kategori ?></strong>
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Katalog Grid -->
            <div class="row" id="katalog-container">
                <?php if (!empty($barang)) : ?>
                    <?php foreach ($barang as $item) : ?>
                        <div class="col-12 col-sm-6 col-md-4 col-lg-3 mb-3">
                            <article class="article article-style-b">
                                <div class="article-header">
                                    <div class="article-image" data-background="<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>" style="cursor: pointer; height: 200px; background-size: contain; background-position: center; background-repeat: no-repeat;" onclick="showImage('<?= $item->gambar ? base_url('uploads/barang/' . $item->gambar) : base_url('assets/img/no-image.png') ?>', '<?= htmlspecialchars($item->nama_nusp, ENT_QUOTES) ?>')">
                                    </div>
                                </div>
                                <div class="article-details">
                                    <div class="article-title">
                                        <h6 title="<?= $item->nama_gudang ?>">
                                            <?= character_limiter($item->nama_gudang, 40) ?>
                                        </h6>
                                    </div>
                                    <p class="text-muted small mb-2">
                                        <i class="fas fa-barcode"></i> <?= $item->kode_nusp ?><br>
                                        <i class="fas fa-cube"></i> Satuan: <?= $item->satuan ?>
                                    </p>
                                    <div class="article-cta">
                                        <div class="row">
                                            <div class="col-5">
                                                <input type="number" class="form-control form-control-sm qty-input" id="qty-<?= $item->id ?>" value="1" min="1" max="999">
                                            </div>
                                            <div class="col-7">
                                                <button class="btn btn-primary btn-sm btn-block btn-add-cart" data-id="<?= $item->id ?>" data-nama="<?= htmlspecialchars($item->nama_nusp, ENT_QUOTES) ?>">
                                                    <i class="fas fa-plus"></i> Tambah
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    <?php endforeach; ?>
                <?php else : ?>
                    <div class="col-12">
                        <div class="empty-state">
                            <div class="empty-state-icon bg-primary">
                                <i class="fas fa-question"></i>
                            </div>
                            <h2>Barang tidak ditemukan</h2>
                            <p class="lead">
                                <?php if ($search || $kategori) : ?>
                                    Coba ubah kata kunci pencarian atau filter kategori
                                <?php else : ?>
                                    Tidak ada barang yang tersedia saat ini
                                <?php endif; ?>
                            </p>
                            <?php if ($search || $kategori) : ?>
                                <a href="<?= base_url('user/katalog-barang') ?>" class="btn btn-primary mt-4">
                                    Lihat Semua Barang
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Pagination -->
            <?php if ($pagination) : ?>
                <div class="row">
                    <div class="col-12">
                        <?= $pagination ?>
                    </div>
                </div>
            <?php endif; ?>
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
                <img id="modalImage" src="" class="img-fluid" style="max-height: 500px;">
            </div>
        </div>
    </div>
</div>

<!-- Success Toast Template -->
<div id="success-toast" style="display: none;">
    <div class="toast" role="alert" aria-live="assertive" aria-atomic="true" style="position: fixed; top: 80px; right: 20px; z-index: 9999;">
        <div class="toast-header bg-success text-white">
            <strong class="mr-auto">Berhasil</strong>
            <button type="button" class="ml-2 mb-1 close text-white" data-dismiss="toast">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="toast-body">
            <span id="toast-message"></span>
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

    // Document ready
    $(document).ready(function() {
        // CSRF Token
        var csrfName = '<?= $this->security->get_csrf_token_name() ?>';
        var csrfHash = '<?= $this->security->get_csrf_hash() ?>';

        // Add to cart button click
        $('.btn-add-cart').on('click', function(e) {
            e.preventDefault();

            var btn = $(this);
            var id = btn.data('id');
            var nama = btn.data('nama');
            var qty = $('#qty-' + id).val();

            // Validate quantity
            if (qty < 1) {
                alert('Jumlah minimal 1');
                return;
            }

            // Disable button
            btn.prop('disabled', true);
            btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');

            // Prepare data
            var postData = {
                barang_id: id,
                jumlah: qty
            };
            postData[csrfName] = csrfHash;

            // AJAX request
            $.ajax({
                url: '<?= base_url('user/katalog-barang/add_to_cart') ?>',
                type: 'POST',
                data: postData,
                dataType: 'json',
                success: function(response) {
                    // Update CSRF token
                    csrfHash = response.csrfHash || csrfHash;

                    if (response.status == 'success') {
                        // Update cart badge
                        $('#cart-count').text(response.cart_count);
                        $('.cart-badge').text(response.cart_count);

                        // Show success message
                        showSuccessToast(response.message);

                        // Reset quantity input
                        $('#qty-' + id).val(1);

                        // Animate the button
                        btn.removeClass('btn-primary').addClass('btn-success');
                        btn.html('<i class="fas fa-check"></i> Ditambahkan');

                        setTimeout(function() {
                            btn.removeClass('btn-success').addClass('btn-primary');
                            btn.html('<i class="fas fa-plus"></i> Tambah');
                        }, 2000);
                    } else {
                        alert(response.message || 'Gagal menambahkan ke keranjang');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('AJAX Error:', error);
                    alert('Terjadi kesalahan. Silakan coba lagi.');
                },
                complete: function() {
                    // Re-enable button
                    setTimeout(function() {
                        btn.prop('disabled', false);
                    }, 2000);
                }
            });
        });

        // Show success toast
        function showSuccessToast(message) {
            // Using iziToast if available
            if (typeof iziToast !== 'undefined') {
                iziToast.success({
                    title: 'Berhasil',
                    message: message,
                    position: 'topRight',
                    timeout: 3000
                });
            } else {
                // Fallback to Bootstrap toast
                $('#toast-message').text(message);
                $('.toast').toast({
                    delay: 3000
                });
                $('.toast').toast('show');
            }
        }

        // Quantity input validation
        $('.qty-input').on('change', function() {
            var val = parseInt($(this).val());
            if (isNaN(val) || val < 1) {
                $(this).val(1);
            } else if (val > 999) {
                $(this).val(999);
            }
        });

        // Enter key on quantity input
        $('.qty-input').on('keypress', function(e) {
            if (e.which == 13) {
                e.preventDefault();
                $(this).closest('.article-cta').find('.btn-add-cart').click();
            }
        });
    });
</script>

<style>
    /* Custom styling for katalog */
    .article-style-b {
        height: 100%;
        border: 1px solid #e3eaef;
        border-radius: 5px;
        overflow: hidden;
        transition: transform 0.3s, box-shadow 0.3s;
    }

    .article-style-b:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .article-image {
        border-bottom: 1px solid #e3eaef;
    }

    .article-details {
        padding: 15px;
    }

    .article-title h6 {
        font-size: 14px;
        font-weight: 600;
        margin-bottom: 10px;
        line-height: 1.4;
        min-height: 40px;
    }

    .article-cta {
        margin-top: 15px;
    }

    .empty-state {
        padding: 60px 20px;
        text-align: center;
    }

    .empty-state-icon {
        width: 100px;
        height: 100px;
        line-height: 100px;
        font-size: 48px;
        border-radius: 50%;
        display: inline-block;
        color: #fff;
        margin-bottom: 20px;
    }

    .pagination {
        margin-top: 20px;
    }

    @media (max-width: 576px) {
        .article-style-b {
            margin-bottom: 20px;
        }
    }
</style>