<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Permintaan Barang</div>
            </div>
        </div>

        <div class="section-body">
            <!-- Statistics Cards -->
            <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon bg-warning">
                            <i class="fas fa-hourglass-half"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Menunggu Persetujuan</h4>
                            </div>
                            <div class="card-body">
                                <?= $count_pending ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon bg-success">
                            <i class="fas fa-check-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Disetujui</h4>
                            </div>
                            <div class="card-body">
                                <?= $count_approved ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-12">
                    <div class="card card-statistic-2">
                        <div class="card-icon bg-danger">
                            <i class="fas fa-times-circle"></i>
                        </div>
                        <div class="card-wrap">
                            <div class="card-header">
                                <h4>Ditolak</h4>
                            </div>
                            <div class="card-body">
                                <?= $count_rejected ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Filter Section -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter</h4>
                            <div class="card-header-action">
                                <a data-collapse="#filter-box" class="btn btn-icon btn-info" href="#">
                                    <i class="fas fa-filter"></i>
                                </a>
                            </div>
                        </div>
                        <div class="collapse show" id="filter-box">
                            <div class="card-body">
                                <form method="GET" action="">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Status</label>
                                                <select name="status" class="form-control">
                                                    <option value="">Semua Status</option>
                                                    <option value="pending" <?= $filter_status == 'pending' ? 'selected' : '' ?>>Menunggu</option>
                                                    <option value="approved" <?= $filter_status == 'approved' ? 'selected' : '' ?>>Disetujui</option>
                                                    <option value="partial" <?= $filter_status == 'partial' ? 'selected' : '' ?>>Sebagian</option>
                                                    <option value="rejected" <?= $filter_status == 'rejected' ? 'selected' : '' ?>>Ditolak</option>
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tanggal Mulai</label>
                                                <input type="date" name="start_date" class="form-control" value="<?= $filter_start ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>Tanggal Akhir</label>
                                                <input type="date" name="end_date" class="form-control" value="<?= $filter_end ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label>&nbsp;</label>
                                                <div>
                                                    <button type="submit" class="btn btn-primary">
                                                        <i class="fas fa-search"></i> Filter
                                                    </button>
                                                    <a href="<?= base_url('admin/permintaan') ?>" class="btn btn-secondary">
                                                        Reset
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Table -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Permintaan</h4>
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
                                            <th>No. Permintaan</th>
                                            <th>Tanggal</th>
                                            <th>User</th>
                                            <th>Keperluan</th>
                                            <th>Item</th>
                                            <th>Status</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($permintaan as $p) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td>
                                                    <strong><?= $p->nomor_permintaan ?></strong>
                                                </td>
                                                <td><?= date('d/m/Y', strtotime($p->tanggal_permintaan)) ?></td>
                                                <td><?= $p->nama_user ?></td>
                                                <td><?= character_limiter($p->keperluan, 30) ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-primary"><?= $p->total_item ?></span>
                                                </td>
                                                <td>
                                                    <?php
                                                    $badge_class = '';
                                                    switch ($p->status) {
                                                        case 'pending':
                                                            $badge_class = 'badge-warning';
                                                            $status_text = 'Menunggu';
                                                            break;
                                                        case 'approved':
                                                            $badge_class = 'badge-success';
                                                            $status_text = 'Disetujui';
                                                            break;
                                                        case 'rejected':
                                                            $badge_class = 'badge-danger';
                                                            $status_text = 'Ditolak';
                                                            break;
                                                        case 'partial':
                                                            $badge_class = 'badge-info';
                                                            $status_text = 'Sebagian';
                                                            break;
                                                        default:
                                                            $badge_class = 'badge-secondary';
                                                            $status_text = ucfirst($p->status);
                                                    }
                                                    ?>
                                                    <span class="badge <?= $badge_class ?>"><?= $status_text ?></span>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('admin/permintaan/detail/' . $p->id) ?>" class="btn btn-sm btn-info" title="Detail">
                                                        <i class="fas fa-eye"></i>
                                                    </a>
                                                    <?php if ($p->status == 'pending') : ?>
                                                        <a href="<?= base_url('admin/permintaan/approve/' . $p->id) ?>" class="btn btn-sm btn-success" title="Proses">
                                                            <i class="fas fa-check"></i> Proses
                                                        </a>
                                                    <?php endif; ?>
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
                [2, "desc"]
            ],
            "columnDefs": [{
                "orderable": false,
                "targets": [7]
            }]
        });
    });
</script>