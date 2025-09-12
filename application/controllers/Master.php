<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('NAMA')) {
            redirect('Auth');
        }
        $this->load->model('Data_model', 'data');
    }

    public function index()
    {
        $data = array(
            'title' => "Data Master Barang"
        );
        $filter = $this->input->post('child_kode');
        if ($filter) {
            $data['data'] = $this->db->query('SELECT * FROM master WHERE KODE_REK LIKE "%' . $filter . '%"')->result();
        } else {
            $data['data'] = $this->db->query('SELECT * FROM master LIMIT 100')->result();
        }
        $data['data_filter'] = $this->db->query("SELECT KODE_REK, NAMA FROM master WHERE KODE_REK LIKE '%.%.%.%.%.%' AND KODE_REK NOT LIKE '%.%.%.%.%.%.%' GROUP BY KODE_REK")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('master/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function barang_masuk()
    {
        $data = array(
            'title' => "Barang Masuk"
        );
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('master/barang_masuk', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function detail($id)
    {
        $id_asli = unhash_id($id);
        $data = array(
            'title' => "Edit Barang"
        );
        $data['data'] = $this->db->query("SELECT * FROM master WHERE ID = $id_asli")->row_array();
        $data['id_asli'] = $id;
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('master/edit', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function riwayat()
    {
        $data = array(
            'title' => "Riwayat"
        );
        $data['data'] = $this->db->query("SELECT r.*, m.NAMA_BARANG FROM riwayat r LEFT JOIN master m ON r.KODE = m.KODE")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('master/riwayat', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function update()
    {
        $config['upload_path'] = './assets/img/gambar/';
        $config['allowed_types'] = 'png|jpeg|heic|jpg';
        $config['encrypt_name'] = TRUE;
        $config['max_size'] = 5120;
        $this->load->library('upload', $config);
        $file_data1 = null;
        if ($this->upload->do_upload('gambar')) {
            $file_data1 = $this->upload->data();
            $this->compress_image($file_data1['full_path']);
        } else {
            log_message('error', 'Upload failed for img1: ' . $this->upload->display_errors());
        }
        $id = unhash_id($this->input->post('ID'));
        $where = [
            'ID' => $id
        ];
        $data = [
            'GAMBAR' => !empty($file_data1) ? $file_data1['file_name'] : ''
        ];
        $this->data->update($where, $data, 'master');
        $this->session->set_flashdata('sukses', 'Data berhasil diupdate');
        redirect('Master');
    }
    private function compress_image($path)
    {
        $config['image_library'] = 'gd2';
        $config['source_image'] = $path;
        $config['quality'] = '70%';
        $config['maintain_ratio'] = TRUE;
        $config['width'] = 600;
        $config['height'] = 400;

        $this->load->library('image_lib', $config);

        if (!$this->image_lib->resize()) {
            log_message('error', 'Image compression failed: ' . $this->image_lib->display_errors());
        }

        $this->image_lib->clear();
    }
    public function getFilter()
    {
        $kode = $this->input->get('kode');
        $data['data_filter'] = $this->db->query("SELECT KODE_REK, NAMA FROM master WHERE KODE_REK LIKE '$kode.%' GROUP BY KODE_REK")->result();
        echo json_encode($data);
    }
    public function getData()
    {
        $kode = $this->input->get('kode');
        $data['data'] = $this->db->query('SELECT * FROM master WHERE KODE_REK LIKE "%' . $kode . '%"')->result();
        echo json_encode($data);
    }

    public function search_barang()
    {
        // Ambil parameter pencarian
        // $search = 'Aspal';
        $search = $this->input->get('search');
        $page = (int)$this->input->get('page') ?: 1;
        $limit = 10; // Jumlah item per halaman
        $offset = ($page - 1) * $limit;

        if (empty($search) || strlen($search) < 2) {
            echo json_encode([
                'results' => [],
                'pagination' => ['more' => false]
            ]);
            return;
        }

        try {
            // Query pencarian barang - hanya ambil ID, nama, dan kode
            $this->db->select('ID, NAMA_BARANG as nama, KODE as kode');
            $this->db->from('master'); // Sesuaikan nama tabel
            $this->db->group_start();
            $this->db->like('NAMA_BARANG', $search);
            $this->db->or_like('KODE', $search);
            $this->db->group_end();
            $this->db->limit($limit, $offset);
            $this->db->order_by('NAMA_BARANG', 'ASC');

            $query = $this->db->get();
            $results = $query->result();

            // Cek apakah masih ada data berikutnya
            $this->db->select('COUNT(*) as total');
            $this->db->from('master');
            $this->db->group_start();
            $this->db->like('NAMA_BARANG', $search);
            $this->db->or_like('KODE', $search);
            $this->db->group_end();
            $total_query = $this->db->get();
            $total = $total_query->row()->total;

            $has_more = ($offset + $limit) < $total;

            // Format data untuk Select2 - hanya ID dan kode
            $formatted_results = [];
            foreach ($results as $item) {
                $formatted_results[] = [
                    'id' => $item->ID,           // ID untuk value
                    'text' => $item->nama,       // Nama untuk display di select
                    'nama' => $item->nama,       // Nama untuk template
                    'kode' => $item->kode        // Kode untuk auto-fill
                ];
            }

            echo json_encode([
                'results' => $formatted_results,
                'pagination' => [
                    'more' => $has_more
                ]
            ]);
        } catch (Exception $e) {
            echo json_encode([
                'results' => [],
                'pagination' => ['more' => false],
                'error' => 'Terjadi kesalahan dalam pencarian'
            ]);
        }
    }
}
