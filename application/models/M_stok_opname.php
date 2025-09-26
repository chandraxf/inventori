
<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_stok_opname extends CI_Model
{

    public function get_all()
    {
        $this->db->order_by('created_at', 'DESC');
        return $this->db->get('stok_opname')->result();
    }

    public function get_barang_with_stok()
    {
        $periode = date('Y-m');

        $this->db->select('mb.*, COALESCE(sb.stok_akhir, 0) as stok_saat_ini, 
                          COALESCE(sb.harga_rata_rata, mb.harga_terakhir, 0) as harga_rata_rata');
        $this->db->from('master_barang mb');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = "' . $periode . '"', 'left');
        $this->db->where('mb.status', 'aktif');
        $this->db->order_by('mb.id', 'ASC');

        return $this->db->get()->result();
    }

    public function posting($id)
    {
        $sql = "CALL sp_posting_stok_opname(?)";

        try {
            $this->db->query($sql, [$id]);
            return ['status' => true, 'message' => 'Stok opname berhasil diposting'];
        } catch (Exception $e) {
            return ['status' => false, 'message' => 'Gagal posting: ' . $e->getMessage()];
        }
    }
    
    public function hapus($id)
    {
        $this->db->where('id', $id);
        $this->db->delete('stok_opname');
        return ['status' => $this->db->affected_rows() > 0, 'message' => 'Stok opname berhasil dihapus'];
    }
}
