<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1>Stok Opname</h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Stok Opname</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Stok Opname</h4>
                            <div class="card-header-action">
                                <a href="<?= base_url('admin/stok-opname/tambah') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Buat Stok Opname
                                </a>
                            </div>
                        </div>
                        <div class="card-body">
                            <?php if($this->session->flashdata('success')): ?>
                            <div class="alert alert-success alert-dismissible show fade">
                                <div class="alert-body">
                                    <button class="close" data-dismiss="alert"><span>&times;</span></button>
                                    <?= $this->session->flashdata('success') ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="table-responsive">
                                <table class="table table-striped" id="table-1">
                                    <thead>
                                        <tr>
                                            <th class="text-center">#</th>
                                            <th>No. Opname</th>
                                            <th>Tanggal</th>
                                            <th>Periode</th>
                                            <th>Jenis</th>
                                            <th>Petugas</th>
                                            <th>Nilai Selisih</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1; foreach($stok_opname as $row): ?>
                                        <tr>
                                            <td class="text-center"><?= $no++ ?></td>
                                            <td><?= $row->nomor_opname ?></td>
                                            <td><?= date('d/m/Y', strtotime($row->tanggal_opname)) ?></td>
                                            <td><?= $row->periode ?></td>
                                            <td><?= ucfirst($row->jenis_opname) ?></td>
                                            <td><?= $row->petugas ?></td>
                                            <td>
                                                <?php if($row->total_selisih_nilai > 0): ?>
                                                    <span class="text-success">+Rp <?= number_format(abs($row->total_selisih_nilai), 0, ',', '.') ?></span>
                                                <?php elseif($row->total_selisih_nilai < 0): ?>
                                                    <span class="text-danger">-Rp <?= number_format(abs($row->total_selisih_nilai), 0, ',', '.') ?></span>
                                                <?php else: ?>
                                                    Rp 0
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if($row->status == 'posted'): ?>
                                                    <span class="badge badge-success">Selesai</span>
                                                <?php elseif($row->status == 'cancelled'): ?>
                                                    <span class="badge badge-danger">Cancelled</span>
                                                <?php else: ?>
                                                    <span class="badge badge-warning">Draft</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <a href="<?= base_url('admin/stok-opname/posting/'.$row->id) ?>" 
                                                   class="btn btn-sm btn-success btn-posting" title="Posting">
                                                    <i class="fas fa-check"></i>
                                                </a>
                                                <a href="<?= base_url('admin/stok-opname/hapus/'.$row->id) ?>" 
                                                   class="btn btn-sm btn-danger btn-delete" title="Hapus">
                                                    <i class="fas fa-trash"></i>
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
    $("#table-1").dataTable();
    
    $('.btn-posting').on('click', function(e) {
        e.preventDefault();
        var url = $(this).attr('href');
        
        Swal.fire({
            title: 'Konfirmasi Posting',
            text: "Stok akan disesuaikan dengan hasil opname. Lanjutkan?",
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
});
</script>