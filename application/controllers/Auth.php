<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Auth extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('auth_model', 'Auth');
    }

    public function index()
    {
        $data = array(
            'title' => "Login"
        );
        return $this->load->view('dist/auth-login', $data);
    }
    public function pin()
    {
        $data['user'] = $this->db->query("SELECT username, name FROM user")->result();
        return $this->load->view('template/layout/pin', $data);
    }

    public function cek_login()
    {
        $username = $this->input->post('username', true);
        $password = $this->input->post('password', true);

        $cekAdmin = $this->Auth->cek_admin($username);

        // Cek Apakah ada data admin
        if ($cekAdmin) {
            if (password_verify($password, $cekAdmin['PASSWORD'])) {
                // Login controller - sesuai kolom tabel
                $session_data = [
                    'user_id' => $cekAdmin['ID'],        // Pakai ID
                    'username' => $cekAdmin['USERNAME'],  // Pakai USERNAME
                    'nama' => $cekAdmin['NAMA'],          // Pakai NAMA
                    'img' => $cekAdmin['IMG'],            // Pakai IMG untuk foto
                    'role' => $cekAdmin['ROLE'],          // ROLE: 1=admin, 2=user, 3=pptk
                    'logged_in' => TRUE
                ];
                $this->session->set_userdata($session_data);
                $this->session->set_flashdata('sukses', 'Selamat Datang!');
                if ($cekAdmin['ROLE'] == 1) {
                    redirect('admin');
                } else {
                    redirect('user');
                }
            } else {
                $this->session->set_flashdata('gagal', 'Password atau Username yang anda masukkan salah, atau tidak terdaftar!');
                redirect('Auth');
            }
        } {
            $this->session->set_flashdata('gagal', 'Username anda tidak terdaftar!');
            redirect('Auth');
        }
    }
    public function logout()
    {
        $this->session->sess_destroy();
        $this->session->unset_userdata(['ID', 'NAMA', 'ROLE']); // sesuaikan
        $this->session->set_flashdata('sukses', 'Anda telah logout!');
        redirect('Auth');
    }
}

/* End of file Auth.php and path \application\controllers\Auth.php */
