<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Katalog_barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        // Check login and role
        if (!$this->session->userdata('logged_in')) {
            redirect('Auth');
        }

        if ($this->session->userdata('role') != 2) {
            redirect('Auth');
        }

        $this->load->model('M_barang');
        $this->load->library('pagination');
        $this->load->helper('text'); // For character_limiter
    }

    public function index($page = 0)
    {
        $data['title'] = 'Katalog Barang';

        // Get search and filter parameters
        $search = $this->input->get('search', TRUE);
        $kategori = $this->input->get('kategori', TRUE);

        // Items per page
        $per_page = 12;

        // Get total rows for pagination
        $total_rows = $this->M_barang->count_katalog($search, $kategori);

        // Pagination Configuration
        $config['base_url'] = base_url('user/katalog-barang/index');
        $config['total_rows'] = $total_rows;
        $config['per_page'] = $per_page;
        $config['uri_segment'] = 4;
        $config['use_page_numbers'] = FALSE;
        $config['enable_query_strings'] = TRUE;
        $config['reuse_query_string'] = TRUE;
        $config['page_query_string'] = FALSE;

        // Bootstrap 4 Pagination Styling
        $config['full_tag_open'] = '<nav><ul class="pagination justify-content-center">';
        $config['full_tag_close'] = '</ul></nav>';

        $config['first_link'] = 'First';
        $config['first_tag_open'] = '<li class="page-item">';
        $config['first_tag_close'] = '</li>';

        $config['last_link'] = 'Last';
        $config['last_tag_open'] = '<li class="page-item">';
        $config['last_tag_close'] = '</li>';

        $config['next_link'] = '&raquo;';
        $config['next_tag_open'] = '<li class="page-item">';
        $config['next_tag_close'] = '</li>';

        $config['prev_link'] = '&laquo;';
        $config['prev_tag_open'] = '<li class="page-item">';
        $config['prev_tag_close'] = '</li>';

        $config['cur_tag_open'] = '<li class="page-item active"><a class="page-link" href="#">';
        $config['cur_tag_close'] = '</a></li>';

        $config['num_tag_open'] = '<li class="page-item">';
        $config['num_tag_close'] = '</li>';

        $config['attributes'] = array('class' => 'page-link');

        // Initialize pagination
        $this->pagination->initialize($config);

        // Get data with limit and offset
        $data['barang'] = $this->M_barang->get_katalog($per_page, $page, $search, $kategori);

        // Get kategori list for filter
        $data['kategori_list'] = $this->M_barang->get_kategori_list();

        // Pass parameters to view
        $data['pagination'] = $this->pagination->create_links();
        $data['search'] = $search;
        $data['kategori'] = $kategori;
        $data['total_rows'] = $total_rows;
        $data['current_page'] = ($page / $per_page) + 1;
        $data['total_pages'] = ceil($total_rows / $per_page);

        // Get cart count
        $data['cart_count'] = $this->get_cart_count();

        // Load views

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('user/katalog_barang/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    // Add item to cart (AJAX)
    public function add_to_cart()
    {
        // Check if request is AJAX
        if (!$this->input->is_ajax_request()) {
            show_404();
        }

        $response = array();

        // Get input
        $barang_id = $this->input->post('barang_id', TRUE);
        $jumlah = (int)$this->input->post('jumlah', TRUE);

        // Validate input
        if (!$barang_id || $jumlah < 1) {
            $response = [
                'status' => 'error',
                'message' => 'Data tidak valid'
            ];
            echo json_encode($response);
            return;
        }

        // Get barang detail
        $barang = $this->M_barang->get_by_id($barang_id);

        if (!$barang || $barang->status != 'aktif') {
            $response = [
                'status' => 'error',
                'message' => 'Barang tidak ditemukan atau tidak aktif'
            ];
            echo json_encode($response);
            return;
        }

        // Get current cart from session
        $cart = $this->session->userdata('cart');
        if (!is_array($cart)) {
            $cart = array();
        }

        // Check if item already exists in cart
        $found = false;
        foreach ($cart as $key => &$item) {
            if ($item['id'] == $barang_id) {
                // Update quantity
                $cart[$key]['jumlah'] += $jumlah;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $new_item = [
                'id' => $barang->id,
                'kode_nusp' => $barang->kode_nusp,
                'nama_nusp' => $barang->nama_nusp,
                'satuan' => $barang->satuan,
                'gambar' => $barang->gambar,
                'jumlah' => $jumlah
            ];
            $cart[] = $new_item;
        }

        // Save cart to session
        $this->session->set_userdata('cart', $cart);

        // Return response
        $response = [
            'status' => 'success',
            'message' => 'Barang berhasil ditambahkan ke keranjang',
            'cart_count' => count($cart),
            'cart_items' => array_sum(array_column($cart, 'jumlah'))
        ];

        echo json_encode($response);
    }

    // Get cart count
    private function get_cart_count()
    {
        $cart = $this->session->userdata('cart');
        if (!is_array($cart)) {
            return 0;
        }
        return count($cart);
    }

    // View cart (for debugging)
    public function view_cart()
    {
        $cart = $this->session->userdata('cart');
        echo '<pre>';
        print_r($cart);
        echo '</pre>';
    }

    public function detail($id)
    {
        $data['barang'] = $this->M_barang->get_by_id($id);
        if (!$data['barang']) {
            show_404();
        }

        echo json_encode($data['barang']);
    }
}
