<?php
defined('BASEPATH') or exit('No direct script access allowed');

class User_management extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->model('M_user');
        $this->load->library('form_validation');
        $this->load->library('upload');
        
        // Check if user is logged in and is admin
        if (!$this->session->userdata('logged_in')) {
            redirect('auth');
        }
        
        // Only admin can access user management
        if ($this->session->userdata('role') != 1) {
            $this->session->set_flashdata('error', 'Anda tidak memiliki akses untuk halaman ini');
            redirect('dashboard');
        }
    }

    public function index()
    {
        $data['title'] = 'Manajemen User';
        $data['users'] = $this->M_user->get_all();

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/user_management/index', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function tambah()
    {
        $data['title'] = 'Tambah User';

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[20]|is_unique[user.USERNAME]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');
            $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'required|matches[password]');
            $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[50]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[1,2,3]');

            if ($this->form_validation->run() == TRUE) {
                // Proses upload foto jika ada
                $upload_img = '';
                if (!empty($_FILES['img']['name'])) {
                    $config['upload_path'] = './uploads/users/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = time() . '_' . $_FILES['img']['name'];
                    $config['encrypt_name'] = FALSE;

                    // Create directory if not exists
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('img')) {
                        $upload_img = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('admin/user-management/tambah');
                        return;
                    }
                }

                // Data untuk insert
                $data_insert = [
                    'USERNAME' => $this->input->post('username'),
                    'PASSWORD' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'NAMA' => $this->input->post('nama'),
                    'IMG' => $upload_img,
                    'ROLE' => $this->input->post('role'),
                    'CREATED_AT' => date('Y-m-d H:i:s')
                ];

                if ($this->M_user->insert($data_insert)) {
                    $this->session->set_flashdata('success', 'User berhasil ditambahkan');
                    redirect('admin/user-management');
                } else {
                    $this->session->set_flashdata('error', 'Gagal menambahkan user');
                }
            }
        }

        // Tampilkan form (GET request atau validation failed)
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/user_management/tambah', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function edit($id = null)
    {
        if ($id === null) {
            show_404();
        }

        $data['title'] = 'Edit User';
        $data['user'] = $this->M_user->get_by_id($id);

        if (!$data['user']) {
            show_404();
        }

        // Prevent editing super admin or self deletion by non-super admin
        if ($data['user']->ID == 1 && $this->session->userdata('user_id') != 1) {
            $this->session->set_flashdata('error', 'Anda tidak dapat mengedit super admin');
            redirect('admin/user-management');
        }

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            if ($this->input->post('username') != $data['user']->USERNAME) {
                $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[20]|is_unique[user.USERNAME]');
            } else {
                $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[20]');
            }

            $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[50]');
            $this->form_validation->set_rules('role', 'Role', 'required|in_list[1,2,3]');

            // Password validation only if password is provided
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'matches[password]');
            }

            if ($this->form_validation->run() == TRUE) {
                // Prepare update data
                $data_update = [
                    'USERNAME' => $this->input->post('username'),
                    'NAMA' => $this->input->post('nama'),
                    'ROLE' => $this->input->post('role'),
                    'UPDATED_AT' => date('Y-m-d H:i:s')
                ];

                // Add password if provided
                if ($this->input->post('password')) {
                    $data_update['PASSWORD'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }

                // Handle image upload if new image provided
                if (!empty($_FILES['img']['name'])) {
                    $config['upload_path'] = './uploads/users/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = time() . '_' . $_FILES['img']['name'];
                    $config['encrypt_name'] = FALSE;

                    // Create directory if not exists
                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('img')) {
                        // Delete old image if exists
                        if ($data['user']->IMG && file_exists('./uploads/users/' . $data['user']->IMG)) {
                            unlink('./uploads/users/' . $data['user']->IMG);
                        }
                        $data_update['IMG'] = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('admin/user-management/edit/' . $id);
                        return;
                    }
                }

                // Update database
                if ($this->M_user->update($id, $data_update)) {
                    $this->session->set_flashdata('success', 'User berhasil diupdate');
                    redirect('admin/user-management');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate user');
                }
            }
        }

        // Show form (GET request or validation failed)
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/user_management/edit', $data);
        $this->load->view('dist/_partials/footer');
    }

    public function hapus($id = null)
    {
        if ($id === null) {
            show_404();
        }

        $user = $this->M_user->get_by_id($id);

        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan');
            redirect('admin/user-management');
        }

        // Prevent deleting super admin or self
        if ($id == 1) {
            $this->session->set_flashdata('error', 'Super admin tidak dapat dihapus');
            redirect('admin/user-management');
        }

        if ($id == $this->session->userdata('user_id')) {
            $this->session->set_flashdata('error', 'Anda tidak dapat menghapus akun sendiri');
            redirect('admin/user-management');
        }

        // Delete user image if exists
        if ($user->IMG && file_exists('./uploads/users/' . $user->IMG)) {
            unlink('./uploads/users/' . $user->IMG);
        }

        // Delete from database
        if ($this->M_user->delete($id)) {
            $this->session->set_flashdata('success', 'User berhasil dihapus');
        } else {
            $this->session->set_flashdata('error', 'Gagal menghapus user');
        }

        redirect('admin/user-management');
    }

    public function reset_password($id = null)
    {
        if ($id === null) {
            show_404();
        }

        $user = $this->M_user->get_by_id($id);

        if (!$user) {
            $this->session->set_flashdata('error', 'User tidak ditemukan');
            redirect('admin/user-management');
        }

        // Generate random password
        $new_password = $this->generate_password();
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

        $data_update = [
            'PASSWORD' => $hashed_password,
            'UPDATED_AT' => date('Y-m-d H:i:s')
        ];

        if ($this->M_user->update($id, $data_update)) {
            $this->session->set_flashdata('success', 
                'Password user "' . $user->USERNAME . '" berhasil direset.<br>' .
                'Password baru: <strong>' . $new_password . '</strong><br>' .
                'Silahkan catat dan berikan kepada user yang bersangkutan.'
            );
        } else {
            $this->session->set_flashdata('error', 'Gagal mereset password');
        }

        redirect('admin/user-management');
    }

    public function profile()
    {
        $data['title'] = 'Profil Saya';
        $data['user'] = $this->M_user->get_by_id($this->session->userdata('user_id'));

        if (!$data['user']) {
            show_404();
        }

        // Jika form disubmit (POST request)
        if ($this->input->server('REQUEST_METHOD') == 'POST') {
            // Set validation rules
            if ($this->input->post('username') != $data['user']->USERNAME) {
                $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[20]|is_unique[user.USERNAME]');
            } else {
                $this->form_validation->set_rules('username', 'Username', 'required|min_length[3]|max_length[20]');
            }

            $this->form_validation->set_rules('nama', 'Nama Lengkap', 'required|max_length[50]');

            // Password validation only if password is provided
            if ($this->input->post('password')) {
                $this->form_validation->set_rules('password', 'Password', 'min_length[6]');
                $this->form_validation->set_rules('password_confirm', 'Konfirmasi Password', 'matches[password]');
            }

            if ($this->form_validation->run() == TRUE) {
                // Prepare update data
                $data_update = [
                    'USERNAME' => $this->input->post('username'),
                    'NAMA' => $this->input->post('nama'),
                    'UPDATED_AT' => date('Y-m-d H:i:s')
                ];

                // Add password if provided
                if ($this->input->post('password')) {
                    $data_update['PASSWORD'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }

                // Handle image upload if new image provided
                if (!empty($_FILES['img']['name'])) {
                    $config['upload_path'] = './uploads/users/';
                    $config['allowed_types'] = 'jpg|jpeg|png|gif';
                    $config['max_size'] = 2048; // 2MB
                    $config['file_name'] = time() . '_' . $_FILES['img']['name'];

                    if (!is_dir($config['upload_path'])) {
                        mkdir($config['upload_path'], 0777, true);
                    }

                    $this->upload->initialize($config);

                    if ($this->upload->do_upload('img')) {
                        // Delete old image if exists
                        if ($data['user']->IMG && file_exists('./uploads/users/' . $data['user']->IMG)) {
                            unlink('./uploads/users/' . $data['user']->IMG);
                        }
                        $data_update['IMG'] = $this->upload->data('file_name');
                    } else {
                        $this->session->set_flashdata('error', $this->upload->display_errors());
                        redirect('admin/user-management/profile');
                        return;
                    }
                }

                // Update database
                if ($this->M_user->update($data['user']->ID, $data_update)) {
                    // Update session data
                    $this->session->set_userdata([
                        'username' => $data_update['USERNAME'],
                        'nama' => $data_update['NAMA']
                    ]);

                    $this->session->set_flashdata('success', 'Profil berhasil diupdate');
                } else {
                    $this->session->set_flashdata('error', 'Gagal mengupdate profil');
                }

                redirect('admin/user-management/profile');
            }
        }

        $this->load->view('dist/_partials/header', $data);
        $this->load->view('admin/user_management/profile', $data);
        $this->load->view('dist/_partials/footer');
    }

    // AJAX: Check if username is available
    public function check_username()
    {
        $username = $this->input->post('username');
        $id = $this->input->post('id'); // For edit mode

        if ($username) {
            $this->db->where('USERNAME', $username);
            if ($id) {
                $this->db->where('ID !=', $id);
            }
            $count = $this->db->count_all_results('user');

            if ($count > 0) {
                echo json_encode(['status' => false, 'message' => 'Username sudah digunakan']);
            } else {
                echo json_encode(['status' => true, 'message' => 'Username tersedia']);
            }
        } else {
            echo json_encode(['status' => false, 'message' => 'Username tidak boleh kosong']);
        }
    }

    // Generate random password
    private function generate_password($length = 8)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        
        return $randomString;
    }

    // Get role name for display
    public function get_role_name($role_id)
    {
        switch ($role_id) {
            case 1:
                return 'Administrator';
            case 2:
                return 'User';
            case 3:
                return 'PPTK';
            default:
                return 'Unknown';
        }
    }
}