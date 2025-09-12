<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class Laporan extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->load->model(['M_laporan', 'M_barang', 'M_barang_masuk', 'M_barang_keluar']);
    }
    
    public function index() {
        redirect('admin/laporan/stok');
    }
    
    public function stok() {
        $data['title'] = 'Laporan Stok';
        
        $periode = $this->input->get('periode') ?: date('Y-m');
        $data['periode'] = $periode;
        $data['laporan_stok'] = $this->M_laporan->get_laporan_stok($periode);
        
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/template/sidebar', $data);
        $this->load->view('admin/laporan/stok', $data);
        $this->load->view('admin/template/footer');
    }
    
    public function kartu_stok() {
        $data['title'] = 'Kartu Stok';
        
        $barang_id = $this->input->get('barang_id');
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $data['barang'] = $this->M_barang->get_all_active();
        $data['barang_id'] = $barang_id;
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        
        if ($barang_id) {
            $data['kartu_stok'] = $this->M_laporan->get_kartu_stok($barang_id, $start_date, $end_date);
            $data['selected_barang'] = $this->M_barang->get_by_id($barang_id);
        }
        
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/template/sidebar', $data);
        $this->load->view('admin/laporan/kartu_stok', $data);
        $this->load->view('admin/template/footer');
    }
    
    public function barang_masuk() {
        $data['title'] = 'Laporan Barang Masuk';
        
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['barang_masuk'] = $this->M_barang_masuk->get_by_periode($start_date, $end_date);
        
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/template/sidebar', $data);
        $this->load->view('admin/laporan/barang_masuk', $data);
        $this->load->view('admin/template/footer');
    }
    
    public function barang_keluar() {
        $data['title'] = 'Laporan Barang Keluar';
        
        $start_date = $this->input->get('start_date') ?: date('Y-m-01');
        $end_date = $this->input->get('end_date') ?: date('Y-m-t');
        
        $data['start_date'] = $start_date;
        $data['end_date'] = $end_date;
        $data['barang_keluar'] = $this->M_barang_keluar->get_by_periode($start_date, $end_date);
        
        $this->load->view('admin/template/header', $data);
        $this->load->view('admin/template/sidebar', $data);
        $this->load->view('admin/laporan/barang_keluar', $data);
        $this->load->view('admin/template/footer');
    }
    
    public function stok_excel() {
        $periode = $this->input->get('periode') ?: date('Y-m');
        $laporan = $this->M_laporan->get_laporan_stok($periode);
        
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Header
        $sheet->setCellValue('A1', 'LAPORAN PERSEDIAAN BARANG');
        $sheet->setCellValue('A2', 'Periode: ' . $periode);
        
        // Column headers
        $headers = ['No', 'Kode Rek', 'Nama Barang', 'Kode NUSP', 'Nama NUSP', 'Satuan',
                   'Stok Awal', 'Harga Awal', 'Total Awal',
                   'Jumlah Masuk', 'Harga Masuk', 'Total Masuk',
                   'Jumlah Keluar', 'Harga Keluar', 'Total Keluar',
                   'Jumlah Akhir', 'Harga Akhir', 'Total Akhir'];
        
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '4', $header);
            $col++;
        }
        
        // Data
        $row = 5;
        $no = 1;
        foreach ($laporan as $data) {
            $sheet->setCellValue('A' . $row, $no++);
            $sheet->setCellValue('B' . $row, $data->kode_rek_108);
            $sheet->setCellValue('C' . $row, $data->nama_barang_108);
            $sheet->setCellValue('D' . $row, $data->kode_nusp);
            $sheet->setCellValue('E' . $row, $data->nama_nusp);
            $sheet->setCellValue('F' . $row, $data->satuan);
            $sheet->setCellValue('G' . $row, $data->stok_awal);
            $sheet->setCellValue('H' . $row, $data->harga_awal);
            $sheet->setCellValue('I' . $row, $data->total_awal);
            $sheet->setCellValue('J' . $row, $data->jumlah_masuk);
            $sheet->setCellValue('K' . $row, $data->harga_masuk);
            $sheet->setCellValue('L' . $row, $data->total_masuk);
            $sheet->setCellValue('M' . $row, $data->jumlah_keluar);
            $sheet->setCellValue('N' . $row, $data->harga_keluar);
            $sheet->setCellValue('O' . $row, $data->total_keluar);
            $sheet->setCellValue('P' . $row, $data->jumlah_akhir);
            $sheet->setCellValue('Q' . $row, $data->harga_akhir);
            $sheet->setCellValue('R' . $row, $data->total_akhir);
            $row++;
        }
        
        // Styling
        $sheet->getStyle('A4:R4')->getFont()->setBold(true);
        $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
        
        // Auto size columns
        foreach (range('A', 'R') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        $writer = new Xlsx($spreadsheet);
        $filename = 'Laporan_Stok_' . $periode . '.xlsx';
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $writer->save('php://output');
    }
    
    public function stok_pdf() {
        $periode = $this->input->get('periode') ?: date('Y-m');
        $data['periode'] = $periode;
        $data['laporan_stok'] = $this->M_laporan->get_laporan_stok($periode);
        
        $html = $this->load->view('admin/laporan/stok_pdf', $data, true);
        
        $mpdf = new \Mpdf\Mpdf(['orientation' => 'L']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('Laporan_Stok_' . $periode . '.pdf', 'D');
    }
}