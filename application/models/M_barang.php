<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_barang extends CI_Model
{

    private $table = 'master_barang';

    public function get_all()
    {
        $this->db->select('mb.*, COALESCE(sb.stok_akhir, 0) as stok_saat_ini');
        $this->db->from($this->table . ' mb');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = "' . date('Y-m') . '"', 'left');
        $this->db->order_by('mb.kode_nusp', 'ASC');
        return $this->db->get()->result();
    }
    public function get_all_filter($kode)
    {
        $periode = date('Y-m');

        $this->db->select('mb.*, COALESCE(sb.stok_akhir, 0) as stok_saat_ini');
        $this->db->from($this->table . ' mb');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = ' . $this->db->escape($periode), 'left');
        $this->db->like('mb.kode_nusp', $kode); // default sudah '%kode%'
        $this->db->order_by('mb.kode_nusp', 'ASC');

        return $this->db->get()->result();
    }
    public function get_per_nusp($kode)
    {
        $periode = date('Y-m');

        $this->db->select('mb.*, COALESCE(sb.stok_akhir, 0) as stok_saat_ini');
        $this->db->from($this->table . ' mb');
        $this->db->join('stok_barang sb', 'mb.id = sb.barang_id AND sb.periode = ' . $this->db->escape($periode), 'left');
        $this->db->like('mb.kode_nusp', $kode); // default sudah '%kode%'
        $this->db->order_by('mb.kode_nusp', 'ASC');

        return $this->db->get()->row_array();
    }


    public function get_all_active()
    {
        $this->db->where('status', 'aktif');
        $this->db->order_by('nama_nusp', 'ASC');
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

    public function check_transaction($id)
    {
        // Check in barang_masuk_detail
        $masuk = $this->db->where('barang_id', $id)->get('barang_masuk_detail')->num_rows();
        if ($masuk > 0) return true;

        // Check in barang_keluar_detail
        $keluar = $this->db->where('barang_id', $id)->get('barang_keluar_detail')->num_rows();
        if ($keluar > 0) return true;

        return false;
    }

    public function get_stok($barang_id, $periode)
    {
        $this->db->where('barang_id', $barang_id);
        $this->db->where('periode', $periode);
        return $this->db->get('stok_barang')->row();
    }

    public function search($keyword)
    {
        $this->db->like('kode_nusp', $keyword);
        $this->db->or_like('nama_nusp', $keyword);
        $this->db->where('status', 'aktif');
        return $this->db->get($this->table)->result();
    }

    public function count_active($search = null, $kategori = null)
    {
        $this->db->from('master_barang');
        $this->db->where('status', 'aktif');

        if ($search) {
            $this->db->group_start();
            $this->db->like('nama_nusp', $search);
            $this->db->or_like('kode_nusp', $search);
            $this->db->or_like('nama_barang_108', $search);
            $this->db->group_end();
        }

        if ($kategori) {
            $this->db->where('kode_rek_108', $kategori);
        }

        return $this->db->count_all_results();
    }

    // New
    public function get_katalog($limit, $offset, $search = null, $kategori = null)
    {
        $this->db->select('mb.*');
        $this->db->from($this->table . ' mb');
        $this->db->where('mb.status', 'aktif');

        // Apply search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('mb.nama_nusp', $search);
            $this->db->or_like('mb.kode_nusp', $search);
            $this->db->or_like('mb.nama_barang_108', $search);
            $this->db->group_end();
        }

        // Apply category filter
        if (!empty($kategori)) {
            $this->db->where('mb.kode_rek_108', $kategori);
        }

        $this->db->order_by('mb.nama_nusp', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    // Count katalog for pagination
    public function count_katalog($search = null, $kategori = null)
    {
        $this->db->from($this->table);
        $this->db->where('status', 'aktif');

        // Apply search filter
        if (!empty($search)) {
            $this->db->group_start();
            $this->db->like('nama_nusp', $search);
            $this->db->or_like('kode_nusp', $search);
            $this->db->or_like('nama_barang_108', $search);
            $this->db->group_end();
        }

        // Apply category filter
        if (!empty($kategori)) {
            $this->db->where('kode_rek_108', $kategori);
        }

        return $this->db->count_all_results();
    }

    // Get kategori list for dropdown
    public function get_kategori_list()
    {
        $this->db->select('kode_rek_108, nama_barang_108');
        $this->db->from($this->table);
        $this->db->where('status', 'aktif');
        $this->db->group_by('kode_rek_108, nama_barang_108');
        $this->db->order_by('nama_barang_108', 'ASC');
        return $this->db->get()->result();
    }

    // Get by ID
    // public function get_by_id($id)
    // {
    //     return $this->db->get_where($this->table, ['id' => $id])->row();
    // }

    // Get popular items
    public function get_popular_items($limit = 5)
    {
        $this->db->select('mb.*, COUNT(pbd.barang_id) as request_count');
        $this->db->from($this->table . ' mb');
        $this->db->join('permintaan_barang_detail pbd', 'mb.id = pbd.barang_id', 'left');
        $this->db->where('mb.status', 'aktif');
        $this->db->group_by('mb.id');
        $this->db->order_by('request_count', 'DESC');
        $this->db->limit($limit);
        return $this->db->get()->result();
    }
}
