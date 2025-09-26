<?php
// =============================================
// 1. CONTROLLERS/ADMIN/PERMINTAAN.PHP
// =============================================
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if admin logged in
        if (!$this->session->userdata('logged_in') || $this->session->userdata('role') != 1) {
            redirect('login');
        }
        $this->load->model(['M_permintaan_admin', 'M_barang', 'M_unit']);
        $this->load->helper('text');
    }

    public function index()
    {
        $data['title'] = 'Permintaan Barang';

        // Get filter parameters
        $status = $this->input->get('status');
        $start_date = $this->input->get('start_date');
        $end_date = $this->input->get('end_date');

        // Get permintaan list
        $data['permintaan'] = $this->M_permintaan_admin->get_all_permintaan($status, $start_date, $end_date);

        // Pass filter values to view
        $data['filter_status'] = $status;
        $data['filter_start'] = $start_date;
        $data['filter_end'] = $end_date;

        // Count by status for badges
        $data['count_pending'] = $this->M_permintaan_admin->count_by_status('pending');
        $data['count_approved'] = $this->M_permintaan_admin->count_by_status('approved');
        $data['count_rejected'] = $this->M_permintaan_admin->count_by_status('rejected');

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/permintaan/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function detail($id)
    {
        if (!$id) {
            show_404();
        }

        $data['title'] = 'Detail Permintaan';
        $data['permintaan'] = $this->M_permintaan_admin->get_by_id($id);

        if (!$data['permintaan']) {
            show_404();
        }

        $data['detail'] = $this->M_permintaan_admin->get_detail($id);

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/permintaan/detail', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function approve($id)
    {
        if (!$id) {
            show_404();
        }

        $data['title'] = 'Approve Permintaan';
        $data['permintaan'] = $this->M_permintaan_admin->get_by_id($id);

        if (!$data['permintaan'] || $data['permintaan']->status != 'pending') {
            $this->session->set_flashdata('error', 'Permintaan tidak valid atau sudah diproses');
            redirect('admin/permintaan');
        }

        $data['detail'] = $this->M_permintaan_admin->get_detail_with_stok($id);

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/permintaan/approve', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function process_approve()
    {
        $permintaan_id = $this->input->post('permintaan_id');
        $action = $this->input->post('action'); // approve or reject

        if (!$permintaan_id) {
            $this->session->set_flashdata('error', 'Data tidak valid');
            redirect('admin/permintaan');
        }

        $this->db->trans_start();

        if ($action == 'reject') {
            // Reject all
            $update_data = [
                'status' => 'rejected',
                'approved_by' => $this->session->userdata('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id', $permintaan_id);
            $this->db->update('permintaan_barang', $update_data);

            // Update all details to 0
            $this->db->where('permintaan_id', $permintaan_id);
            $this->db->update('permintaan_barang_detail', ['jumlah_disetujui' => 0]);
        } else {
            // Process approval
            $detail_ids = $this->input->post('detail_id');
            $jumlah_disetujui = $this->input->post('jumlah_disetujui');
            $keterangan = $this->input->post('keterangan');

            $total_approved = 0;
            $total_requested = 0;

            // Update each detail
            foreach ($detail_ids as $key => $detail_id) {
                $qty_approved = (int)$jumlah_disetujui[$key];
                $note = $keterangan[$key];

                // Get original request qty
                $detail = $this->db->get_where('permintaan_barang_detail', ['id' => $detail_id])->row();
                $total_requested += $detail->jumlah_diminta;
                $total_approved += $qty_approved;

                // Update detail
                $this->db->where('id', $detail_id);
                $this->db->update('permintaan_barang_detail', [
                    'jumlah_disetujui' => $qty_approved,
                    'keterangan' => $note
                ]);
            }

            // Determine status
            if ($total_approved == 0) {
                $status = 'rejected';
            } elseif ($total_approved < $total_requested) {
                $status = 'partial';
            } else {
                $status = 'approved';
            }

            // Update header
            $update_data = [
                'status' => $status,
                'approved_by' => $this->session->userdata('user_id'),
                'approved_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->db->where('id', $permintaan_id);
            $this->db->update('permintaan_barang', $update_data);

            // If approved, check if should create barang keluar
            if ($this->input->post('create_barang_keluar') && ($status == 'approved' || $status == 'partial')) {
                $this->create_barang_keluar($permintaan_id);
            }
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal memproses permintaan');
        } else {
            $message = $action == 'reject' ? 'Permintaan berhasil ditolak' : 'Permintaan berhasil diproses';
            $this->session->set_flashdata('success', $message);
        }

        redirect('admin/permintaan');
    }

    private function create_barang_keluar($permintaan_id)
    {
        // Get permintaan data
        $permintaan = $this->M_permintaan_admin->get_by_id($permintaan_id);
        $details = $this->M_permintaan_admin->get_approved_details($permintaan_id);

        if (!$permintaan || empty($details)) {
            return false;
        }

        // Generate nomor barang keluar
        $nomor_keluar = $this->generate_nomor_keluar();

        // Insert barang keluar header
        $header_data = [
            'nomor_transaksi' => $nomor_keluar,
            'tanggal_keluar' => date('Y-m-d'),
            'jenis_keluar' => 'pemakaian',
            'nomor_permintaan' => $permintaan->nomor_permintaan,
            'nama_penerima' => $permintaan->nama_user,
            'keterangan' => 'Dari permintaan: ' . $permintaan->nomor_permintaan,
            'status' => 'draft',
            'user_input' => $this->session->userdata('nama'),
            'total_nilai' => 0,
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('barang_keluar', $header_data);
        $barang_keluar_id = $this->db->insert_id();

        // Insert details
        $total_nilai = 0;
        foreach ($details as $d) {
            if ($d->jumlah_disetujui > 0) {
                // Get current price
                $periode = date('Y-m');
                $stok = $this->db->select('harga_rata_rata')
                    ->where('barang_id', $d->barang_id)
                    ->where('periode', $periode)
                    ->get('stok_barang')
                    ->row();

                $harga = $stok ? $stok->harga_rata_rata : 0;
                $subtotal = $d->jumlah_disetujui * $harga;

                $detail_data = [
                    'barang_keluar_id' => $barang_keluar_id,
                    'barang_id' => $d->barang_id,
                    'jumlah' => $d->jumlah_disetujui,
                    'harga_satuan' => $harga,
                    'total' => $subtotal
                ];

                $this->db->insert('barang_keluar_detail', $detail_data);
                $total_nilai += $subtotal;
            }
        }

        // Update total nilai
        $this->db->where('id', $barang_keluar_id);
        $this->db->update('barang_keluar', ['total_nilai' => $total_nilai]);

        return $barang_keluar_id;
    }

    private function generate_nomor_keluar()
    {
        $prefix = 'BK-' . date('Ym') . '-';

        $this->db->select('nomor_transaksi');
        $this->db->from('barang_keluar');
        $this->db->like('nomor_transaksi', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if ($last) {
            $last_number = (int)substr($last->nomor_transaksi, -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }
}
