<div class="main-content">
    
    <div class="card">
        <div class="card-header">
            <h4>Data Master Barang</h4>
        </div>
        <div class="card-body">
            <form action="<?= base_url('Master') ?>" method="post">
                <input type="hidden" id="csrf_token_name" name="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
                <div class="form-group row align-items-end mb-4">
                    <div class="col-sm-4">
                        <select name="parent_kode" id="parentSelect" class="form-control">
                            <option value="" selected disabled>Pilih data filter</option>
                            <?php foreach ($data_filter as $df) : ?>
                                <option value="<?= $df->KODE_REK ?>"><?= $df->NAMA ?></option>
                            <?php endforeach ?>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select name="child_kode" id="childSelect" class="form-control">
                            <!-- Diisi oleh ajax sesuai kode diatas -->
                        </select>
                    </div>
                    <div class="col-auto">
                        <button type="submit" class="btn btn-primary">Filter</button>
                    </div>
                </div>
            </form>
            <hr>
            <table class="table">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>KODE REK</th>
                        <th>NAMA</th>
                        <th>KODE</th>
                        <th>NAMA BARANG</th>
                        <th>SATUAN</th>
                        <th>STOK</th>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i = 1;
                    foreach ($data as $d) : ?>
                        <tr>
                            <td><?= $i++; ?></td>
                            <td><?= $d->KODE_REK ?></td>
                            <td><?= $d->NAMA ?></td>
                            <td><?= $d->KODE ?></td>
                            <td><?= $d->NAMA_BARANG ?></td>
                            <td><?= $d->SATUAN ?></td>
                            <td>
                                <span class="badge badge-<?= $d->STOK > 0 ? 'success' : 'danger' ?>"><?= $d->STOK ?></span>
                            </td>
                            <td>
                                <a href="<?= base_url('Master/detail/' . hash_id($d->ID)) ?>" class="btn btn-primary">Detail</a>
                            </td>
                        </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<script src="<?= base_url('assets/modules/datatables/datatables.min.js') ?>"></script>
<script>
    $(document).ready(function() {
        $('.table').DataTable();
    });
    $('#parentSelect').on('change', function() {
        var kode = $(this).val();

        $('#childSelect').html('<option value="">Loading...</option>');

        $.ajax({
            url: '<?= base_url('Master/getFilter') ?>',
            type: 'GET',
            dataType: 'json',
            data: {
                kode: kode,
            },
            success: function(response) {
                // Update token CSRF dari response header jika dikirim ulang oleh server
                var options = '<option value="" disabled>Pilih Sub Data</option>';
                if (response.data_filter.length > 0) {
                    $.each(response.data_filter, function(index, item) {
                        options += '<option value="' + item.KODE_REK + '">' + item.NAMA + '</option>';
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
    });
</script>