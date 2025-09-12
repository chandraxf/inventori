<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan_admin extends CI_Controller
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
            'title' => "Daftar Permintaan"
        );
        $data['riwayat'] = $this->db->query("
                    SELECT 
                        p.*, u.NAMA,
                        (SELECT COUNT(ID) FROM detail_permintaan dp WHERE dp.ID_PERMINTAAN = p.ID) AS total_detail
                    FROM permintaan p
                    LEFT JOIN user u ON u.ID = p.USER_ID
                    ORDER BY p.CREATED_AT DESC
                ")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('permintaan_admin/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function lihat($id)
    {
        $id_asli = unhash_id($id);
        $data = array(
            'title' => "Lihat Permintaan"
        );
        $ID = $this->session->userdata('ID');
        $data['ket'] = $this->db->query("SELECT * FROM permintaan WHERE ID = $id_asli")->row_array();
        $data['data'] = $this->db->query("
                    SELECT dt.*, m.NAMA_BARANG, m.STOK FROM detail_permintaan dt LEFT JOIN master m ON dt.KODE = m.KODE WHERE dt.ID_PERMINTAAN = $id_asli
                ")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('permintaan_admin/lihat', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function update_status()
    {
        $id_detail = $this->input->post('id_detail'); // array ID_DETAIL
        $status = $this->input->post('status');       // array STATUS (1 atau 2)
        $jumlah = $this->input->post('jumlah');
        $getID = $this->db->query("SELECT ID_PERMINTAAN FROM detail_permintaan WHERE ID = $id_detail[0]")->row_array();
        $this->db->where('ID', $getID['ID_PERMINTAAN']);
        $this->db->update('permintaan', ['STATUS' => 1]);

        if ($id_detail && $status && count($id_detail) == count($status)) {
            foreach ($id_detail as $i => $id) {
                $this->db->where('ID', $id);
                if($status[$i] == 1){
                    $this->db->update('detail_permintaan', ['STATUS' => $status[$i], 'JUMLAH_DISETUJUI' => $jumlah[$i]]);
                }else{
                    $this->db->update('detail_permintaan', ['STATUS' => $status[$i]]);
                }
            }

            $this->session->set_flashdata('success', 'Status berhasil diperbarui.');
        } else {
            $this->session->set_flashdata('error', 'Gagal memperbarui status.');
        }

        redirect('Permintaan_admin');
    }
}
