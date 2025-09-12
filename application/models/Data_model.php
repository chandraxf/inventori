<?php
defined('BASEPATH') or exit('No direct script access allowed');
class Data_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        //Load Dependencies

    }

    // List all your items
    public function view($table)
    {
        return $this->db->get($table);
    }
    public function bersih($table, $where)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    // Add a new item
    public function add($data, $table)
    {
        return $this->db->insert($table, $data);
    }

    public function getWhere($where, $table)
    {
        $this->db->where($where);
        return $this->db->get($table);
    }

    //Update one item
    public function update($where, $data, $table)
    {
        $this->db->where($where);
        return $this->db->update($table, $data);

    }
    public function update2($where,$where2, $data, $table)
    {
        $this->db->where($where);
        $this->db->where($where2);
        return $this->db->update($table, $data);

    }
    public function cari($key, $user)
    {
        $query = $this->db->query("SELECT * FROM barang WHERE kode_barang = '$key' AND bagian = '$user'");
        return $query->result();
    }

    //Delete one item
    public function delete($where, $table)
    {
        $this->db->where($where);
        return $this->db->delete($table);
    }

    /* End of file Controllername.php */

}