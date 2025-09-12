<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_laporan extends CI_Model
{

    public function get_laporan_stok($periode = null)
    {
        if (!$periode) {
            $periode = date('Y-m');
        }

        $query = "SELECT * FROM v_laporan_stok 
                  WHERE jumlah_akhir > 0 OR jumlah_masuk > 0 OR jumlah_keluar > 0
                  ORDER BY kode_rek_108, kode_nusp";

        return $this->db->query($query)->result();
    }

    public function get_kartu_stok($barang_id, $start_date, $end_date)
    {
        $this->db->select('ks.*, mb.kode_nusp, mb.nama_nusp, mb.satuan');
        $this->db->from('kartu_stok ks');
        $this->db->join('master_barang mb', 'ks.barang_id = mb.id');
        $this->db->where('ks.barang_id', $barang_id);
        $this->db->where('ks.tanggal >=', $start_date);
        $this->db->where('ks.tanggal <=', $end_date);
        $this->db->order_by('ks.tanggal', 'ASC');
        $this->db->order_by('ks.id', 'ASC');
        return $this->db->get()->result();
    }

    public function get_barang_minimum()
    {
        $query = "SELECT * FROM v_monitoring_stok 
                  WHERE status_stok IN ('PERLU RESTOCK', 'MENDEKATI MINIMUM')
                  ORDER BY stok_saat_ini ASC";

        return $this->db->query($query)->result();
    }

    public function get_rekap_transaksi($start_date, $end_date)
    {
        // Rekap barang masuk
        $this->db->select('COUNT(*) as total_transaksi, SUM(total_nilai) as total_nilai');
        $this->db->where('tanggal_masuk >=', $start_date);
        $this->db->where('tanggal_masuk <=', $end_date);
        $this->db->where('status', 'posted');
        $data['barang_masuk'] = $this->db->get('barang_masuk')->row();

        // Rekap barang keluar
        $this->db->select('COUNT(*) as total_transaksi, SUM(total_nilai) as total_nilai');
        $this->db->where('tanggal_keluar >=', $start_date);
        $this->db->where('tanggal_keluar <=', $end_date);
        $this->db->where('status', 'posted');
        $data['barang_keluar'] = $this->db->get('barang_keluar')->row();

        return $data;
    }
}
