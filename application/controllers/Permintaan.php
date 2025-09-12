<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan extends CI_Controller
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
            'title' => "Dashboard"
        );
        $ID = $this->session->userdata('ID');
        $data['riwayat'] = $this->db->query("
                    SELECT 
                        p.*, 
                        (SELECT COUNT(ID) FROM detail_permintaan dp WHERE dp.ID_PERMINTAAN = p.ID) AS total_detail
                    FROM permintaan p
                    WHERE p.USER_ID = $ID
                    ORDER BY p.CREATED_AT DESC
                ")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('permintaan/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function lihat($id)
    {
        $id_asli = unhash_id($id);
        $data = array(
            'title' => "Lihat Permintaan"
        );
        $ID = $this->session->userdata('ID');
        $data['data'] = $this->db->query("
                    SELECT dt.*, m.* FROM detail_permintaan dt LEFT JOIN master m ON dt.ID_BARANG = m.ID WHERE dt.ID_PERMINTAAN = $id_asli
                ")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('permintaan/lihat', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function ajukan()
    {
        $data = array(
            'title' => "Pengajuan Permintaan Barang"
        );
        $data['data_filter'] = $this->db->query("SELECT KODE_REK, NAMA FROM master WHERE KODE_REK LIKE '%.%.%.%.%.%' AND KODE_REK NOT LIKE '%.%.%.%.%.%.%' GROUP BY KODE_REK")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('permintaan/ajukan', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function simpan_barang()
    {
        // Simpan data permintaan utama
        $data = [
            'USER_ID' => $this->session->userdata('ID'),
            'KET' => $this->input->post('keterangan'),
            'STATUS' => 0
        ];

        // Simpan ke tabel permintaan
        $this->data->add($data, 'permintaan');
        $last_id = $this->db->insert_id();

        $id_barang = $this->input->post('id_barang'); // Menggunakan ID_BARANG
        $jumlah = $this->input->post('jumlah');

        foreach ($id_barang as $i => $id) {
            $jumlah_minta = (int)$jumlah[$i];

            // Ambil data barang dari tabel master menggunakan ID
            $barang = $this->db->get_where('master', ['ID' => $id])->row();

            if (!$barang) {
                log_message('error', "ID barang tidak ditemukan: {$id}");
                continue;
            }

            $stok_awal = (int)$barang->STOK;
            $stok_akhir = $stok_awal - $jumlah_minta;

            // Update stok di tabel master menggunakan ID
            $this->db->where('ID', $id);
            $update_result = $this->db->update('master', ['STOK' => $stok_akhir]);

            if (!$update_result) {
                log_message('error', "Gagal update stok untuk ID: {$id}");
                continue;
            }

            // Simpan ke tabel detail_permintaan menggunakan ID_BARANG
            $data_barang = [
                'ID_PERMINTAAN' => $last_id,
                'ID_BARANG' => $id, // Menggunakan ID_BARANG
                'JUMLAH' => $jumlah_minta,
                'STATUS' => 0
            ];
            $this->data->add($data_barang, 'detail_permintaan');

            $id_detail = $this->db->insert_id(); // Ambil ID detail yang baru saja disimpan

            // Simpan ke tabel riwayat menggunakan ID_BARANG
            $riwayat = [
                'ID_DETAIL' => $id_detail,
                'ID_BARANG' => $id, // Menggunakan ID_BARANG
                'STOK_AWAL' => $stok_awal,
                'JUMLAH' => $jumlah_minta,
                'STOK_AKHIR' => $stok_akhir,
                'STATUS' => 0,
                'CREATED_AT' => date('Y-m-d H:i:s')
            ];
            $this->db->insert('riwayat', $riwayat);
        }

        $this->session->set_flashdata('sukses', 'Data berhasil disimpan');
        redirect('permintaan');
    }


    // Method tambahan untuk debugging
    public function debug_form_data()
    {
        echo "<pre>";
        echo "POST Data:\n";
        print_r($this->input->post());
        echo "\n\nKode Array:\n";
        print_r($this->input->post('kode'));
        echo "\n\nJumlah Array:\n";
        print_r($this->input->post('jumlah'));
        echo "</pre>";
        die();
    }
}
