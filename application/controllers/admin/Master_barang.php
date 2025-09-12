<?php
// =============================================
// CONTROLLERS/ADMIN/MASTER_BARANG.PHP (FIXED)
// =============================================
defined('BASEPATH') or exit('No direct script access allowed');

class Master_barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_barang');
        $this->load->library('form_validation');
        $this->load->library('upload');
    }

    public function index()
    {
        $data['title'] = 'Master Barang';
        // $data['barang'] = $this->M_barang->get_all();
        $filter = $this->input->post('child_kode');
        if ($filter) {
            $data['parent_kode'] = $this->input->post('parent_kode');
            $data['child_kode'] = $filter;
            $data['barang'] = $this->M_barang->get_all_filter($filter);
        } else {
            $data['barang'] = $this->M_barang->get_all();
        }
        $data['data_filter'] = $this->db->query("SELECT kode_rek_108, nama_barang_108 FROM master_barang WHERE kode_rek_108 LIKE '%.%.%.%.%.%.%' AND kode_rek_108 NOT LIKE '%.%.%.%.%.%.%.%' GROUP BY kode_rek_108")->result();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/master_barang/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function getFilter()
    {
        $kode = $this->input->get('kode');
        $data['data_filter'] = $this->db->query("SELECT kode_nusp, nama_nusp FROM master_barang WHERE kode_nusp LIKE '$kode.%' GROUP BY kode_nusp")->result();
        echo json_encode($data);
    }
    public function getData()
    {
        $kode = $this->input->get('kode');
        $data['data'] = $this->db->query('SELECT * FROM master_barang WHERE kode_nusp LIKE "%' . $kode . '%"')->result();
        echo json_encode($data);
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Barang';

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            $this->form_validation->set_rules('kode_rek_108', 'Kode Rekening', 'required');
            $this->form_validation->set_rules('nama_barang_108', 'Nama Barang', 'required');
            $this->form_validation->set_rules('kode_nusp', 'Kode NUSP', 'required');
            $this->form_validation->set_rules('nama_nusp', 'Nama NUSP', 'required');
            $this->form_validation->set_rules('nama_gudang', 'Nama Gudang', 'required');
            $this->form_validation->set_rules('satuan', 'Satuan', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Proses upload gambar jika ada
                $upload_gambar = '';
                if (!empty($_FILES['gambar']['name'])) {
                    $config['upload_path'] = './uploads/barang/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['max_size'] = 2048;
                    $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];

                    // Create directory if not exists
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('gambar')) {
                        $upload_gambar = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                    }
                }
                $kode_nusp = $this->input->post('kode_nusp');

                // Ambil kode terakhir untuk kode_nusp ini
                $cek = $this->db->query("
                    SELECT kode_gudang 
                    FROM master_barang 
                    WHERE kode_nusp = '{$kode_nusp}'
                    ORDER BY kode_gudang DESC 
                    LIMIT 1
                ")->row_array();

                if (empty($cek['kode_gudang'])) {
                    $urutan = 1;
                } else {
                    $last_number = (int)substr($cek['kode_gudang'], -3);
                    $urutan = $last_number + 1;
                }

                $kode_generate = $kode_nusp . '.' . str_pad($urutan, 3, "0", STR_PAD_LEFT);

                $data_insert = [
                    'kode_rek_108' => $this->input->post('kode_rek_108'),
                    'nama_barang_108' => $this->input->post('nama_barang_108'),
                    'kode_nusp' => $this->input->post('kode_nusp'),
                    'nama_nusp' => $this->input->post('nama_nusp'),
                    'kode_gudang' => $kode_generate,
                    'nama_gudang' => $this->input->post('nama_gudang'),
                    'satuan' => $this->input->post('satuan'),
                    'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
                    'harga_terakhir' => $this->input->post('harga_terakhir') ?: 0,
                    'gambar' => $upload_gambar,
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($this->M_barang->insert($data_insert)) {
                    $this->session->set_flashdata('success', 'Data barang berhasil ditambahkan');
                    redirect('admin/master-barang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan data barang');
                }
            }
        }

        // Tampilkan form (GET request atau validation failed)
        $data['ref'] = $this->db->query("SELECT * FROM referensi WHERE jenis = 1")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/master_barang/tambah', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function tambah_per_nusp()
    {
        $data['title'] = 'Tambah Barang';

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            $this->form_validation->set_rules('kode_rek_108', 'Kode Rekening', 'required');
            $this->form_validation->set_rules('nama_barang_108', 'Nama Barang', 'required');
            $this->form_validation->set_rules('kode_nusp', 'Kode NUSP', 'required');
            $this->form_validation->set_rules('nama_nusp', 'Nama NUSP', 'required');
            $this->form_validation->set_rules('nama_barang_gudang', 'Nama Barang Gudang', 'required');
            $this->form_validation->set_rules('satuan', 'Satuan', 'required');
            $this->form_validation->set_rules('stok_minimum', 'Stok Minimum', 'required');
            $this->form_validation->set_rules('harga_terakhir', 'Harga Terakhir', 'required');

            if ($this->form_validation->run() == TRUE) {
                // Proses upload gambar jika ada
                $upload_gambar = '';
                if (!empty($_FILES['gambar']['name'])) {
                    $config['upload_path'] = './uploads/barang/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['max_size'] = 2048;
                    $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];

                    // Create directory if not exists
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('gambar')) {
                        $upload_gambar = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                    }
                }
                $kode_nusp = $this->input->post('kode_nusp');

                // Ambil kode terakhir untuk kode_nusp ini
                $cek = $this->db->query("
                    SELECT kode_gudang 
                    FROM master_barang 
                    WHERE kode_nusp = '{$kode_nusp}'
                    ORDER BY kode_gudang DESC 
                    LIMIT 1
                ")->row_array();

                if (empty($cek['kode_gudang'])) {
                    $urutan = 1;
                } else {
                    $last_number = (int)substr($cek['kode_gudang'], -3); // ambil 3 digit terakhir
                    $urutan = $last_number + 1;
                }

                // Hasilkan kode dengan 3 digit (atau lebih kalau >999)
                $kode_generate = $kode_nusp . '.' . str_pad($urutan, 3, "0", STR_PAD_LEFT);


                // Data untuk insert
                $data_insert = [
                    'kode_rek_108' => $this->input->post('kode_rek_108'),
                    'nama_barang_108' => $this->input->post('nama_barang_108'),
                    'kode_nusp' => $this->input->post('kode_nusp'),
                    'nama_nusp' => $this->input->post('nama_nusp'),
                    'satuan' => $this->input->post('satuan'),
                    'nama_gudang' => $this->input->post('nama_barang_gudang'),
                    'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
                    'harga_terakhir' => $this->input->post('harga_terakhir') ?: 0,
                    'gambar' => $upload_gambar,
                    'kode_gudang' => $kode_generate,
                    'status' => 'aktif',
                    'created_at' => date('Y-m-d H:i:s')
                ];

                if ($this->M_barang->insert($data_insert)) {
                    $this->session->set_flashdata('success', 'Data barang berhasil ditambahkan');
                    redirect($_SERVER['HTTP_REFERER']);
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan data barang');
                }
            }
        }
        $kode_nusp = $this->input->get('kode');

        // Ambil kode terakhir untuk kode_nusp ini
        $cek = $this->db->query("
                    SELECT kode_gudang 
                    FROM master_barang 
                    WHERE kode_nusp = '{$kode_nusp}'
                    ORDER BY kode_gudang DESC 
                    LIMIT 1
                ")->row_array();

        if (empty($cek['kode_gudang'])) {
            $urutan = 1;
        } else {
            $last_number = (int)substr($cek['kode_gudang'], -3); // ambil 3 digit terakhir
            $urutan = $last_number + 1;
        }

        // Hasilkan kode dengan 3 digit (atau lebih kalau >999)
        $kode_generate = $kode_nusp . '.' . str_pad($urutan, 3, "0", STR_PAD_LEFT);

        // Tampilkan form (GET request atau validation failed)
        $kode = $this->input->get('kode');
        $data['data'] = $this->M_barang->get_per_nusp($kode);
        $data['data_per_nusp'] = $this->M_barang->get_all_filter($kode);
        $data['kode_auto'] = $kode_generate;
        $data['ref'] = $this->db->query("SELECT * FROM referensi WHERE jenis = 1")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/master_barang/tambah_per_nusp', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function edit($id = null)
    {
        if ($id === null) {
            show_404();
        }

        $data['title'] = 'Edit Barang';
        $data['barang'] = $this->M_barang->get_by_id($id);

        if (!$data['barang']) {
            show_404();
        }

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            $this->form_validation->set_rules('kode_rek_108', 'Kode Rekening', 'required');
            $this->form_validation->set_rules('nama_barang_108', 'Nama Barang', 'required');
            $this->form_validation->set_rules('nama_nusp', 'Nama NUSP', 'required');
            $this->form_validation->set_rules('satuan', 'Satuan', 'required');

            // Check if kode_nusp changed
            if ($this->input->post('kode_nusp') != $data['barang']->kode_nusp) {
                $this->form_validation->set_rules('kode_nusp', 'Kode NUSP', 'required|is_unique[master_barang.kode_nusp]');
            } else {
                $this->form_validation->set_rules('kode_nusp', 'Kode NUSP', 'required');
            }
            if ($data['barang']->kode_gudang) {
                $kode_generate = $this->input->post('kode_gudang');
            } else {
                $kode_nusp = $this->input->post('kode_nusp');
                $cek = $this->db->query("
                    SELECT kode_gudang 
                    FROM master_barang 
                    WHERE kode_nusp = '{$kode_nusp}'
                    ORDER BY kode_gudang DESC 
                    LIMIT 1
                ")->row_array();

                if (empty($cek['kode_gudang'])) {
                    $urutan = 1;
                } else {
                    $last_number = (int)substr($cek['kode_gudang'], -3);
                    $urutan = $last_number + 1;
                }

                $kode_generate = $kode_nusp . '.' . str_pad($urutan, 3, "0", STR_PAD_LEFT);
            }


            if ($this->form_validation->run() == TRUE) {
                // Prepare update data
                $data_update = [
                    'kode_rek_108' => $this->input->post('kode_rek_108'),
                    'nama_barang_108' => $this->input->post('nama_barang_108'),
                    'kode_nusp' => $this->input->post('kode_nusp'),
                    'nama_nusp' => $this->input->post('nama_nusp'),
                    'kode_gudang' => $kode_generate,
                    'nama_gudang' => $this->input->post('nama_gudang'),
                    'satuan' => $this->input->post('satuan'),
                    'stok_minimum' => $this->input->post('stok_minimum') ?: 0,
                    'harga_terakhir' => $this->input->post('harga_terakhir') ?: 0,
                    'status' => $this->input->post('status') ?: 'aktif',
                    'updated_at' => date('Y-m-d H:i:s')
                ];

                // Handle image upload if new image provided
                if (!empty($_FILES['gambar']['name'])) {
                    $config['upload_path'] = './uploads/barang/';
                    $config['allowed_types'] = 'jpg|jpeg|png';
                    $config['max_size'] = 2048;
                    $config['file_name'] = time() . '_' . $_FILES['gambar']['name'];

                    // Create directory if not exists
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('gambar')) {
                        // Delete old image if exists
                        if ($data['barang']->gambar && file_exists('./uploads/barang/' . $data['barang']->gambar)) {
                            unlink('./uploads/barang/' . $data['barang']->gambar);
                        }
                        $data_update['gambar'] = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                    }
                }

                // Update database
                if ($this->M_barang->update($id, $data_update)) {
                    $this->session->set_flashdata('success', 'Data barang berhasil diupdate');
                    redirect('admin/master-barang');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate data barang');
                }
            }
            // If validation fails, will continue to show form with errors
        }

        // Show form (GET request or validation failed)
        $data['ref'] = $this->db->query("SELECT * FROM referensi WHERE jenis = 1")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/master_barang/edit', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function hapus($id = null)
    {
        if ($id === null) {
            show_404();
        }

        // Check if barang has transactions
        $has_transaction = $this->M_barang->check_transaction($id);

        if ($has_transaction) {
            $this->session->set_flashdata('error', 'Barang tidak dapat dihapus karena sudah memiliki transaksi');
        } else {
            // Get barang data for image deletion
            $barang = $this->M_barang->get_by_id($id);

            if ($barang) {
                // Delete image if exists
                if ($barang->gambar && file_exists('./uploads/barang/' . $barang->gambar)) {
                    unlink('./uploads/barang/' . $barang->gambar);
                }

                // Delete from database
                if ($this->M_barang->delete($id)) {
                    $this->session->set_flashdata('success', 'Data barang berhasil dihapus');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menghapus data barang');
                }
            } else {
                $this->session->set_flashdata('error', 'Data barang tidak ditemukan');
            }
        }

        redirect('admin/master-barang');
    }

    // Ajax method to get barang data as JSON
    public function get_barang_json($id = null)
    {
        if ($id === null) {
            echo json_encode(['error' => 'ID not provided']);
            return;
        }

        $barang = $this->M_barang->get_by_id($id);
        if ($barang) {
            echo json_encode($barang);
        } else {
            echo json_encode(['error' => 'Data not found']);
        }
    }

    // Ajax method to get current stock
    public function get_stok($barang_id = null)
    {
        if ($barang_id === null) {
            echo json_encode(['error' => 'ID not provided']);
            return;
        }

        $periode = date('Y-m');
        $stok = $this->M_barang->get_stok($barang_id, $periode);

        if ($stok) {
            echo json_encode([
                'status' => 'success',
                'stok' => $stok->stok_akhir ?? 0,
                'harga' => $stok->harga_rata_rata ?? 0
            ]);
        } else {
            // If no stock record, get from master
            $barang = $this->M_barang->get_by_id($barang_id);
            echo json_encode([
                'status' => 'success',
                'stok' => 0,
                'harga' => $barang ? $barang->harga_terakhir : 0
            ]);
        }
    }

    // Method to check if kode_nusp is unique (for AJAX validation)
    public function check_kode_nusp()
    {
        $kode_nusp = $this->input->post('kode_nusp');
        $id = $this->input->post('id'); // For edit mode

        if ($kode_nusp) {
            $this->db->where('kode_nusp', $kode_nusp);
            if ($id) {
                $this->db->where('id !=', $id);
            }
            $count = $this->db->count_all_results('master_barang');

            if ($count > 0) {
                echo json_encode(['status' => false, 'message' => 'Kode NUSP sudah digunakan']);
            } else {
                echo json_encode(['status' => true, 'message' => 'Kode NUSP tersedia']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Kode NUSP tidak boleh kosong']);
        }
    }
}
