<div class="main-content">
    <div class="card">
        <div class="card-header">
            <h4>Formulir Permintaan Barang</h4>
        </div>
        <div class="card-body">
            <form action="<?php echo base_url('Permintaan/simpan_barang'); ?>" method="post" enctype="multipart/form-data">
                <?= csrf_field(); ?>
                <div class="mb-2">
                    <label for="keterangan" class="form-label">Keterangan</label>
                    <textarea name="keterangan" id="keterangan" class="form-control"></textarea>
                </div>
                <div class="card bg-secondary">
                    <div class="card-header">
                        <h4>Daftar Permintaan Barang</h4>
                        <div class="card-header-action">
                            <button type="button" id="tambah-barang" class="btn btn-primary float-right mb-2">Tambah Barang</button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div id="barang-container">
                                <!-- Baris akan ditambahkan di sini -->
                            </div>
                        </div>

                        <!-- Template baris barang -->
                        <div class="row barang-row mb-2 d-none no-gutters" id="barang-template">
                            <div class="col-1 align-self-center label-baris text-center">1.</div>
                            <div class="col-3 pr-2">
                                <input type="text" class="form-control kode-input" name="kode[]" placeholder="Kode" readonly>
                                <input type="hidden" class="id-input" name="id_barang[]" value="">
                            </div>
                            <div class="col-4 pr-2">
                                <input type="text" class="form-control nama-input" name="nama[]" placeholder="Nama" readonly>
                            </div>
                            <div class="col-2 pr-2">
                                <input type="number" class="form-control" name="jumlah[]" placeholder="Jumlah">
                            </div>
                            <div class="col-2">
                                <div class="d-flex gap-1">
                                    <button type="button" class="btn btn-sm btn-primary pilih-barang mr-1">Pilih</button>
                                    <button type="button" class="btn btn-sm btn-danger hapus-barang">Hapus</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </div>
        <div class="card-footer">
            <button type="submit" class="btn btn-success float-right">Ajukan / Simpan</button>
            <a href="<?= base_url("Permintaan") ?>" class="btn btn-secondary float-right mr-2">Kembali</a>
        </div>
    </div>
    </form>
</div>

<div class="modal fade" id="modelId" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih barang yang ingin ditambahkan.</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group row align-items-end mb-4">
                    <div class="col">
                        <select name="parent_kode" id="parentSelect" class="form-control">
                            <option value="" selected disabled>Pilih data filter</option>
                            <?php foreach ($data_filter as $df) : ?>
                                <option value="<?= $df->KODE_REK ?>"><?= $df->NAMA ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col">
                        <select name="child_kode" id="childSelect" class="form-control">
                            <!-- Diisi oleh ajax sesuai kode diatas -->
                        </select>
                    </div>
                </div>
                <hr>

                <!-- Tombol Select All dan Clear All -->
                <div class="mb-3">
                    <button type="button" class="btn btn-sm btn-primary" id="selectAll">Pilih Semua</button>
                    <button type="button" class="btn btn-sm btn-danger" id="clearAll">Hapus Semua</button>
                    <span class="ml-3 text-muted">
                        <span id="selectedCount">0</span> item dipilih
                    </span>
                </div>

                <table class="table" width="100%" id="dataTable">
                    <thead>
                        <tr>
                            <th width="8%" class="text-center align-middle">
                                #
                            </th>
                            <th width="20%" class="align-middle">KODE</th>
                            <th width="40%" class="align-middle">NAMA BARANG</th>
                            <th width="32%" class="align-middle text-center">GAMBAR</th>
                        </tr>
                    </thead>
                    <tbody>

                    </tbody>
                </table>
            </div>
            <div class="modal-footer">
                <span class="float-left">* Centang item yang akan ditambahkan.</span>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="btnPilihBarang">
                    Pilih <span id="footerSelectedCount">0</span> Item
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar besar -->
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true" data-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Preview" class="img-fluid" style="max-height: 70vh;">
                <div class="mt-3">
                    <h6 id="modalImageTitle"></h6>
                    <p class="text-muted" id="modalImageCode"></p>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>

<script>
    $(document).ready(function() {
        let barisKe = 0;
        let table;
        let selectedItems = [];
        let currentParentFilter = '';
        let currentChildFilter = '';
        let currentTableData = [];

        function updateLabelBaris() {
            $('#barang-container .barang-row').each(function(index) {
                $(this).find('.label-baris').text((index + 1) + '.');
            });
        }

        function updateSelectedCount() {
            const count = selectedItems.length;
            $('#selectedCount').text(count);
            $('#footerSelectedCount').text(count);
            $('#btnPilihBarang').prop('disabled', count === 0);
        }

        // Function untuk menyimpan state modal
        function saveModalState() {
            currentParentFilter = $('#parentSelect').val();
            currentChildFilter = $('#childSelect').val();
            currentTableData = table.data().toArray();
        }

        // Function untuk restore state modal
        function restoreModalState() {
            if (currentParentFilter) {
                $('#parentSelect').val(currentParentFilter);

                // Load child options jika parent sudah dipilih
                if (currentParentFilter !== '') {
                    $.ajax({
                        url: '<?= base_url('Master/getFilter') ?>',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            kode: currentParentFilter,
                        },
                        success: function(response) {
                            var options = '<option value="" selected disabled>Pilih Sub Data</option>';
                            if (response.data_filter.length > 0) {
                                $.each(response.data_filter, function(index, item) {
                                    const selected = item.KODE_REK === currentChildFilter ? 'selected' : '';
                                    options += '<option value="' + item.KODE_REK + '" ' + selected + '>' + item.NAMA + '</option>';
                                });
                            } else {
                                options = '<option value="">Tidak ada data</option>';
                            }

                            $('#childSelect').html(options);

                            // Restore table data jika ada
                            if (currentTableData.length > 0) {
                                table.clear().rows.add(currentTableData).draw();
                            }
                        }
                    });
                }
            }
        }

        // Function untuk menampilkan gambar dengan fallback
        function getImageHtml(gambar, kode, nama) {
            const baseUrl = '<?= base_url("assets/img/gambar/") ?>';

            if (!gambar || gambar.trim() === '') {
                return `
                    <div class="text-center">
                        <div class="border rounded p-3 bg-light" style="width: 80px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;">
                            <small class="text-muted">Tidak ada gambar</small>
                        </div>
                    </div>
                `;
            }

            const imagePath = baseUrl + gambar;

            return `
                <div class="text-center">
                    <img src="${imagePath}" 
                         alt="${nama}" 
                         class="img-thumbnail gambar-preview" 
                         style="width: 80px; height: 60px; object-fit: cover; cursor: pointer;"
                         data-kode="${kode}"
                         data-nama="${nama}"
                         data-gambar="${gambar}"
                         onerror="this.parentElement.innerHTML='<div class=\\'border rounded p-3 bg-light\\' style=\\'width: 80px; height: 60px; display: flex; align-items: center; justify-content: center; margin: 0 auto;\\'><small class=\\'text-muted\\'>Tidak ada gambar</small></div>';">
                    <br>
                    <small class="text-muted">Klik untuk perbesar</small>
                </div>
            `;
        }

        // Initialize DataTable
        table = $('#dataTable').DataTable({
            data: [],
            columns: [{
                    data: null,
                    render: function(data, type, row) {
                        const isChecked = selectedItems.some(item => item.ID === row.ID) ? 'checked' : '';
                        return `
                    <div class="form-check text-center m-0">
                        <input type="checkbox" class="form-check-input item-checkbox"
                               data-id="${row.ID}"
                               data-kode="${row.KODE}"
                               data-nama="${row.NAMA_BARANG}"
                               data-gambar="${row.GAMBAR}"
                               ${isChecked}>
                    </div>
                `;
                    },
                    orderable: false,
                    searchable: false
                },
                {
                    data: 'KODE'
                },
                {
                    data: 'NAMA_BARANG'
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        return getImageHtml(row.GAMBAR, row.KODE, row.NAMA_BARANG);
                    },
                    orderable: false,
                    searchable: false
                }
            ],
            pageLength: 10,
            responsive: true
        });

        // Event: Klik gambar untuk menampilkan popup
        $('#dataTable tbody').on('click', '.gambar-preview', function() {
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const gambar = $(this).data('gambar');
            const baseUrl = '<?= base_url("assets/img/gambar/") ?>';
            const imagePath = baseUrl + gambar;

            // Simpan state modal sebelum ditutup
            saveModalState();

            // Simpan referensi modal utama
            const mainModal = $('#modelId');

            $('#modalImage').attr('src', imagePath);
            $('#modalImageTitle').text(nama);
            $('#modalImageCode').text('Kode: ' + kode);

            // Tutup modal utama sementara, tampilkan modal gambar
            mainModal.modal('hide');

            // Tunggu sebentar agar transisi modal selesai, lalu buka modal gambar
            setTimeout(function() {
                $('#imageModal').modal('show');
            }, 300);
        });

        // Event: Ketika modal gambar ditutup, buka kembali modal utama
        $('#imageModal').on('hidden.bs.modal', function() {
            // Buka kembali modal utama
            setTimeout(function() {
                $('#modelId').modal('show');
                // Restore state modal setelah dibuka
                setTimeout(function() {
                    restoreModalState();
                }, 100);
            }, 100);
        });

        // Event: Tambah barang
        $('#tambah-barang').on('click', function() {
            barisKe++;
            let newRow = $('#barang-template').clone().removeClass('d-none').removeAttr('id');
            newRow.find('input').val('');
            $('#barang-container').append(newRow);
            updateLabelBaris();
        });

        // Event: Hapus barang
        $('#barang-container').on('click', '.hapus-barang', function() {
            $(this).closest('.barang-row').remove();
            updateLabelBaris();
        });

        // Event: Pilih barang (buka modal)
        $('#barang-container').on('click', '.pilih-barang', function() {
            selectedItems = []; // Reset selected items
            updateSelectedCount();
            $('#modelId').modal('show');
        });

        // Event: Parent select change
        $('#parentSelect').on('change', function() {
            var kode = $(this).val();
            currentParentFilter = kode; // Simpan filter parent saat ini

            $('#childSelect').html('<option value="">Loading...</option>');

            $.ajax({
                url: '<?= base_url('Master/getFilter') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    kode: kode,
                },
                success: function(response) {
                    var options = '<option value="" selected disabled>Pilih Sub Data</option>';
                    if (response.data_filter.length > 0) {
                        $.each(response.data_filter, function(index, item) {
                            options += '<option value="' + item.KODE_REK + '">' + item.NAMA + '</option>';
                        });
                    } else {
                        options = '<option value="">Tidak ada data</option>';
                    }

                    $('#childSelect').html(options);
                    // Reset child filter dan table data saat parent berubah
                    currentChildFilter = '';
                    currentTableData = [];
                },
                error: function() {
                    $('#childSelect').html('<option value="">Gagal mengambil data</option>');
                }
            });
        });

        // Event: Child select change
        $('#childSelect').on('change', function() {
            var kode = $(this).val();
            currentChildFilter = kode; // Simpan filter saat ini
            $.ajax({
                url: '<?= base_url('Master/getData') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    kode: kode
                },
                success: function(response) {
                    if (response.data && response.data.length > 0) {
                        currentTableData = response.data; // Simpan data table
                        table.clear().rows.add(response.data).draw();
                    } else {
                        currentTableData = []; // Reset data jika kosong
                        table.clear().draw();
                    }
                },
                error: function() {
                    alert('Gagal mengambil data');
                }
            });
        });

        // Event: Checkbox individual
        $('#dataTable tbody').on('change', '.item-checkbox', function() {
            const id = $(this).data('id');
            const kode = $(this).data('kode');
            const nama = $(this).data('nama');
            const gambar = $(this).data('gambar');

            if ($(this).is(':checked')) {
                // Tambah ke selected items jika belum ada
                if (!selectedItems.some(item => item.ID === id)) {
                    selectedItems.push({
                        ID: id,
                        KODE: kode,
                        NAMA_BARANG: nama,
                        GAMBAR: gambar
                    });
                }
            } else {
                // Hapus dari selected items
                selectedItems = selectedItems.filter(item => item.ID !== id);
            }

            updateSelectedCount();
            updateCheckboxAll();
        });

        // Event: Checkbox all
        $('#checkboxAll').on('change', function() {
            const isChecked = $(this).is(':checked');

            $('.item-checkbox').each(function() {
                const id = $(this).data('id');
                const kode = $(this).data('kode');
                const nama = $(this).data('nama');
                const gambar = $(this).data('gambar');

                $(this).prop('checked', isChecked);

                if (isChecked) {
                    if (!selectedItems.some(item => item.ID === id)) {
                        selectedItems.push({
                            ID: id,
                            KODE: kode,
                            NAMA_BARANG: nama,
                            GAMBAR: gambar
                        });
                    }
                } else {
                    selectedItems = selectedItems.filter(item => item.ID !== id);
                }
            });

            updateSelectedCount();
        });

        // Function: Update checkbox all status
        function updateCheckboxAll() {
            const totalCheckboxes = $('.item-checkbox').length;
            const checkedCheckboxes = $('.item-checkbox:checked').length;

            if (totalCheckboxes === 0) {
                $('#checkboxAll').prop('indeterminate', false).prop('checked', false);
            } else if (checkedCheckboxes === totalCheckboxes) {
                $('#checkboxAll').prop('indeterminate', false).prop('checked', true);
            } else if (checkedCheckboxes > 0) {
                $('#checkboxAll').prop('indeterminate', true);
            } else {
                $('#checkboxAll').prop('indeterminate', false).prop('checked', false);
            }
        }

        // Event: Select All button
        $('#selectAll').on('click', function() {
            $('#checkboxAll').prop('checked', true).trigger('change');
        });

        // Event: Clear All button
        $('#clearAll').on('click', function() {
            $('#checkboxAll').prop('checked', false).trigger('change');
        });

        // Event: Pilih barang dari modal
        $('#btnPilihBarang').on('click', function() {
            if (selectedItems.length === 0) {
                alert('Silakan pilih minimal satu item.');
                return;
            }

            // Hapus baris kosong jika ada
            $('#barang-container .barang-row').each(function() {
                const kodeValue = $(this).find('.kode-input').val();
                const namaValue = $(this).find('.nama-input').val();
                if (!kodeValue && !namaValue) {
                    $(this).remove();
                }
            });

            // Tambahkan baris baru untuk setiap item yang dipilih
            selectedItems.forEach(function(item) {
                barisKe++;
                let newRow = $('#barang-template').clone().removeClass('d-none').removeAttr('id');
                newRow.find('.kode-input').val(item.KODE);
                newRow.find('.nama-input').val(item.NAMA_BARANG);
                newRow.find('.id-input').val(item.ID); // Set ID barang
                newRow.find('input[name="jumlah[]"]').val(1); // Default jumlah 1
                $('#barang-container').append(newRow);
            });

            updateLabelBaris();
            selectedItems = [];
            updateSelectedCount();
            $('#modelId').modal('hide');
        });

        // Event: Modal hidden - reset
        $('#modelId').on('hidden.bs.modal', function() {
            selectedItems = [];
            updateSelectedCount();
            table.clear().draw();
            $('#parentSelect').val('');
            $('#childSelect').html('<option value="" selected disabled>Pilih Sub Data</option>');
        });

        // Auto-trigger untuk demo
        $('#tambah-barang').click();

        // Update checkbox all saat table di-redraw
        table.on('draw', function() {
            updateCheckboxAll();
        });

        // Error handling untuk gambar yang gagal dimuat sudah ditangani di function getImageHtml
    });
</script>