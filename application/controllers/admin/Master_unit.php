<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Master_unit extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_unit');
        $this->load->library('form_validation');
    }

    public function index()
    {
        $data['title'] = 'Master Unit';
        $data['unit'] = $this->M_unit->get_all();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/master_unit/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah Unit';

        $this->form_validation->set_rules('kode_unit', 'Kode Unit', 'required|is_unique[master_unit.kode_unit]');
        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required');

        if ($this->form_validation->run() == FALSE) {

            $this->load->view('dist/_partials/header', $data);
            $this->load->view('admin/master_unit/tambah', $data);
            $this->load->view('dist/_partials/footer');
        } else {
            $data_insert = [
                'kode_unit' => $this->input->post('kode_unit'),
                'nama_unit' => $this->input->post('nama_unit'),
                'penanggung_jawab' => $this->input->post('penanggung_jawab'),
                'status' => 'aktif'
            ];

            if ($this->M_unit->insert($data_insert)) {
                $this->session->set_flashdata('success', 'Data unit berhasil ditambahkan');
                redirect('admin/master-unit');
            } else {
                $this->session->set_flashdata('error', 'Gagal menambahkan data unit');
                redirect('admin/master-unit/tambah');
            }
        }
    }

    public function edit($id)
    {
        $data['title'] = 'Edit Unit';
        $data['unit'] = $this->M_unit->get_by_id($id);

        if (!$data['unit']) {
            show_404();
        }

        $this->form_validation->set_rules('nama_unit', 'Nama Unit', 'required');

        if ($this->input->post('kode_unit') != $data['unit']->kode_unit) {
            $this->form_validation->set_rules('kode_unit', 'Kode Unit', 'required|is_unique[master_unit.kode_unit]');
        } else {
            $this->form_validation->set_rules('kode_unit', 'Kode Unit', 'required');
        }

        if ($this->form_validation->run() == FALSE) {
            $this->load->view('dist/_partials/header', $data);
            $this->load->view('admin/master_unit/edit', $data);
            $this->load->view('dist/_partials/footer');
        } else {
            $data_update = [
                'kode_unit' => $this->input->post('kode_unit'),
                'nama_unit' => $this->input->post('nama_unit'),
                'penanggung_jawab' => $this->input->post('penanggung_jawab'),
                'status' => $this->input->post('status')
            ];

            if ($this->M_unit->update($id, $data_update)) {
                $this->session->set_flashdata('success', 'Data unit berhasil diupdate');
                redirect('admin/master-unit');
            } else {
                $this->session->set_flashdata('error', 'Gagal mengupdate data unit');
                redirect('admin/master-unit/edit/' . $id);
            }
        }
    }

    public function hapus($id)
    {
        if ($this->M_unit->delete($id)) {
            $this->session->set_flashdata('success', 'Data unit berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus data unit');
        }
        redirect('admin/master-unit');
    }
}
