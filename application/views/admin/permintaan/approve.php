<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Proses Permintaan</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item"><a href="<?= base_url('admin/permintaan') ?>">Permintaan</a></div>
                <div class="breadcrumb-item">Proses</div>
            </div>
        </div>

        <div class="section-body">
            <form action="<?= base_url('admin/permintaan/process_approve') ?>" method="POST" id="form-approve">
                <?= csrf_field() ?>
                <input type="hidden" name="permintaan_id" value="<?= $permintaan->id ?>">

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Informasi Permintaan</h4>
                            </div>
                            <div class="card-body">
                                <div class="row">
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="150"><strong>No. Permintaan</strong></td>
                                                <td>: <?= $permintaan->nomor_permintaan ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Tanggal</strong></td>
                                                <td>: <?= date('d F Y', strtotime($permintaan->tanggal_permintaan)) ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Pemohon</strong></td>
                                                <td>: <?= $permintaan->nama_user ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                    <div class="col-md-6">
                                        <table class="table table-sm">
                                            <tr>
                                                <td width="150"><strong>Keperluan</strong></td>
                                                <td>: <?= $permintaan->keperluan ?></td>
                                            </tr>
                                            <tr>
                                                <td><strong>Keterangan</strong></td>
                                                <td>: <?= $permintaan->keterangan ?: '-' ?></td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h4>Detail Barang</h4>
                                <div class="card-header-action">
                                    <div class="btn-group">
                                        <button type="button" class="btn btn-success btn-sm" onclick="approveAll()">
                                            <i class="fas fa-check"></i> Setujui Semua
                                        </button>
                                        <button type="button" class="btn btn-danger btn-sm" onclick="rejectAll()">
                                            <i class="fas fa-times"></i> Tolak Semua
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-bordered">
                                        <thead>
                                            <tr>
                                                <th width="40">#</th>
                                                <th>Kode Barang</th>
                                                <th>Nama Barang</th>
                                                <th width="80">Satuan</th>
                                                <th width="100">Stok Tersedia</th>
                                                <th width="100">Qty Diminta</th>
                                                <th width="120">Qty Disetujui</th>
                                                <th width="200">Keterangan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $no = 1;
                                            foreach ($detail as $d) : ?>
                                                <tr>
                                                    <td><?= $no++ ?></td>
                                                    <td><?= $d->kode_nusp ?></td>
                                                    <td><?= $d->nama_nusp ?></td>
                                                    <td class="text-center"><?= $d->satuan ?></td>
                                                    <td class="text-center">
                                                        <?php if ($d->stok_tersedia > 0) : ?>
                                                            <span class="badge badge-success"><?= $d->stok_tersedia ?></span>
                                                        <?php else : ?>
                                                            <span class="badge badge-danger">0</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="text-center">
                                                        <strong><?= $d->jumlah_diminta ?></strong>
                                                    </td>
                                                    <td>
                                                        <input type="hidden" name="detail_id[]" value="<?= $d->id ?>">
                                                        <input type="number" name="jumlah_disetujui[]" class="form-control form-control-sm qty-approve" value="<?= min($d->jumlah_diminta, $d->stok_tersedia) ?>" min="0" max="<?= min($d->jumlah_diminta, $d->stok_tersedia) ?>" data-requested="<?= $d->jumlah_diminta ?>" data-available="<?= $d->stok_tersedia ?>">
                                                    </td>
                                                    <td>
                                                        <input type="text" name="keterangan[]" class="form-control form-control-sm" placeholder="Keterangan (opsional)">
                                                    </td>
                                                </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="alert alert-info mt-3">
                                    <strong>Informasi:</strong>
                                    <ul class="mb-0">
                                        <li>Qty disetujui tidak bisa melebihi stok tersedia</li>
                                        <li>Isi 0 untuk menolak item tertentu</li>
                                        <li>Keterangan opsional untuk catatan tambahan</li>
                                    </ul>
                                </div>
                            </div>
                            <div class="card-footer">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="custom-control custom-checkbox">
                                            <input type="checkbox" class="custom-control-input" id="create_barang_keluar" name="create_barang_keluar" value="1" checked>
                                            <label class="custom-control-label" for="create_barang_keluar">
                                                Buat transaksi barang keluar otomatis
                                            </label>
                                        </div>
                                    </div>
                                    <div class="col-md-6 text-right">
                                        <a href="<?= base_url('admin/permintaan') ?>" class="btn btn-secondary">
                                            <i class="fas fa-arrow-left"></i> Kembali
                                        </a>
                                        <button type="button" class="btn btn-danger" onclick="rejectRequest()">
                                            <i class="fas fa-times"></i> Tolak Permintaan
                                        </button>
                                        <button type="submit" name="action" value="approve" class="btn btn-success">
                                            <i class="fas fa-check"></i> Proses Persetujuan
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>

<script>
    // Approve all items
    function approveAll() {
        $('.qty-approve').each(function() {
            var requested = $(this).data('requested');
            var available = $(this).data('available');
            $(this).val(Math.min(requested, available));
        });
    }

    // Reject all items
    function rejectAll() {
        $('.qty-approve').val(0);
    }

    // Reject request
    function rejectRequest() {
        Swal.fire({
            title: 'Tolak Permintaan?',
            text: "Semua item dalam permintaan ini akan ditolak!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Tolak!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Add hidden input for rejection
                $('#form-approve').append('<input type="hidden" name="action" value="reject">');
                $('#form-approve').submit();
            }
        });
    }

    // Validate qty on change
    $('.qty-approve').on('change', function() {
        var max = parseInt($(this).attr('max'));
        var val = parseInt($(this).val());

        if (val > max) {
            $(this).val(max);
            iziToast.warning({
                title: 'Peringatan',
                message: 'Qty tidak boleh melebihi stok tersedia',
                position: 'topRight'
            });
        }
    });

    // Form submit validation
    $('#form-approve').on('submit', function(e) {
        var totalApproved = 0;
        $('.qty-approve').each(function() {
            totalApproved += parseInt($(this).val()) || 0;
        });

        if (totalApproved === 0 && $('input[name="action"]').val() !== 'reject') {
            e.preventDefault();
            Swal.fire({
                title: 'Konfirmasi',
                text: "Tidak ada item yang disetujui. Tolak permintaan ini?",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, Tolak',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    $('#form-approve').append('<input type="hidden" name="action" value="reject">');
                    $('#form-approve').submit();
                }
            });
        }
    });
</script>