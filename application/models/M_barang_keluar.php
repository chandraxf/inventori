<?php
// =============================================
// 10. MODELS/M_BARANG_KELUAR.PHP
// =============================================
defined('BASEPATH') or exit('No direct script access allowed');

class M_barang_keluar extends CI_Model
{

    private $table = 'barang_keluar';

    public function get_all()
    {
        $this->db->select('bk.*, mu.nama_unit');
        $this->db->from($this->table . ' bk');
        $this->db->join('master_unit mu', 'bk.unit_id = mu.id', 'left');
        $this->db->order_by('bk.created_at', 'DESC');
        return $this->db->get()->result();
    }

    public function get_by_id($id)
    {
        return $this->db->get_where($this->table, ['id' => $id])->row();
    }

    public function get_detail($barang_keluar_id)
    {
        $this->db->select('bkd.*, mb.kode_nusp, mb.nama_nusp, mb.satuan');
        $this->db->from('barang_keluar_detail bkd');
        $this->db->join('master_barang mb', 'bkd.barang_id = mb.id');
        $this->db->where('bkd.barang_keluar_id', $barang_keluar_id);
        return $this->db->get()->result();
    }

    public function delete($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete($this->table);
    }

    public function posting($id)
    {
        // Call stored procedure
        $sql = "CALL sp_posting_barang_keluar(?)";

        try {
            $this->db->query($sql, [$id]);
            return ['status' => true, 'message' => 'Barang keluar berhasil diposting'];
        } catch (Exception $e) {
            // Extract error message
            $error_msg = $e->getMessage();
            if (strpos($error_msg, 'Stok tidak mencukupi') !== false) {
                return ['status' => false, 'message' => 'Stok tidak mencukupi untuk barang keluar'];
            }
            return ['status' => false, 'message' => 'Gagal posting: ' . $error_msg];
        }
    }

    public function get_by_periode($start_date, $end_date)
    {
        $this->db->select('bk.*, mu.nama_unit');
        $this->db->from($this->table . ' bk');
        $this->db->join('master_unit mu', 'bk.unit_id = mu.id', 'left');
        $this->db->where('bk.tanggal_keluar >=', $start_date);
        $this->db->where('bk.tanggal_keluar <=', $end_date);
        $this->db->where('bk.status', 'posted');
        $this->db->order_by('bk.tanggal_keluar', 'ASC');
        return $this->db->get()->result();
    }
}
