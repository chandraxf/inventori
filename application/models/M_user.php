<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_user extends CI_Model
{

    private $table = 'user';

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['ID' => $id])->row();
    }

    public function get_by_username($username)
    {
        return $this->db->get_where($this->table, ['USERNAME' => $username])->row();
    }

    public function update_profile($id, $data)
    {
        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_password($id, $new_password)
    {
        $data = [
            'PASSWORD' => password_hash($new_password, PASSWORD_DEFAULT),
            'UPDATED_AT' => date('Y-m-d H:i:s')
        ];

        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);
    }

    public function update_photo($id, $filename)
    {
        $data = [
            'IMG' => $filename,
            'UPDATED_AT' => date('Y-m-d H:i:s')
        ];

        $this->db->where('ID', $id);
        return $this->db->update($this->table, $data);
    }

    public function get_users_by_role($role)
    {
        $this->db->where('ROLE', $role);
        $this->db->order_by('NAMA', 'ASC');
        return $this->db->get($this->table)->result();
    }
}
