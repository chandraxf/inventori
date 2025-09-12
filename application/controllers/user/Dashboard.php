<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Dashboard extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        // Check if user logged in and role = 2
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        if ($this->session->userdata('role') != 2) {
            // Redirect to appropriate dashboard
            if ($this->session->userdata('role') == 1) {
                redirect('admin');
            } elseif ($this->session->userdata('role') == 3) {
                redirect('pptk');
            } else {
                redirect('login');
            }
        }

        $this->load->model(['M_barang', 'M_permintaan', 'M_user']);
    }

    public function index()
    {
        $data['title'] = 'Dashboard User';

        // Get user data from session
        $user_id = $this->session->userdata('user_id');

        // Get user details if needed
        $data['user'] = [
            'id' => $user_id,
            'nama' => $this->session->userdata('nama'),
            'username' => $this->session->userdata('username'),
            'img' => $this->session->userdata('img'),
            'role' => $this->session->userdata('role')
        ];

        // Get statistics
        $data['total_permintaan'] = $this->M_permintaan->count_user_permintaan($user_id);
        $data['permintaan_pending'] = $this->M_permintaan->count_pending($user_id);
        $data['permintaan_approved'] = $this->M_permintaan->count_approved($user_id);
        $data['permintaan_rejected'] = $this->M_permintaan->count_rejected($user_id);

        // Get recent permintaan (limit 5)
        $data['recent_permintaan'] = $this->M_permintaan->get_recent_user($user_id, 5);

        // Get popular items (optional - most requested items)
        $data['popular_items'] = $this->M_barang->get_popular_items(5);

        // Cart count
        $data['cart_count'] = count($this->session->userdata('cart') ?: []);

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('user/index', $data);
        $this->load->view('dist/_partials/footer');
    }
}
