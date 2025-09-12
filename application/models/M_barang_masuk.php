<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_barang_masuk extends CI_Model {
    
    private $table = 'barang_masuk';
    
    public function get_all() {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get($this->table)->result();
    }
    
    public function get_by_id($id) {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }
    
    public function get_detail($barang_masuk_id) {
        $this->db->select('bmd.*, mb.kode_nusp, mb.nama_nusp, mb.satuan');
        $this->db->from('barang_masuk_detail bmd');
        $this->db->join('master_barang mb', 'bmd.barang_id = mb.id');
        $this->db->where('bmd.barang_masuk_id', $barang_masuk_id);
        return $this->db->get()->result();
    }
    
    public function delete($id) {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }
    
    public function posting($id) {
        // Call stored procedure
        $sql = "CALL sp_posting_barang_masuk(?)";
        
        try {
            $this->db->query($sql, [$id]);
            return ['status' => true, 'message' => 'Barang masuk berhasil diposting'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => $e->getMessage()];
        }
    }
    
    public function get_by_periode($start_date, $end_date) {
        $this->db->where('tanggal_masuk >=', $start_date);
        $this->db->where('tanggal_masuk <=', $end_date);
        $this->db->where('status', 'posted');
        return $this->db->get($this->table)->result();
    }
}
