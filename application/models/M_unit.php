<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_unit extends CI_Model
{

    private $table = 'master_unit';

    public function get_all()
    {
        $this->db->order_by('nama_unit', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_all_active()
    {
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_unit', 'ASC');
        return $this->db->get($this->table)->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function insert($data)
    {
        return $this->db->insert($this->table, $data);
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update($this->table, $data);
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
}
