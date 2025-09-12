<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_masuk extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['M_barang_masuk', 'M_barang']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Barang Masuk';
        $data['barang_masuk'] = $this->M_barang_masuk->get_all();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/barang_masuk/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Barang Masuk';
        $data['barang'] = $this->M_barang->get_all_active();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/barang_masuk/tambah', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function simpan()
    {
        $this->db->trans_start();

        // Insert header
        $data_header = [
            'tanggal_masuk' => $this->input->post('tanggal_masuk'),
            'jenis_masuk' => $this->input->post('jenis_masuk'),
            'nomor_faktur' => $this->input->post('nomor_faktur'),
            'nama_supplier' => $this->input->post('nama_supplier'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => 'draft',
            'user_input' => $this->session->userdata('username') ?? 'admin',
            'total_nilai' => 0
        ];

        $this->db->insert('barang_masuk', $data_header);
        $barang_masuk_id = $this->db->insert_id();

        // Insert detail
        $barang_id = $this->input->post('barang_id');
        $jumlah = $this->input->post('jumlah');
        $harga_satuan = $this->input->post('harga_satuan');

        $total_nilai = 0;
        for ($i = 0; $i < count($barang_id); $i++) {
            if ($barang_id[$i] && $jumlah[$i] > 0) {
                $total = $jumlah[$i] * $harga_satuan[$i];
                $data_detail = [
                    'barang_masuk_id' => $barang_masuk_id,
                    'barang_id' => $barang_id[$i],
                    'jumlah' => $jumlah[$i],
                    'harga_satuan' => $harga_satuan[$i],
                    'total' => $total
                ];
                $this->db->insert('barang_masuk_detail', $data_detail);
                $total_nilai += $total;
            }
        }

        // Update total nilai
        $this->db->where('id', $barang_masuk_id);
        $this->db->update('barang_masuk', ['total_nilai' => $total_nilai]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menyimpan barang masuk');
            redirect('admin/barang-masuk/tambah');
        } else {
            $this->session->set_flashdata('success', 'Barang masuk berhasil disimpan');
            redirect('admin/barang-masuk');
        }
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Barang Masuk';
        $data['header'] = $this->M_barang_masuk->get_by_id($id);
        $data['detail'] = $this->M_barang_masuk->get_detail($id);

        if (!$data['header']) {
            show_404();
        }

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/barang_masuk/detail', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function posting($id)
    {
        $result = $this->M_barang_masuk->posting($id);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('admin/barang-masuk');
    }

    public function hapus($id)
    {
        $header = $this->M_barang_masuk->get_by_id($id);

        if (!$header) {
            show_404();
        }

        if ($header->status == 'posted') {
            $this->session->set_flashdata('error', 'Transaksi yang sudah diposting tidak dapat dihapus');
        } else {
            if ($this->M_barang_masuk->delete($id)) {
                $this->session->set_flashdata('success', 'Barang masuk berhasil dihapus');
            } else {
                $this->session->set_flashdata('error', 'Gagal menghapus barang masuk');
            }
        }

        redirect('admin/barang-masuk');
    }

    public function cetak($id)
    {
        $data['header'] = $this->M_barang_masuk->get_by_id($id);
        $data['detail'] = $this->M_barang_masuk->get_detail($id);

        if (!$data['header']) {
            show_404();
        }

        $this->load->view('admin/barang_masuk/cetak', $data);
    }

    public function search_barang()
    {
        // Set header untuk JSON response
        header('Content-Type: application/json');

        // Ambil parameter pencarian
        $search = $this->input->get('search');
        $page = (int)$this->input->get('page') ?: 1;
        $limit = 10; // Jumlah item per halaman
        $offset = ($page - 1) * $limit;

        // Validasi input minimal 2 karakter
        if (empty($search) || strlen($search) < 2) {
            echo json_encode([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
            return;
        }

        try {

            // Query pencarian barang - ambil semua data yang diperlukan
            $this->db->select('
            mb.id, 
            mb.kode_nusp, 
            mb.nama_nusp, 
            mb.kode_rek_108,
            mb.nama_barang_108,
            mb.kode_gudang,
            mb.nama_gudang,
            mb.satuan, 
            COALESCE(mb.harga_terakhir, 0) as harga_terakhir,
            COALESCE(sb.stok_akhir, 0) as stok_saat_ini,
            COALESCE(sb.harga_rata_rata, mb.harga_terakhir, 0) as harga_rata_rata
        ');
            $this->db->from('master_barang mb');

            // Left join dengan kondisi yang lebih aman
            $current_periode = date('Y-m');
            $this->db->join('stok_barang sb', "mb.id = sb.barang_id AND sb.periode = '$current_periode'", 'left');

            // Filter status aktif
            $this->db->where('mb.status', 'aktif');

            // Pencarian dengan multiple kondisi
            $this->db->group_start();
            $this->db->like('LOWER(mb.nama_gudang)', strtolower($search));
            $this->db->or_like('LOWER(mb.kode_gudang)', strtolower($search));
            if (!empty($search)) {
                $this->db->or_like('LOWER(mb.nama_barang_108)', strtolower($search));
            }
            $this->db->group_end();

            // Pagination dan ordering
            $this->db->limit($limit, $offset);
            $this->db->order_by('mb.nama_gudang', 'ASC');

            $query = $this->db->get();

            // Debug: Log query yang dijalankan
            log_message('info', 'Query: ' . $this->db->last_query());

            $results = $query->result();

            // Hitung total untuk pagination
            $this->db->select('COUNT(DISTINCT mb.id) as total');
            $this->db->from('master_barang mb');
            $this->db->where('mb.status', 'aktif');
            $this->db->group_start();
            $this->db->like('LOWER(mb.nama_gudang)', strtolower($search));
            $this->db->or_like('LOWER(mb.kode_gudang)', strtolower($search));
            if (!empty($search)) {
                $this->db->or_like('LOWER(mb.nama_nusp)', strtolower($search));
            }
            $this->db->group_end();

            $total_query = $this->db->get();
            $total = $total_query->row()->total;

            $has_more = ($offset + $limit) < $total;

            // Format data untuk Select2
            $formatted_results = [];
            if (!empty($results)) {
                foreach ($results as $item) {
                    $formatted_results[] = [
                        'id' => $item->id,
                        'text' => $item->kode_nusp . ' - ' . $item->nama_nusp,
                        'kode_nusp' => $item->kode_nusp ?? '',
                        'nama_nusp' => $item->nama_nusp ?? '',
                        'kode_gudang' => $item->kode_gudang ?? '',
                        'nama_gudang' => $item->nama_gudang ?? '',
                        'satuan' => $item->satuan ?? '',
                        'stok_saat_ini' => (int)$item->stok_saat_ini,
                        'harga_terakhir' => (float)$item->harga_terakhir,
                        'harga_rata_rata' => (float)$item->harga_rata_rata
                    ];
                }
            }

            $response = [
                'results' => $formatted_results,
                'pagination' => [
                    'more' => $has_more
                ],
                'debug' => [
                    'search_term' => $search,
                    'total_found' => $total,
                    'current_page' => $page
                ]
            ];

            echo json_encode($response);
        } catch (Exception $e) {
            // Log error untuk debugging
            log_message('error', 'Error in search_barang: ' . $e->getMessage());

            echo json_encode([
                'results' => [],
                'pagination' => ['more' => false],
                'error' => 'Terjadi kesalahan dalam pencarian: ' . $e->getMessage()
            ]);
        }
    }
    public function get_barang_detail($id = null)
    {
        if ($id === null) {
            echo json_encode(['error' => 'ID tidak ditemukan']);
            return;
        }

        $periode = date('Y-m');

        $this->db->select('
            mb.*, 
            COALESCE(sb.stok_akhir, 0) as stok_saat_ini,
            COALESCE(sb.harga_rata_rata, mb.harga_terakhir, 0) as harga_rata_rata
        ');
        $this->db->from('master_barang mb');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = "' . $periode . '"', 'left');
        $this->db->where('mb.id', $id);

        $barang = $this->db->get()->row();

        if ($barang) {
            echo json_encode([
                'status' => 'success',
                'data' => $barang
            ]);
        } else {
            echo json_encode([
                'status' => 'error',
                'message' => 'Barang tidak ditemukan'
            ]);
        }
    }
}
