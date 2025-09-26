<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_permintaan_admin extends CI_Model
{

    public function get_all_permintaan($status = null, $start_date = null, $end_date = null)
    {
        $this->db->select('pb.*, u.NAMA as nama_user, 
                          COUNT(pbd.id) as total_item,
                          SUM(pbd.jumlah_diminta) as total_qty_diminta,
                          SUM(pbd.jumlah_disetujui) as total_qty_disetujui');
        $this->db->from('permintaan_barang pb');
        $this->db->join('user u', 'pb.user_id = u.ID');
        $this->db->join('permintaan_barang_detail pbd', 'pb.id = pbd.permintaan_id', 'left');

        if ($status) {
            $this->db->where('pb.status', $status);
        }

        if ($start_date) {
            $this->db->where('pb.tanggal_permintaan >=', $start_date);
        }

        if ($end_date) {
            $this->db->where('pb.tanggal_permintaan <=', $end_date);
        }

        $this->db->group_by('pb.id');
        $this->db->order_by('pb.created_at', 'DESC');

        return $this->db->get()->result();
    }

    public function count_by_status($status)
    {
        return $this->db->where('status', $status)->count_all_results('permintaan_barang');
    }

    public function get_by_id($id)
    {
        $this->db->select('pb.*, u.NAMA as nama_user, u2.NAMA as approved_by_name');
        $this->db->from('permintaan_barang pb');
        $this->db->join('user u', 'pb.user_id = u.ID');
        $this->db->join('user u2', 'pb.approved_by = u2.ID', 'left');
        $this->db->where('pb.id', $id);
        return $this->db->get()->row();
    }

    public function get_detail($permintaan_id)
    {
        $this->db->select('pbd.*, mb.kode_nusp, mb.nama_nusp, mb.satuan, mb.gambar');
        $this->db->from('permintaan_barang_detail pbd');
        $this->db->join('master_barang mb', 'pbd.barang_id = mb.id');
        $this->db->where('pbd.permintaan_id', $permintaan_id);
        return $this->db->get()->result();
    }

    public function get_detail_with_stok($permintaan_id)
    {
        $periode = date('Y-m');

        $this->db->select('pbd.*, mb.kode_nusp, mb.nama_nusp, mb.satuan, mb.gambar,
                          COALESCE(sb.stok_akhir, 0) as stok_tersedia,
                          COALESCE(sb.harga_rata_rata, mb.harga_terakhir, 0) as harga');
        $this->db->from('permintaan_barang_detail pbd');
        $this->db->join('master_barang mb', 'pbd.barang_id = mb.id');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = "' . $periode . '"', 'left');
        $this->db->where('pbd.permintaan_id', $permintaan_id);
        return $this->db->get()->result();
    }

    public function get_approved_details($permintaan_id)
    {
        $this->db->select('pbd.*');
        $this->db->from('permintaan_barang_detail pbd');
        $this->db->where('pbd.permintaan_id', $permintaan_id);
        $this->db->where('pbd.jumlah_disetujui >', 0);
        return $this->db->get()->result();
    }
}
