<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/barang-keluar') ?>">Barang Keluar</a></div>
                <div class="breadcrumb-item">Tambah</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <form action="<?= base_url('admin/barang-keluar/simpan') ?>" method="POST">
                        <?= csrf_field() ?>
                        <div class="card">
                            <div class="card-header">
                                <h4>Form Barang Keluar</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Tanggal Keluar</label>
                                            <input type="date" name="tanggal_keluar" class="form-control" value="<?= date('Y-m-d') ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Unit/Bagian</label>
                                            <select name="unit_id" class="form-control select2">
                                                <option value="">-- Pilih Unit --</option>
                                                <?php foreach ($unit as $u) : ?>
                                                    <option value="<?= $u->id ?>"><?= $u->nama_unit ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>Jenis Keluar</label>
                                            <select name="jenis_keluar" class="form-control" required>
                                                <option value="pemakaian">Pemakaian</option>
                                                <option value="retur_ke_supplier">Retur ke Supplier</option>
                                                <option value="rusak">Rusak</option>
                                                <option value="koreksi_minus">Koreksi Minus</option>
                                                <option value="lainnya">Lainnya</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label>No. Permintaan</label>
                                            <input type="text" name="nomor_permintaan" class="form-control" placeholder="Nomor Surat Permintaan">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Nama Penerima</label>
                                            <input type="text" name="nama_penerima" class="form-control" placeholder="Nama Penerima Barang">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label>Keterangan</label>
                                            <textarea name="keterangan" class="form-control" rows="2"></textarea>
                                        </div>
                                    </div>
                                </div>

                                <h5 class="mt-4 mb-3">Detail Barang</h5>

                                <div class="alert alert-info">
                                    <strong>Tips:</strong> Ketik minimal 2 karakter untuk mencari barang berdasarkan kode atau nama
                                </div>

                                <div class="table-responsive">
                                    <table class="table table-bordered" id="table-detail">
                                        <thead>
                                            <tr>
                                                <th width="35%">Barang</th>
                                                <th width="10%">Satuan</th>
                                                <th width="10%">Stok Saat Ini</th>
                                                <th width="10%">Jumlah Keluar</th>
                                                <th width="15%">Harga Satuan</th>
                                                <th width="15%">Subtotal</th>
                                                <th width="5%">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody id="detail-container">
                                            <tr class="detail-row">
                                                <td>
                                                    <select name="barang_id[]" class="form-control barang-select" style="width: 100%" required>
                                                        <option value="">-- Cari Barang --</option>
                                                    </select>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control satuan-text" readonly>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control stok-saat-ini" readonly>
                                                </td>
                                                <td>
                                                    <input type="number" name="jumlah[]" class="form-control jumlah" min="1" required>
                                                </td>
                                                <td>
                                                    <input type="number" name="harga_satuan[]" class="form-control harga" min="0" required>
                                                </td>
                                                <td>
                                                    <input type="text" class="form-control subtotal" readonly>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-danger btn-sm btn-remove-row">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <td colspan="5" class="text-right"><strong>Total:</strong></td>
                                                <td><strong id="total-all">Rp 0</strong></td>
                                                <td>
                                                    <button type="button" class="btn btn-success btn-sm" id="btn-add-row">
                                                        <i class="fas fa-plus"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                            <div class="card-footer text-right">
                                <a href="<?= base_url('admin/barang-keluar') ?>" class="btn btn-secondary">Batal</a>
                                <button type="submit" class="btn btn-primary" id="btn-submit">Simpan</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>

<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .select2-container--default .select2-selection--single {
        height: 38px;
        border: 1px solid #ced4da;
        border-radius: 0.375rem;
    }

    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px;
        color: #495057;
    }

    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px;
    }

    .select2-dropdown {
        border: 1px solid #ced4da;
    }
</style>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<!-- Perbaikan untuk JavaScript di view -->
<script>
    $(document).ready(function() {
        // Initialize first select2
        setTimeout(function() {
            initializeSelect2($('.barang-select'));
        }, 100);

        // Function to initialize Select2 with AJAX
        function initializeSelect2(element) {
            // Destroy existing select2 if it exists
            if (element.hasClass("select2-hidden-accessible")) {
                element.select2('destroy');
            }

            element.select2({
                placeholder: '-- Cari Barang --',
                minimumInputLength: 2,
                allowClear: true,
                ajax: {
                    url: '<?= base_url('admin/barang_keluar/search_barang') ?>',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data, params) {
                        params.page = params.page || 1;

                        if (data && data.results) {
                            return {
                                results: data.results,
                                pagination: data.pagination || {
                                    more: false
                                }
                            };
                        } else {
                            return {
                                results: [],
                                pagination: {
                                    more: false
                                }
                            };
                        }
                    },
                    cache: true
                },
                templateResult: formatBarang,
                templateSelection: formatBarangSelection,
                language: {
                    searching: function() {
                        return "Mencari...";
                    },
                    noResults: function() {
                        return "Barang tidak ditemukan";
                    },
                    inputTooShort: function(args) {
                        var remainingChars = args.minimum - args.input.length;
                        return 'Ketik ' + remainingChars + ' karakter lagi untuk mencari';
                    },
                    errorLoading: function() {
                        return "Gagal memuat data";
                    }
                }
            });
        }

        // Format display in dropdown
        function formatBarang(barang) {
            if (barang.loading) {
                return barang.text;
            }

            if (!barang.id) {
                return barang.text || 'Data tidak lengkap';
            }

            var $container = $(
                "<div class='select2-result-barang clearfix'>" +
                "<div class='select2-result-barang__title'></div>" +
                "<div class='select2-result-barang__meta'>" +
                "<span class='select2-result-barang__kode'></span>" +
                "<span class='select2-result-barang__stok'></span>" +
                "</div>" +
                "</div>"
            );

            $container.find(".select2-result-barang__title").text(barang.nama_nusp + ' - ' + barang.nama_gudang || barang.text || 'Nama tidak tersedia');
            $container.find(".select2-result-barang__kode").html("<i class='fas fa-barcode'></i> " + (barang.kode_nusp || 'Kode tidak tersedia'));
            $container.find(".select2-result-barang__stok").html(" <i class='fas fa-box'></i> Stok: " + (barang.stok_saat_ini || 0));

            return $container;
        }

        // Format selected item
        function formatBarangSelection(barang) {
            return barang.nama_gudang || barang.nama_nusp || '-- Pilih Barang --';
        }

        // Add new row
        $('#btn-add-row').click(function() {
            var newRow = $('.detail-row:first').clone();

            // Clear all inputs
            newRow.find('input').val('');

            // Reset select element
            var selectElement = newRow.find('.barang-select');
            selectElement.empty().append('<option value="">-- Cari Barang --</option>');

            // Remove any select2 classes and data attributes
            selectElement.removeClass('select2-hidden-accessible')
                .removeAttr('data-select2-id')
                .removeAttr('aria-hidden')
                .removeAttr('tabindex');

            // Remove any generated select2 containers
            newRow.find('.select2-container').remove();

            // Append new row to container
            newRow.appendTo('#detail-container');

            // Initialize Select2 for the new row only
            initializeSelect2(selectElement);
        });

        // Remove row
        $(document).on('click', '.btn-remove-row', function() {
            if ($('.detail-row').length > 1) {
                var row = $(this).closest('tr');
                var selectElement = row.find('.barang-select');

                // Properly destroy select2
                if (selectElement.hasClass("select2-hidden-accessible")) {
                    selectElement.select2('destroy');
                }

                row.remove();
                calculateTotal();
            } else {
                Swal.fire('Peringatan', 'Minimal harus ada satu baris detail', 'warning');
            }
        });

        // On barang change - get detail and auto-fill
        $(document).on('select2:select', '.barang-select', function(e) {
            var data = e.params.data;
            var row = $(this).closest('tr');

            // Auto-fill dari data yang sudah ada di select2
            row.find('.satuan-text').val(data.satuan || '');
            row.find('.stok-saat-ini').val(data.stok_saat_ini || 0);
            row.find('.harga').val(data.harga_terakhir || 0);

            // Calculate subtotal after filling data
            calculateSubtotal(row);

            // Get more detailed info via AJAX jika diperlukan
            if (data.id) {
                $.ajax({
                    url: '<?= base_url('admin/barang_keluar/get_barang_detail/') ?>' + data.id,
                    type: 'GET',
                    dataType: 'json',
                    success: function(response) {
                        if (response.status === 'success') {
                            row.find('.satuan-text').val(response.data.satuan);
                            row.find('.stok-saat-ini').val(response.data.stok_saat_ini);

                            // Set harga terakhir sebagai default jika belum ada
                            if (response.data.harga_terakhir > 0 && !row.find('.harga').val()) {
                                row.find('.harga').val(response.data.harga_terakhir);
                            }

                            calculateSubtotal(row);
                        }
                    }
                });
            }
        });

        // Calculate on input change
        $(document).on('input', '.jumlah, .harga', function() {
            var row = $(this).closest('tr');
            calculateSubtotal(row);
        });

        function calculateSubtotal(row) {
            var jumlah = parseFloat(row.find('.jumlah').val()) || 0;
            var harga = parseFloat(row.find('.harga').val()) || 0;
            var subtotal = jumlah * harga;

            row.find('.subtotal').val('Rp ' + formatNumber(subtotal));
            calculateTotal();
        }

        function calculateTotal() {
            var total = 0;
            $('.detail-row').each(function() {
                var jumlah = parseFloat($(this).find('.jumlah').val()) || 0;
                var harga = parseFloat($(this).find('.harga').val()) || 0;
                total += (jumlah * harga);
            });
            $('#total-all').text('Rp ' + formatNumber(total));
        }

        function formatNumber(num) {
            return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
        }

        // Form validation before submit
        $('#form-barang-masuk').on('submit', function(e) {
            var valid = true;
            var hasDetail = false;

            $('.detail-row').each(function() {
                var barang = $(this).find('.barang-select').val();
                var jumlah = $(this).find('.jumlah').val();
                var harga = $(this).find('.harga').val();

                if (barang && jumlah && harga) {
                    hasDetail = true;
                }
            });

            if (!hasDetail) {
                e.preventDefault();
                Swal.fire('Peringatan', 'Minimal harus ada satu detail barang yang diisi lengkap', 'warning');
                return false;
            }

            // Show loading
            $('#btn-submit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');
        });
    });
</script>