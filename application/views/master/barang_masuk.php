<div class="main-content">
    <form action="<?= base_url('Master') ?>" method="post" id="masterForm">
        <input type="hidden" id="csrf_token_name" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">

        <div class="card">
            <div class="card-body">
                <div class="form-group">
                    <label for="penyedia">Penyedia</label>
                    <input type="text" name="penyedia" id="penyedia" class="form-control" required>
                </div>
                <div class="row">
                    <div class="col">
                        <div class="form-group">
                            <label for="no_kwitansi">No Kwitansi</label>
                            <input type="text" name="no_kwitansi" id="no_kwitansi" class="form-control" required>
                        </div>
                    </div>
                    <div class="col">
                        <div class="form-group">
                            <label for="no_bast">No Bast</label>
                            <input type="text" name="no_bast" id="no_bast" class="form-control" required>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-body">
                <table class="table table-bordered" id="barangTable">
                    <thead>
                        <tr>
                            <th width="7%">NO</th>
                            <th width="35%">BARANG</th>
                            <th width="20%">KODE</th>
                            <th width="15%">JUMLAH</th>
                            <th width="15%">HARGA</th>
                            <th width="15%">GAMBAR</th>
                            <th width="7%">AKSI</th>
                        </tr>
                    </thead>
                    <tbody id="tableBody">
                        <tr data-row="1">
                            <td class="text-center row-number" style="vertical-align: middle;">1</td>
                            <td>
                                <select name="id_barang[]" class="form-control barang-select" data-row="1" required>
                                    <option value="" selected disabled>Ketik untuk mencari barang...</option>
                                </select>
                            </td>
                            <td>
                                <input type="text" name="kode[]" class="form-control kode-input" data-row="1" readonly>
                            </td>
                            <td>
                                <input type="number" name="jumlah[]" class="form-control jumlah-input" data-row="1" min="1" required>
                            </td>
                            <td>
                                <input type="number" name="harga[]" class="form-control harga-input" data-row="1" min="0" step="0.01" required>
                            </td>
                            <td>
                                <div class="custom-file">
                                    <input type="file" class="custom-file-input" id="inputGroupFile01" aria-describedby="inputGroupFileAddon01">
                                    <label class="custom-file-label" for="inputGroupFile01"></label>
                                </div>
                            </td>
                            <td class="text-center">
                                <button type="button" class="btn btn-danger btn-sm remove-row" data-row="1" disabled>
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6" class="text-center">
                                <button type="button" class="btn btn-primary btn-sm" id="addRowBtn">
                                    <i class="fas fa-plus"></i> Tambah Baris
                                </button>
                            </td>
                        </tr>
                    </tfoot>
                </table>

                <div class="mt-3 float-right">
                    <button type="reset" class="btn btn-secondary">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save"></i> Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<!-- CSS untuk Select2 -->
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

<!-- JavaScript -->
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>

<script>
    $(document).ready(function() {
        let rowCounter = 1;

        // Initialize first select2 - pastikan element sudah ada
        setTimeout(function() {
            initializeSelect2($('.barang-select[data-row="1"]'));
        }, 100);

        function initializeSelect2(element) {
            // Destroy existing select2 jika ada
            if (element.hasClass("select2-hidden-accessible")) {
                element.select2('destroy');
            }

            element.select2({
                placeholder: 'Ketik untuk mencari barang...',
                allowClear: true,
                minimumInputLength: 2,
                ajax: {
                    url: '<?= base_url("Master/search_barang") ?>', // Ganti YourController dengan nama controller Anda
                    dataType: 'json',
                    delay: 300,
                    data: function(params) {
                        return {
                            search: params.term,
                            page: params.page || 1
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data.results || [],
                            pagination: {
                                more: data.pagination ? data.pagination.more : false
                            }
                        };
                    },
                    cache: true
                },
                templateResult: function(repo) {
                    if (repo.loading) {
                        return repo.text;
                    }
                    // Template untuk menampilkan hasil pencarian - disesuaikan dengan response API Anda
                    var template = $('<div class="select2-result-repository clearfix">' +
                        '<div class="select2-result-repository__title"></div>' +
                        '<div class="select2-result-repository__description"></div>' +
                        '</div>');

                    template.find('.select2-result-repository__title').text(repo.nama || repo.text);
                    template.find('.select2-result-repository__description').html('<small>Kode: <strong>' + (repo.kode || '') + '</strong></small>');

                    return template;
                },
                templateSelection: function(repo) {
                    return repo.nama || repo.text;
                }
            });
        }

        // Handle barang selection change
        $(document).on('change', '.barang-select', function() {
            let row = $(this).data('row');
            let selectedData = $(this).select2('data')[0];

            if (selectedData && selectedData.id) {
                // Auto-fill kode (hanya kode yang diambil)
                $(`.kode-input[data-row="${row}"]`).val(selectedData.kode || '');

                // Focus ke input jumlah
                $(`.jumlah-input[data-row="${row}"]`).focus();
            } else {
                // Clear fields if no selection
                $(`.kode-input[data-row="${row}"]`).val('');
            }
        });

        // Add new row
        $('#addRowBtn').click(function() {
            rowCounter++;
            let newRow = `
            <tr data-row="${rowCounter}">
                <td class="text-center row-number" style="vertical-align: middle;">${rowCounter}</td>
                <td>
                    <select name="id_barang[]" class="form-control barang-select" data-row="${rowCounter}" required>
                        <option value="" selected disabled>Ketik untuk mencari barang...</option>
                    </select>
                </td>
                <td>
                    <input type="text" name="kode[]" class="form-control kode-input" data-row="${rowCounter}" readonly>
                </td>
                <td>
                    <input type="number" name="jumlah[]" class="form-control jumlah-input" data-row="${rowCounter}" min="1" required>
                </td>
                <td>
                    <input type="number" name="harga[]" class="form-control harga-input" data-row="${rowCounter}" min="0" step="0.01" required>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm remove-row" data-row="${rowCounter}">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>
        `;

            $('#tableBody').append(newRow);

            // Initialize Select2 untuk baris baru dengan delay
            setTimeout(function() {
                initializeSelect2($(`.barang-select[data-row="${rowCounter}"]`));
            }, 100);

            // Update state tombol hapus
            updateRemoveButtons();
        });

        // Remove row
        $(document).on('click', '.remove-row', function() {
            let rowToRemove = $(this).data('row');
            $(`tr[data-row="${rowToRemove}"]`).remove();

            // Renumber rows
            renumberRows();
            updateRemoveButtons();
        });

        // Function untuk renumber rows
        function renumberRows() {
            $('#tableBody tr').each(function(index) {
                let newRowNumber = index + 1;
                $(this).find('.row-number').text(newRowNumber);
            });
        }

        // Function untuk update state tombol hapus
        function updateRemoveButtons() {
            let totalRows = $('#tableBody tr').length;
            if (totalRows <= 1) {
                $('.remove-row').prop('disabled', true);
            } else {
                $('.remove-row').prop('disabled', false);
            }
        }

        // Form validation sebelum submit
        $('#masterForm').submit(function(e) {
            let isValid = true;
            let errorMessage = '';

            // Validasi field utama
            if (!$('#penyedia').val().trim()) {
                isValid = false;
                errorMessage += '- Penyedia harus diisi\n';
            }

            if (!$('#no_kwitansi').val().trim()) {
                isValid = false;
                errorMessage += '- No Kwitansi harus diisi\n';
            }

            if (!$('#no_bast').val().trim()) {
                isValid = false;
                errorMessage += '- No BAST harus diisi\n';
            }

            // Validasi minimal ada 1 barang
            let hasBarang = false;
            $('.barang-select').each(function() {
                if ($(this).val()) {
                    hasBarang = true;
                }
            });

            if (!hasBarang) {
                isValid = false;
                errorMessage += '- Minimal harus ada 1 barang yang dipilih\n';
            }

            // Validasi setiap baris yang diisi
            $('.barang-select').each(function() {
                if ($(this).val()) {
                    let row = $(this).data('row');
                    let jumlah = $(`.jumlah-input[data-row="${row}"]`).val();
                    let harga = $(`.harga-input[data-row="${row}"]`).val();

                    if (!jumlah || jumlah <= 0) {
                        isValid = false;
                        errorMessage += `- Jumlah pada baris ${row} harus diisi dan lebih dari 0\n`;
                    }

                    if (!harga || harga < 0) {
                        isValid = false;
                        errorMessage += `- Harga pada baris ${row} harus diisi dan tidak boleh negatif\n`;
                    }
                }
            });

            if (!isValid) {
                e.preventDefault();
                alert('Mohon perbaiki kesalahan berikut:\n\n' + errorMessage);
                return false;
            }

            return true;
        });

        // Reset form
        $('button[type="reset"]').click(function() {
            if (confirm('Yakin ingin mereset semua data?')) {
                // Destroy semua select2 terlebih dahulu
                $('.barang-select').each(function() {
                    if ($(this).hasClass("select2-hidden-accessible")) {
                        $(this).select2('destroy');
                    }
                });

                // Reset semua input
                $('input[type="text"], input[type="number"]').val('');

                // Reset ke 1 baris saja
                $('#tableBody tr:not(:first)').remove();
                rowCounter = 1;

                // Re-initialize select2 untuk baris pertama
                setTimeout(function() {
                    initializeSelect2($('.barang-select[data-row="1"]'));
                }, 100);

                updateRemoveButtons();
            }
        });

        // Initialize remove button state
        updateRemoveButtons();

        // Force re-initialize jika ada masalah
        setTimeout(function() {
            if (!$('.barang-select[data-row="1"]').hasClass("select2-hidden-accessible")) {
                console.log('Re-initializing first select2...');
                initializeSelect2($('.barang-select[data-row="1"]'));
            }
        }, 500);
    });
</script>

<!-- Include datatables jika diperlukan -->
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>