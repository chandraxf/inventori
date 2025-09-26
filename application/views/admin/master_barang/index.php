<div class="main-content">
    <section class="section">
        <div class="section-header">
            <h1><?= $title ?></h1>
            <div class="section-header-breadcrumb">
                <div class="breadcrumb-item active"><a href="<?= base_url('admin') ?>">Dashboard</a></div>
                <div class="breadcrumb-item">Master Barang</div>
            </div>
        </div>

        <div class="section-body">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Filter</h4>
                        </div>
                        <div class="card-body">
                            <form action="<?= base_url('admin/Master_barang') ?>" method="post">
                                <?= csrf_field() ?>
                                <div class="form-group row align-items-end mb-4">
                                    <div class="col">
                                        <select name="parent_kode" id="parentSelect" class="form-control" required>
                                            <option value="" disabled <?= empty($parent_kode) ? 'selected' : '' ?>>-- Pilih Data Filter --</option>
                                            <?php foreach ($data_filter as $df) : ?>
                                                <option value="<?= html_escape($df->kode_rek_108) ?>" <?= isset($parent_kode) && $parent_kode == $df->kode_rek_108 ? 'selected' : '' ?>>
                                                    <?= html_escape($df->nama_barang_108) ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="col">
                                        <select name="child_kode" id="childSelect" class="form-control" required>
                                            <option value="" disabled selected>-- Pilih Sub Data --</option>
                                            <!-- Nanti diisi via AJAX -->
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col">
                                        <button type="submit" class="btn btn-info float-right"><i class="fas fa-search"></i> Filter Data</button>
                                        <?php if (isset($child_kode)) : ?>
                                            <a href="<?= base_url('admin/Master_barang/tambah_per_nusp?kode=') . $child_kode ?>" class="btn btn-primary float-right mr-2"><i class="fas fa-plus"></i> Tambah Barang Per NUSP</a>
                                        <?php endif ?>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4>Data Barang</h4>
                            <div class="card-header-action">
                                <a href="<?= base_url('admin/master-barang/tambah') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah Barang
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
                                            <th class="text-center" style="width:50px">#</th>
                                            <th style="width:120px">Kode Rek 108</th>
                                            <th style="width:500px">Nama Barang 108</th>
                                            <th style="width:120px">Kode NUSP</th>
                                            <th style="width:200px">Nama Barang</th>
                                            <th style="width:120px">Kode Gudang</th>
                                            <th style="width:200px">Nama Gudang</th>
                                            <th style="width:80px">Satuan</th>
                                            <th style="width:80px">Stok</th>
                                            <th style="width:80px">Gambar</th>
                                            <!-- <th style="width:80px">Stok Min</th> -->
                                            <th style="width:120px">Harga</th>
                                            <th style="width:100px">Status</th>
                                            <th style="width:20%">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php $no = 1;
                                        foreach ($barang as $row) : ?>
                                            <tr>
                                                <td class="text-center"><?= $no++ ?></td>
                                                <td><?= $row->kode_rek_108 ?></td>
                                                <td><?= $row->nama_barang_108 ?></td>
                                                <td><?= $row->kode_nusp ?></td>
                                                <td><?= $row->nama_nusp ?></td>
                                                <td><?= (!$row->kode_gudang) ? '<span class="badge badge-danger">Tidak ada kode!.</span>' : $row->kode_gudang ?></td>
                                                <td><?= (!$row->kode_gudang) ? '<span class="badge badge-danger">Tidak ada barang!.</span>' : $row->nama_gudang ?></td>
                                                <td><?= $row->satuan ?></td>
                                                <td>
                                                    <?php if ($row->stok_saat_ini <= $row->stok_minimum) : ?>
                                                        <span class="badge badge-danger"><?= $row->stok_saat_ini ?></span>
                                                    <?php else : ?>
                                                        <span class="badge badge-success"><?= $row->stok_saat_ini ?></span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php if ($row->gambar) : ?>
                                                        <img src="<?= base_url('uploads/barang/' . $row->gambar) ?>" alt="Gambar Barang" class="img-fluid" style="max-height: 50px;">
                                                    <?php else : ?>
                                                        <span class="badge badge-danger">Tidak ada gambar!</span>
                                                    <?php endif; ?>
                                                </td>
                                                <!-- <th style="width:80px">Stok Min</th> -->
                                                <td>Rp <?= number_format($row->harga_terakhir, 0, ',', '.') ?></td>
                                                <td>
                                                    <?php if ($row->status == 'aktif') : ?>
                                                        <span class="badge badge-success">Aktif</span>
                                                    <?php else : ?>
                                                        <span class="badge badge-danger">Nonaktif</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <a href="<?= base_url('admin/master_barang/edit/' . $row->id) ?>" class="btn btn-sm btn-warning" title="Edit">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <a href="<?= base_url('admin/master-barang/hapus/' . $row->id) ?>" class="btn btn-sm btn-danger btn-delete" title="Hapus">
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
<script>
    // nilai default dari PHP (jika sedang edit data)
    var selectedChild = "<?= isset($child_kode) ? $child_kode : '' ?>";

    $(document).ready(function() {
        $('#table-1').DataTable({
            autoWidth: true
        });
        // kalau parent sudah ada nilai (misal saat edit), langsung load child-nya
        if ($('#parentSelect').val() !== "") {
            loadChild($('#parentSelect').val());
        }

        // event saat parent berubah
        $('#parentSelect').on('change', function() {
            var kode = $(this).val();
            loadChild(kode);
        });

        // fungsi untuk ambil data anak via AJAX
        function loadChild(kode) {
            $('#childSelect').html('<option value="">Loading...</option>');

            $.ajax({
                url: '<?= base_url('admin/Master_barang/getFilter') ?>',
                type: 'GET',
                dataType: 'json',
                data: {
                    kode: kode
                },
                success: function(response) {
                    var options = '<option value="" disabled>Pilih Sub Data</option>';
                    if (response.data_filter.length > 0) {
                        $.each(response.data_filter, function(index, item) {
                            // cek apakah kode_nusp = child_kode, kalau iya set selected
                            var isSelected = (item.kode_nusp === selectedChild) ? 'selected' : '';
                            options += '<option value="' + item.kode_nusp + '" ' + isSelected + '>' + item.nama_nusp + '</option>';
                        });
                    } else {
                        options = '<option value="">Tidak ada data</option>';
                    }
                    $('#childSelect').html(options);
                },
                error: function() {
                    $('#childSelect').html('<option value="">Gagal mengambil data</option>');
                }
            });
        }
    });
</script>