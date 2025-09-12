<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Permintaan_barang extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if ($this->session->userdata('role') != 2) {
            redirect('Auth');
        }
        $this->load->model(['M_permintaan', 'M_barang']);
    }

    public function index()
    {
        $data['title'] = 'Permintaan Barang Saya';
        $data['permintaan'] = $this->M_permintaan->get_user_permintaan($this->session->userdata('user_id'));

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('user/permintaan_barang/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function buat()
    {
        $data['title'] = 'Buat Permintaan Barang';

        // Get cart from session
        $data['cart_items'] = $this->session->userdata('cart') ?: [];

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('user/permintaan_barang/buat', $data);
        $this->load->view('dist/_partials/footer');
    }

    // Add to cart (AJAX)
    public function add_to_cart()
    {
        $barang_id = $this->input->post('barang_id');
        $jumlah = $this->input->post('jumlah') ?: 1;

        // Get barang detail
        $barang = $this->M_barang->get_by_id($barang_id);
        if (!$barang) {
            echo json_encode(['status' => 'error', 'message' => 'Barang tidak ditemukan']);
            return;
        }

        // Get current cart
        $cart = $this->session->userdata('cart') ?: [];

        // Check if item exists in cart
        $found = false;
        foreach ($cart as &$item) {
            if ($item['id'] == $barang_id) {
                $item['jumlah'] += $jumlah;
                $found = true;
                break;
            }
        }

        // Add new item if not found
        if (!$found) {
            $cart[] = [
                'id' => $barang->id,
                'kode_nusp' => $barang->kode_nusp,
                'nama_nusp' => $barang->nama_nusp,
                'satuan' => $barang->satuan,
                'gambar' => $barang->gambar,
                'jumlah' => $jumlah
            ];
        }

        // Save to session
        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            'status' => 'success',
            'message' => 'Barang ditambahkan ke keranjang',
            'cart_count' => count($cart),
            'cart_items' => array_sum(array_column($cart, 'jumlah'))
        ]);
    }

    // Update cart item (AJAX)
    public function update_cart()
    {
        $barang_id = $this->input->post('barang_id');
        $jumlah = $this->input->post('jumlah');

        $cart = $this->session->userdata('cart') ?: [];

        foreach ($cart as &$item) {
            if ($item['id'] == $barang_id) {
                if ($jumlah > 0) {
                    $item['jumlah'] = $jumlah;
                } else {
                    // Remove if quantity is 0
                    $cart = array_filter($cart, function ($i) use ($barang_id) {
                        return $i['id'] != $barang_id;
                    });
                    $cart = array_values($cart); // Reindex
                }
                break;
            }
        }

        $this->session->set_userdata('cart', $cart);

        echo json_encode([
            'status' => 'success',
            'cart_count' => count($cart),
            'cart_items' => array_sum(array_column($cart, 'jumlah'))
        ]);
    }

    // Remove from cart
    public function remove_from_cart($barang_id)
    {
        $cart = $this->session->userdata('cart') ?: [];

        $cart = array_filter($cart, function ($item) use ($barang_id) {
            return $item['id'] != $barang_id;
        });
        $cart = array_values($cart); // Reindex

        $this->session->set_userdata('cart', $cart);

        $this->session->set_flashdata('success', 'Barang dihapus dari keranjang');
        redirect('user/permintaan-barang/buat');
    }

    // Clear cart
    public function clear_cart()
    {
        $this->session->unset_userdata('cart');
        $this->session->set_flashdata('success', 'Keranjang dikosongkan');
        redirect('user/permintaan-barang/buat');
    }

    // Submit permintaan
    public function submit()
    {
        $cart = $this->session->userdata('cart') ?: [];

        if (empty($cart)) {
            $this->session->set_flashdata('error', 'Keranjang kosong');
            redirect('user/permintaan-barang/buat');
        }

        $this->db->trans_start();

        // Generate nomor permintaan
        $nomor_permintaan = $this->generate_nomor_permintaan();

        // Insert header permintaan dengan user_id dari session
        $data_header = [
            'nomor_permintaan' => $nomor_permintaan,
            'tanggal_permintaan' => date('Y-m-d'),
            'user_id' => $this->session->userdata('user_id'), // Use user_id from session
            'keperluan' => $this->input->post('keperluan'),
            'keterangan' => $this->input->post('keterangan'),
            'status' => 'pending',
            'created_at' => date('Y-m-d H:i:s')
        ];

        $this->db->insert('permintaan_barang', $data_header);
        $permintaan_id = $this->db->insert_id();

        // Insert detail
        foreach ($cart as $item) {
            $data_detail = [
                'permintaan_id' => $permintaan_id,
                'barang_id' => $item['id'],
                'jumlah_diminta' => $item['jumlah'],
                'jumlah_disetujui' => 0,
                'keterangan' => ''
            ];
            $this->db->insert('permintaan_barang_detail', $data_detail);
        }

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {
            $this->session->set_flashdata('error', 'Gagal membuat permintaan');
        } else {
            // Clear cart
            $this->session->unset_userdata('cart');
            $this->session->set_flashdata('success', 'Permintaan berhasil dibuat dengan nomor: ' . $nomor_permintaan);
        }

        redirect('user/permintaan-barang');
    }

    private function generate_nomor_permintaan()
    {
        $prefix = 'PRM-' . date('Ym') . '-';

        $this->db->select('nomor_permintaan');
        $this->db->from('permintaan_barang');
        $this->db->like('nomor_permintaan', $prefix, 'after');
        $this->db->order_by('id', 'DESC');
        $this->db->limit(1);
        $last = $this->db->get()->row();

        if ($last) {
            $last_number = (int)substr($last->nomor_permintaan, -4);
            $new_number = $last_number + 1;
        } else {
            $new_number = 1;
        }

        return $prefix . str_pad($new_number, 4, '0', STR_PAD_LEFT);
    }

    public function detail($id)
    {
        $data['title'] = 'Detail Permintaan';
        $data['permintaan'] = $this->M_permintaan->get_by_id($id);

        // Check ownership
        if ($data['permintaan']->user_id != $this->session->userdata('user_id')) {
            show_404();
        }

        $data['detail'] = $this->M_permintaan->get_detail($id);

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('user/permintaan_barang/detail', $data);
        $this->load->view('dist/_partials/footer');
    }
}
