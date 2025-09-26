<?php
defined('BASEPATH') or exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Auth';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

$route['admin'] = 'admin/dashboard';
$route['admin/master-barang'] = 'admin/master_barang';
$route['admin/master-barang/tambah'] = 'admin/master_barang/tambah';
$route['admin/master-barang/edit/(:num)'] = 'admin/master_barang/edit/$1';
$route['admin/master-barang/hapus/(:num)'] = 'admin/master_barang/hapus/$1';

$route['admin/master-unit'] = 'admin/master_unit';
$route['admin/master-unit/tambah'] = 'admin/master_unit/tambah';
$route['admin/master-unit/edit/(:num)'] = 'admin/master_unit/edit/$1';
$route['admin/master-unit/hapus/(:num)'] = 'admin/master_unit/hapus/$1';


$route['admin/barang-masuk'] = 'admin/barang_masuk';
$route['admin/barang-masuk/tambah'] = 'admin/barang_masuk/tambah';
$route['admin/barang-masuk/posting/(:num)'] = 'admin/barang_masuk/posting/$1';
$route['admin/barang-masuk/detail/(:num)'] = 'admin/barang_masuk/detail/$1';

$route['admin/barang-keluar'] = 'admin/Barang_keluar';
$route['admin/barang-keluar/tambah'] = 'admin/Barang_keluar/tambah';
$route['admin/barang-keluar/simpan'] = 'admin/Barang_keluar/simpan';
$route['admin/barang-keluar/detail/(:num)'] = 'admin/Barang_keluar/detail/$1';
$route['admin/barang-keluar/posting/(:num)'] = 'admin/barang_keluar/posting/$1';


$route['admin/stok-opname'] = 'admin/stok_opname';
$route['admin/stok-opname/simpan'] = 'admin/stok_opname/simpan';
$route['admin/stok-opname/posting/(:num)'] = 'admin/stok_opname/posting/$1';
$route['admin/stok-opname/hapus/(:num)'] = 'admin/stok_opname/hapus/$1';
$route['admin/stok-opname/tambah'] = 'admin/stok_opname/tambah';
$route['admin/laporan'] = 'admin/laporan';

$route['admin/permintaan'] = 'admin/permintaan';
$route['admin/permintaan/detail/(:num)'] = 'admin/permintaan/detail/$1';
$route['admin/permintaan/approve/(:num)'] = 'admin/permintaan/approve/$1';
$route['admin/permintaan/process_approve'] = 'admin/permintaan/process_approve';


$route['admin/user-management'] = 'admin/user_management';
$route['admin/user-management/tambah'] = 'admin/user_management/tambah';
$route['admin/user-management/edit/(:num)'] = 'admin/user_management/edit/$1';
$route['admin/user-management/hapus/(:num)'] = 'admin/user_management/hapus/$1';

$route['admin/laporan/kartu-stok'] = 'admin/laporan/kartu_stok';
$route['admin/laporan/barang-masuk'] = 'admin/laporan/barang_masuk';
$route['admin/laporan/barang-keluar'] = 'admin/laporan/barang_keluar';

// User routes
$route['user'] = 'user/dashboard';
$route['user/katalog-barang'] = 'user/katalog_barang';
$route['user/permintaan-barang'] = 'user/permintaan_barang';
$route['user/permintaan-barang/buat'] = 'user/permintaan_barang/buat';
$route['user/permintaan-barang/detail/(:num)'] = 'user/permintaan_barang/detail/$1';
$route['user/permintaan-barang/submit'] = 'user/permintaan_barang/submit';


$route['user/katalog-barang/index'] = 'user/katalog_barang/index';
$route['user/katalog-barang/index/(:num)'] = 'user/katalog_barang/index/$1';
$route['user/katalog-barang/add_to_cart'] = 'user/katalog_barang/add_to_cart';

