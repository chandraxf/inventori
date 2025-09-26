<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_user extends CI_Model {

    private $table = 'user';

    public function __construct() {
        parent::__construct();
    }

    // Get all users
    public function get_all() {
        $this->db->order_by('CREATED_AT', 'DESC');
        return $this->db->get($this->table)->result();
    }

    // Get user by ID
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['ID' => $id])->row();
    }

    // Get user by username
    public function get_by_username($username) {
        return $this->db->get_where($this->table, ['USERNAME' => $username])->row();
    }

    // Insert new user
    public function insert($data) {
        return $this->db->insert($this->table, $data);
    }

    // Update user
    public function update($id, $data) {
        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);
    }

    // Delete user
    public function delete($id) {
        $this->db->where('ID', $id);
        return $this->db->delete($this->table);
    }

    // Check if username exists
    public function username_exists($username, $exclude_id = null) {
        $this->db->where('USERNAME', $username);
        if ($exclude_id) {
            $this->db->where('ID !=', $exclude_id);
        }
        return $this->db->count_all_results($this->table) > 0;
    }

    // Get users by role
    public function get_by_role($role) {
        $this->db->where('ROLE', $role);
        $this->db->order_by('NAMA', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Get active users (you might want to add status field later)
    public function get_active() {
        // For now, return all users since there's no status field
        $this->db->order_by('NAMA', 'ASC');
        return $this->db->get($this->table)->result();
    }

    // Verify login
    public function verify_login($username, $password) {
        $user = $this->get_by_username($username);
        
        if ($user && password_verify($password, $user->PASSWORD)) {
            return $user;
        }
        
        return false;
    }

    // Count users by role
    public function count_by_role($role) {
        $this->db->where('ROLE', $role);
        return $this->db->count_all_results($this->table);
    }

    // Get role name
    public function get_role_name($role_id) {
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

    // Get recent users (last 30 days)
    public function get_recent($limit = 10) {
        $this->db->where('CREATED_AT >=', date('Y-m-d H:i:s', strtotime('-30 days')));
        $this->db->order_by('CREATED_AT', 'DESC');
        $this->db->limit($limit);
        return $this->db->get($this->table)->result();
    }
}