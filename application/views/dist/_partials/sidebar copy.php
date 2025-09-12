<?php
defined('BASEPATH') or exit('No direct script access allowed');
?>
<div class="main-sidebar sidebar-style-2">
  <aside id="sidebar-wrapper">
    <div class="sidebar-brand">
      <a href="<?php echo base_url(); ?>dist/index">JUMAI APP</a>
    </div>
    <div class="sidebar-brand sidebar-brand-sm">
      <a href="<?php echo base_url(); ?>dist/index">St</a>
    </div>
    <?php $role = $this->session->userdata('ROLE'); ?>
    <?php if ($role == 1) : ?>
      <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="dropdown <?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
          <ul class="dropdown-menu">
            <li class="<?php echo $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>dist/index_0">Dashboard</a></li>
          </ul>
        </li>
        <li class="menu-header">Menu</li>
        <li class="<?php echo $this->uri->segment(2) == 'credits' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>Permintaan_admin"><i class="fas fa-pencil-ruler"></i> <span>Permintaan</span></a></li>
        <li class="dropdown <?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Barang</span></a>
          <ul class="dropdown-menu">
            <li class="<?php echo $this->uri->segment(1) == 'Master' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>Master">Master</a></li>
            <li class="<?php echo $this->uri->segment(2) == 'riwayat' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>Master/riwayat">Riwayat</a></li>
            <li class="<?php echo $this->uri->segment(2) == 'barang_masuk' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>Master/barang_masuk">Barang Masuk</a></li>
          </ul>
        </li>
        <li class="<?php echo $this->uri->segment(1) == 'User' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>User"><i class="fas fa-users"></i> <span>Pengguna</span></a></li>
      </ul>
    <?php else : ?>
      <ul class="sidebar-menu">
        <li class="menu-header">Dashboard</li>
        <li class="dropdown <?php echo $this->uri->segment(2) == '' || $this->uri->segment(2) == 'index' || $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>">
          <a href="#" class="nav-link has-dropdown"><i class="fas fa-fire"></i><span>Dashboard</span></a>
          <ul class="dropdown-menu">
            <li class="<?php echo $this->uri->segment(2) == 'index_0' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>dist/index_0">Dashboard</a></li>
          </ul>
        </li>
        <li class="menu-header">Menu</li>
        <li class="<?php echo $this->uri->segment(1) == 'Permintaan' ? 'active' : ''; ?>"><a class="nav-link" href="<?php echo base_url(); ?>Permintaan"><i class="fas fa-pencil-ruler"></i> <span>Permintaan</span></a></li>
      </ul>
    <?php endif ?>

  </aside>
</div>