<?php
// =============================================
// 9. CONTROLLERS/ADMIN/BARANG_KELUAR.PHP
// =============================================
defined('BASEPATH') or exit('No direct script access allowed');

class Barang_keluar extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['M_barang_keluar', 'M_barang', 'M_unit']);
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Barang Keluar';
        $data['barang_keluar'] = $this->M_barang_keluar->get_all();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/barang_keluar/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Barang Keluar';
        $data['barang'] = $this->M_barang->get_all_active();
        $data['unit'] = $this->M_unit->get_all_active();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/barang_keluar/tambah', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function simpan()
    {
        $this->db->trans_start();

        // Insert header
        $data_header = [
            'tanggal_keluar' => $this->input->post('tanggal_keluar'),
            'unit_id' => $this->input->post('unit_id'),
            'jenis_keluar' => $this->input->post('jenis_keluar'),
            'nomor_permintaan' => $this->input->post('nomor_permintaan'),
            'nama_penerima' => $this->input->post('nama_penerima'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => 'draft',
            'user_input' => $this->session->userdata('username') ?? 'admin',
            'total_nilai' => 0
        ];

        $this->db->insert('barang_keluar', $data_header);
        $barang_keluar_id = $this->db->insert_id();

        // Insert detail
        $barang_id = $this->input->post('barang_id');
        $jumlah = $this->input->post('jumlah');

        for ($i = 0; $i < count($barang_id); $i++) {
            if ($barang_id[$i] && $jumlah[$i] > 0) {
                // Get harga rata-rata from stok
                $periode = date('Y-m');
                $stok = $this->M_barang->get_stok($barang_id[$i], $periode);
                $harga = $stok ? $stok->harga_rata_rata : 0;

                $data_detail = [
                    'barang_keluar_id' => $barang_keluar_id,
                    'barang_id' => $barang_id[$i],
                    'jumlah' => $jumlah[$i],
                    'harga_satuan' => $harga,
                    'total' => $jumlah[$i] * $harga
                ];
                $this->db->insert('barang_keluar_detail', $data_detail);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menyimpan barang keluar');
            redirect('admin/barang-keluar/tambah');
        } else {
            $this->session->set_flashdata('success', 'Barang keluar berhasil disimpan');
            redirect('admin/barang-keluar');
        }
    }

    public function posting($id)
    {
        $result = $this->M_barang_keluar->posting($id);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('admin/barang-keluar');
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
