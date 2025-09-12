<?php $role = $this->session->userdata('role'); ?>
<?php if ($role == 1) : ?>
    <div class="main-sidebar sidebar-style-2">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="<?= base_url('admin') ?>">Inventory System</a>
            </div>
            <div class="sidebar-brand sidebar-brand-sm">
                <a href="<?= base_url('admin') ?>">INV</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Dashboard</li>
                <li class="<?= $this->uri->segment(2) == '' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin') ?>">
                        <i class="fas fa-fire"></i> <span>Dashboard</span>
                    </a>
                </li>

                <li class="menu-header">Master Data</li>
                <li class="<?= $this->uri->segment(2) == 'master-barang' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/master-barang') ?>">
                        <i class="fas fa-box"></i> <span>Master Barang</span>
                    </a>
                </li>
                <li class="<?= $this->uri->segment(2) == 'master-unit' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/master-unit') ?>">
                        <i class="fas fa-building"></i> <span>Master Unit</span>
                    </a>
                </li>

                <li class="menu-header">Transaksi</li>
                <li class="<?= $this->uri->segment(2) == 'barang-masuk' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/barang-masuk') ?>">
                        <i class="fas fa-download"></i> <span>Barang Masuk</span>
                    </a>
                </li>
                <li class="<?= $this->uri->segment(2) == 'barang-keluar' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/barang-keluar') ?>">
                        <i class="fas fa-upload"></i> <span>Barang Keluar</span>
                    </a>
                </li>
                <li class="<?= $this->uri->segment(2) == 'stok-opname' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/stok-opname') ?>">
                        <i class="fas fa-clipboard-check"></i> <span>Stok Opname</span>
                    </a>
                </li>

                <li class="menu-header">Laporan</li>
                <li class="dropdown <?= $this->uri->segment(2) == 'laporan' ? 'active' : '' ?>">
                    <a href="#" class="nav-link has-dropdown">
                        <i class="fas fa-file-alt"></i> <span>Laporan</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="<?= base_url('admin/laporan/stok') ?>">Laporan Stok</a></li>
                        <li><a class="nav-link" href="<?= base_url('admin/laporan/kartu-stok') ?>">Kartu Stok</a></li>
                        <li><a class="nav-link" href="<?= base_url('admin/laporan/barang-masuk') ?>">Laporan Barang Masuk</a></li>
                        <li><a class="nav-link" href="<?= base_url('admin/laporan/barang-keluar') ?>">Laporan Barang Keluar</a></li>
                    </ul>
                </li>
                <li class="menu-header">Pengaturan</li>
                <li class="<?= $this->uri->segment(2) == 'referensi' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('admin/referensi') ?>">
                        <i class="fas fa-cog"></i> <span>Referensi</span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>
<?php endif ?>
<?php if ($role == 2) : ?>
    <div class="main-sidebar">
        <aside id="sidebar-wrapper">
            <div class="sidebar-brand">
                <a href="<?= base_url('user') ?>">User Panel</a>
            </div>
            <ul class="sidebar-menu">
                <li class="menu-header">Menu Utama</li>
                <li class="<?= $this->uri->segment(2) == '' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('user') ?>">
                        <i class="fas fa-home"></i> <span>Dashboard</span>
                    </a>
                </li>
                <li class="<?= $this->uri->segment(2) == 'katalog-barang' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('user/katalog-barang') ?>">
                        <i class="fas fa-boxes"></i> <span>Katalog Barang</span>
                    </a>
                </li>
                <li class="<?= $this->uri->segment(2) == 'permintaan-barang' ? 'active' : '' ?>">
                    <a class="nav-link" href="<?= base_url('user/permintaan-barang') ?>">
                        <i class="fas fa-clipboard-list"></i> <span>Permintaan Saya</span>
                        <br>
                        <br>
                        <?php
                        $pending = $this->db->where(['user_id' => $this->session->userdata('user_id'), 'status' => 'pending'])
                            ->count_all_results('permintaan_barang');
                        if ($pending > 0) :
                            ?>
                            <span class="badge badge-warning"><?= $pending ?></span>
                        <?php endif; ?>
                    </a>
                </li>
                <li>
                    <a class="nav-link" href="<?= base_url('user/permintaan-barang/buat') ?>">
                        <i class="fas fa-shopping-cart"></i>
                        <span>Keranjang</span>
                        <span class="badge badge-primary cart-badge">
                            <?= count($this->session->userdata('cart') ?: []) ?>
                        </span>
                    </a>
                </li>
            </ul>
        </aside>
    </div>
<?php endif ?>