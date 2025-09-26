<?php
// =============================================
// 7. CONTROLLERS/ADMIN/STOK_OPNAME.PHP
// =============================================
defined('BASEPATH') or exit('No direct script access allowed');

class Stok_opname extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model(['M_stok_opname', 'M_barang']);
    }

    public function index()
    {
        $data['title'] = 'Stok Opname';
        $data['stok_opname'] = $this->M_stok_opname->get_all();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/stok_opname/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Stok Opname';
        $data['barang_with_stok'] = $this->M_stok_opname->get_barang_with_stok();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/stok_opname/tambah', $data);
        $this->load->view('dist/_partials/footer');
    }

    // public function simpan() {
    //     $this->db->trans_start();

    //     // Insert header
    //     $data_header = [
    //         'tanggal_opname' => $this->input->post('tanggal_opname'),
    //         'periode' => $this->input->post('periode'),
    //         'jenis_opname' => $this->input->post('jenis_opname'),
    //         'petugas' => $this->input->post('petugas'),
    //         'keterangan' => $this->input->post('keterangan'),
    //         'status' => 'draft',
    //         'user_input' => $this->session->userdata('username') ?? 'admin',
    //         'total_selisih_nilai' => 0
    //     ];

    //     $this->db->insert('stok_opname', $data_header);
    //     $opname_id = $this->db->insert_id();

    //     // Insert detail
    //     $barang_id = $this->input->post('barang_id');
    //     $stok_sistem = $this->input->post('stok_sistem');
    //     $stok_fisik = $this->input->post('stok_fisik');
    //     $harga_satuan = $this->input->post('harga_satuan');
    //     $kondisi = $this->input->post('kondisi');

    //     $total_selisih_nilai = 0;

    //     for ($i = 0; $i < count($barang_id); $i++) {
    //         $selisih = $stok_fisik[$i] - $stok_sistem[$i];
    //         $nilai_selisih = $selisih * $harga_satuan[$i];

    //         // Only insert if there's a difference
    //         if ($selisih != 0) {
    //             $data_detail = [
    //                 'stok_opname_id' => $opname_id,
    //                 'barang_id' => $barang_id[$i],
    //                 'stok_sistem' => $stok_sistem[$i],
    //                 'stok_fisik' => $stok_fisik[$i],
    //                 'selisih' => $selisih,
    //                 'harga_satuan' => $harga_satuan[$i],
    //                 'nilai_selisih' => $nilai_selisih,
    //                 'kondisi' => $kondisi[$i]
    //             ];
    //             $this->db->insert('stok_opname_detail', $data_detail);
    //             $total_selisih_nilai += $nilai_selisih;
    //         }
    //     }

    //     // Update total selisih nilai
    //     $this->db->where('id', $opname_id);
    //     $this->db->update('stok_opname', ['total_selisih_nilai' => $total_selisih_nilai]);

    //     $this->db->trans_complete();

    //     if ($this->db->trans_status() === FALSE) {
    //         $this->session->set_flashdata('error', 'Gagal menyimpan stok opname');
    //         redirect('admin/stok-opname/tambah');
    //     } else {
    //         $this->session->set_flashdata('success', 'Stok opname berhasil disimpan');
    //         redirect('admin/stok-opname');
    //     }
    // }
    public function simpan()
    {
        $this->db->trans_start();

        // Insert header
        $data_header = [
            'tanggal_opname' => $this->input->post('tanggal_opname'),
            'periode' => $this->input->post('periode'),
            'jenis_opname' => $this->input->post('jenis_opname'),
            'petugas' => $this->input->post('petugas'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => 'draft',
            'user_input' => $this->session->userdata('username') ?? 'admin',
            'total_selisih_nilai' => 0
        ];

        $this->db->insert('stok_opname', $data_header);
        $opname_id = $this->db->insert_id();

        // Ambil semua data detail dari input
        $barang_id     = $this->input->post('barang_id');
        $stok_sistem   = $this->input->post('stok_sistem');
        $stok_fisik    = $this->input->post('stok_fisik');
        $harga_satuan  = $this->input->post('harga_satuan');
        $kondisi       = $this->input->post('kondisi');

        $total_selisih_nilai = 0;

        // Loop dengan foreach
        foreach ($barang_id as $i => $id) {
            $selisih       = $stok_fisik[$i] - $stok_sistem[$i];
            $nilai_selisih = $selisih * $harga_satuan[$i];

            $data_detail = [
                'stok_opname_id' => $opname_id,
                'barang_id'      => $id,
                'stok_sistem'    => $stok_sistem[$i],
                'stok_fisik'     => $stok_fisik[$i],
                'selisih'        => $selisih,
                'harga_satuan'   => $harga_satuan[$i],
                'nilai_selisih'  => $nilai_selisih,
                'kondisi'        => $kondisi[$i]
            ];
            $this->db->insert('stok_opname_detail', $data_detail);

            $total_selisih_nilai += $nilai_selisih;
        }

        // Update total selisih nilai
        $this->db->where('id', $opname_id);
        $this->db->update('stok_opname', ['total_selisih_nilai' => $total_selisih_nilai]);

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal menyimpan stok opname');
            redirect('admin/stok-opname/tambah');
        } else {
            $this->session->set_flashdata('success', 'Stok opname berhasil disimpan');
            redirect('admin/stok-opname');
        }
    }



    public function posting($id)
    {
        $result = $this->M_stok_opname->posting($id);

        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }

        redirect('admin/stok-opname');
    }
    public function hapus($id)
    {
        $result = $this->M_stok_opname->hapus($id);
        if ($result['status']) {
            $this->session->set_flashdata('success', $result['message']);
        } else {
            $this->session->set_flashdata('error', $result['message']);
        }
        redirect('admin/stok-opname');
    }
}
