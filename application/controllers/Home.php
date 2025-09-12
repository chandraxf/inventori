<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Home extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $data = array(
            'title' => "Dashboard"
        );
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('home/index', $data);
        $this->load->view('dist/_partials/footer');
    }
    public function barang_master()
    {
        $data = array(
            'title' => "Data Master Barang"
        );
        $this->load->view('dist/_partials/header', $data);
        $this->load->view('master/index', $data);
        $this->load->view('dist/_partials/footer');
    }
}
