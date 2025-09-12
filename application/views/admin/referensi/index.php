<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Referensi</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
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
                            <div class="row">
                                <!-- Sidebar kiri -->
                                <div class="col-md-3">
                                    <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                                        <a class="nav-link active" id="tab-home" data-toggle="pill" href="#pane-home" role="tab" aria-controls="pane-home" aria-selected="true">Satuan</a>
                                        <a class="nav-link" id="tab-profile" data-toggle="pill" href="#pane-profile" role="tab" aria-controls="pane-profile" aria-selected="false">Profile</a>
                                        <a class="nav-link" id="tab-settings" data-toggle="pill" href="#pane-settings" role="tab" aria-controls="pane-settings" aria-selected="false">Settings</a>
                                    </div>
                                </div>

                                <!-- Konten kanan -->
                                <div class="col-md-9">
                                    <div class="tab-content" id="v-pills-tabContent">
                                        <div class="tab-pane fade show active" id="pane-home" role="tabpanel" aria-labelledby="tab-home">
                                            <h4 class="float-left">Satuan</h4>
                                            <button type="button" class="btn btn-primary float-right mb-2" data-toggle="modal" data-target="#modalSatuan">
                                                <i class="fas fa-plus"></i> Tambah Satuan
                                            </button>
                                            <table class="table table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th scope="col" width="5%">No</th>
                                                        <th scope="col">Nama Satuan</th>
                                                        <th scope="col" class="text-center" width="20%">Aksi</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php $i = 1;
                                                    foreach ($ref as $r) : ?>
                                                        <?php if ($r->jenis == 1) : ?>
                                                            <tr>
                                                                <th scope="row"><?= $i++ ?></th>
                                                                <td><?= $r->isi ?></td>
                                                                <td class="text-center">
                                                                    <a href="<?= base_url('admin/referensi/edit_satuan/' . $r->id) ?>" class="btn btn-primary btn-sm">Edit</a>
                                                                    <a href="<?= base_url('admin/referensi/delete_satuan/' . $r->id) ?>" class="btn btn-danger btn-sm btn-delete">Hapus</a>
                                                                </td>
                                                            </tr>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
                                        </div>
                                        <div class="tab-pane fade" id="pane-profile" role="tabpanel" aria-labelledby="tab-profile">
                                            <h4>Profile</h4>
                                            <p>Ini adalah konten tab Profile.</p>
                                        </div>
                                        <div class="tab-pane fade" id="pane-settings" role="tabpanel" aria-labelledby="tab-settings">
                                            <h4>Settings</h4>
                                            <p>Ini adalah konten tab Settings.</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

<div class="modal fade" id="modalSatuan" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Satuan</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <form action="<?= base_url('admin/referensi/simpan') ?>" method="post">
                        <?= csrf_field() ?>
                        <label for="">Nama Satuan</label>
                        <input type="text" name="satuan" class="form-control">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('.btn-delete').on('click', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');

            swal({
                title: "Apakah Anda yakin?",
                text: "Data yang dihapus tidak dapat dikembalikan!",
                icon: "warning",
                buttons: {
                    cancel: {
                        text: "Batal",
                        visible: true,
                        closeModal: true,
                    },
                    confirm: {
                        text: "Ya, hapus!",
                        closeModal: true
                    }
                },
                dangerMode: true
            }).then(function(willDelete) {
                if (willDelete) {
                    window.location.href = url;
                }
            });
        });
    });
</script>
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>