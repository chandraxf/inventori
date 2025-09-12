<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check login
        // if(!$this->session->userdata('logged_in')) {
        //     redirect('login');
        // }
        $this->load->model('M_laporan');
    }

    public function index()
    {
        $data['title'] = 'Dashboard';

        // Get dashboard statistics
        $data['total_barang'] = $this->db->count_all('master_barang');
        $data['total_unit'] = $this->db->count_all('master_unit');

        // Get current month statistics
        $periode = date('Y-m');
        $this->db->select('COUNT(DISTINCT barang_id) as total_item, SUM(stok_akhir) as total_qty, SUM(nilai_persediaan) as total_nilai');
        $this->db->where('periode', $periode);
        $stok = $this->db->get('stok_barang')->row();

        $data['total_item'] = $stok->total_item ?? 0;
        $data['total_qty'] = $stok->total_qty ?? 0;
        $data['total_nilai'] = $stok->total_nilai ?? 0;

        // Get barang perlu restock
        $data['need_restock'] = $this->M_laporan->get_barang_minimum();

        // Get recent transactions
        $data['recent_masuk'] = $this->db->order_by('created_at', 'DESC')->limit(5)->get('barang_masuk')->result();
        $data['recent_keluar'] = $this->db->order_by('created_at', 'DESC')->limit(5)->get('barang_keluar')->result();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/dashboard', $data);
        $this->load->view('dist/_partials/footer');
    }
}
