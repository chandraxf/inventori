<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Barang Keluar</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Barang Keluar</h4>
                            <div class="card-header-action">
                                <a href="<?= base_url('admin/barang-keluar/tambah') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Transaksi
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
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

                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>No. Transaksi</th>
                                            <th>Tanggal</th>
                                            <th>Unit</th>
                                            <th>Jenis</th>
                                            <th>Penerima</th>
                                            <th>Total Nilai</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($barang_keluar as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $row->nomor_transaksi ?></td>
                                                <td><?= date('d/m/Y', strtotime($row->tanggal_keluar)) ?></td>
                                                <td><?= $row->nama_unit ?: '-' ?></td>
                                                <td><?= ucfirst(str_replace('_', ' ', $row->jenis_keluar)) ?></td>
                                                <td><?= $row->nama_penerima ?: '-' ?></td>
                                                <td>Rp <?= number_format($row->total_nilai, 0, ',', '.') ?></td>
                                                <td>
                                                    <?php if ($row->status == 'posted') : ?>
                                                        <span class="badge badge-success">Posted</span>
                                                    <?php elseif ($row->status == 'cancelled') : ?>
                                                        <span class="badge badge-danger">Cancelled</span>
                                                    <?php else : ?>
                                                        <span class="badge badge-warning">Draft</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('admin/barang-keluar/detail/' . $row->id) ?>" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($row->status == 'draft') : ?>
                                                        <a href="<?= base_url('admin/barang-keluar/posting/' . $row->id) ?>" class="btn btn-sm btn-success btn-posting" title="Posting">
                                                            <i class="fas fa-check"></i>
                                                        </a>
                                                        <a href="<?= base_url('admin/barang-keluar/hapus/' . $row->id) ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                                            <i class="fas fa-trash"></i>
                                                        </a>
                                                    <?php endif; ?>
                                                    <a href="<?= base_url('admin/barang-keluar/cetak/' . $row->id) ?>" class="btn btn-sm btn-secondary" title="Cetak" target="_blank">
                                                        <i class="fas fa-print"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<script>
    $(document).ready(function() {
        $("#table-1").dataTable({
            "order": [
                [0, "desc"]
            ]
        });

        $('.btn-posting').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            Swal.fire({
                title: 'Konfirmasi Posting',
                text: "Transaksi yang sudah diposting tidak dapat diubah!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, Posting!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });

        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>