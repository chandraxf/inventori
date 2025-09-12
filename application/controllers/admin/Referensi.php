<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Referensi extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        $this->load->model('Data_model', 'data');
    }

    public function index()
    {
        $data['title'] = 'Referensi';
        $data['ref'] = $this->db->query("SELECT * FROM referensi")->result();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/referensi/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function edit_satuan($id = null)
    {
        $data['title'] = 'Edit - Referensi';
        $data['ref'] = $this->db->query("SELECT * FROM referensi WHERE id = $id")->row_array();
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/referensi/edit_satuan', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function simpan()
    {
        $cek = $this->db->query("SELECT * FROM referensi WHERE jenis = 1 AND isi = '" . $this->input->post('satuan') . "'")->row();
        if ($cek) {
            $this->session->set_flashdata('error', 'Data referensi sudah ada.');
        }else{
            $data = [
                'jenis' => 1,
                'isi' => $this->input->post('satuan'),
            ];
            $this->data->add($data, 'referensi');
            $this->session->set_flashdata('success', 'Data referensi berhasil ditambahkan.');
        }
        redirect('admin/referensi');
    }
    public function edit($id)
    {
        $data['ref'] = $this->db->get_where('referensi', ['id' => $id])->row();

        if (!$data['ref']) {
            show_404();
        }

        $this->load->view('admin/referensi/edit', $data);
    }

    public function update_satuan()
    {
        $id = $this->input->post('id');
        $isi = $this->input->post('satuan');

        $this->db->where('id', $id);
        $this->db->update('referensi', [
            'isi' => $isi,
        ]);

        $this->session->set_flashdata('success', 'Data satuan berhasil diperbarui.');
        redirect('admin/referensi');
    }
    public function delete_satuan($id){
        $this->db->where('id', $id);
        $this->db->delete('referensi');
        $this->session->set_flashdata('success', 'Data satuan berhasil dihapus.');
        redirect('admin/referensi');
    }
}
