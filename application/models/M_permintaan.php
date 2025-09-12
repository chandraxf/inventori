<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_permintaan extends CI_Model
{

    public function get_user_permintaan($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('permintaan_barang')->result();
    }

    public function get_recent_user($user_id, $limit = 5)
    {
        $this->db->where('user_id', $user_id);
        $this->db->order_by('created_at', 'DESC');
        $this->db->limit($limit);
        return $this->db->get('permintaan_barang')->result();
    }

    public function count_user_permintaan($user_id)
    {
        $this->db->where('user_id', $user_id);
        return $this->db->count_all_results('permintaan_barang');
    }

    public function count_pending($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'pending');
        return $this->db->count_all_results('permintaan_barang');
    }

    public function count_approved($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'approved');
        return $this->db->count_all_results('permintaan_barang');
    }

    public function count_rejected($user_id)
    {
        $this->db->where('user_id', $user_id);
        $this->db->where('status', 'rejected');
        return $this->db->count_all_results('permintaan_barang');
    }

    public function get_by_id($id)
    {
        $this->db->select('pb.*, u.NAMA as nama_user');
        $this->db->from('permintaan_barang pb');
        $this->db->join('user u', 'pb.user_id = u.ID');
        $this->db->where('pb.id', $id);
        return $this->db->get()->row();
    }

    public function get_detail($permintaan_id)
    {
        $this->db->select('pd.*, mb.kode_nusp, mb.nama_nusp, mb.satuan, mb.gambar');
        $this->db->from('permintaan_barang_detail pd');
        $this->db->join('master_barang mb', 'pd.barang_id = mb.id');
        $this->db->where('pd.permintaan_id', $permintaan_id);
        return $this->db->get()->result();
    }
}
