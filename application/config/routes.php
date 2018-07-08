<?php
defined('BASEPATH') OR exit('No direct script access allowed');

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
$route['default_controller'] = 'C_home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;



//REGISTER/LOGIN
$route['login-akun'] = "C_public_login/login";
$route['daftar-akun'] = "C_public_login/register";
$route['logout-akun'] = "C_public_login/logout";
$route['daftar-sukses'] = "C_public_login/register_sukses";
$route['public-cek-login'] = "C_public_login/cek_login";

//REGISTER/LOGIN

//PROFILE
$route['profile'] = "C_public_profile/index";
$route['simpan-profile'] = "C_public_profile/update_profile";
$route['update-password'] = "C_public_profile/update_password";
$route['upload-gambar'] = "C_public_profile/upload_gambar";
$route['history-order'] = "C_public_history_order/index";
$route['edit-pengaturan'] = "C_public_profile/pengaturan";
//PROFILE

//KELOLA TOKO
$route['kelola-toko'] = "C_public_toko/index";
$route['kelola-toko/(:any)'] = "C_public_toko/index";

$route['aktivasi-toko'] = "C_public_toko/aktivasi";
$route['admin-toko'] = "C_admin_toko/index";
$route['toko-close'] = "C_admin_toko/close";


//SATUAN
	$route['toko-satuan'] = "C_admin_satuan/index";
	$route['toko-toko/(:any)'] = "C_admin_satuan/index";

	$route['toko-satuan-simpan'] = "C_admin_satuan/simpan";
	$route['toko-satuan-simpan/(:any)'] = 'C_admin_satuan/simpan';

	$route['toko-satuan-hapus'] = "C_admin_satuan/hapus";
	$route['toko-satuan-hapus/(:any)'] = 'C_admin_satuan/hapus';
//SATUAN

//KATEGORI PRODUK
	$route['toko-kat-produk'] = "C_admin_kat_produk/index";
	$route['toko-kat-produk/(:any)'] = 'C_admin_kat_produk/index';

	$route['toko-kat-produk-simpan'] = "C_admin_kat_produk/simpan";
	$route['toko-kat-produk-simpan/(:any)'] = 'C_admin_kat_produk/simpan';

	$route['toko-kat-produk-hapus'] = "C_admin_kat_produk/hapus";
	$route['toko-kat-produk-hapus/(:any)'] = 'C_admin_kat_produk/hapus';
//KATEGORI PRODUK

//PRODUK
	$route['toko-produk'] = "C_admin_produk/index";
	$route['toko-produk/(:any)'] = 'C_admin_produk/index';

	$route['toko-produk-simpan'] = "C_admin_produk/simpan";
	$route['toko-produk-simpan/(:any)'] = 'C_admin_produk/simpan';

	$route['toko-produk-hapus'] = "C_admin_produk/hapus";
	$route['toko-produk-hapus/(:any)'] = 'C_admin_produk/hapus';
//PRODUK

//ADMIN/SELLER AUCTION
	$route['admin-auction'] = "C_admin_auction/index";
	$route['admin-auction/(:any)'] = 'C_admin_auction/index';
	
	$route['admin-auction-simpan'] = "C_admin_auction/simpan";
	$route['admin-auction-simpan/(:any)'] = 'C_admin_auction/simpan';
	
	$route['admin-auction-hapus'] = "C_admin_auction/hapus";
	$route['admin-auction-hapus/(:any)'] = 'C_admin_auction/hapus';
//ADMIN/SELLER AUCTION

//PRODUK GAMBAR
	$route['toko-produk-gambar'] = "C_admin_images/index";
	$route['toko-produk-gambar/(:any)'] = 'C_admin_images/index';

	$route['toko-produk-gambar-simpan'] = "C_admin_images/simpan";
	$route['toko-produk-gambar-simpan/(:any)'] = 'C_admin_images/simpan';

	$route['toko-produk-gambar-hapus'] = "C_admin_images/hapus";
	$route['toko-produk-gambar-hapus/(:any)'] = 'C_admin_images/hapus';
	$route['toko-produk-gambar-hapus/(:any)/(:any)'] = 'C_admin_images/hapus';
//PRODUK GAMBAR



//HARGA
	$route['toko-harga-satuan'] = "C_admin_harga_satuan/index";
	$route['toko-harga-satuan/(:any)'] = "C_admin_harga_satuan/index";
//HARGA

//PENJUALAN
	$route['history-penjualan'] = "C_admin_history_penjualan/index";
	$route['history-penjualan/(:any)'] = "C_admin_history_penjualan/index";
//PENJUALAN

//KELOLA TOKO



//PUBLIC
$route['produk'] = "C_home/produk";
$route['produk/(:any)'] = "C_home/produk";

$route['t'] = "C_home/index";
$route['t/(:any)'] = "C_home/index";


$route['p'] = "C_home/detail_produk";
$route['p/(:any)'] = "C_home/detail_produk";

$route['add-cart'] = "C_public_produk/add_cart";
$route['add-cart/(:any)'] = 'C_public_produk/add_cart';

$route['keranjang-belanja'] = "C_public_cart/index";
$route['simpan-belanja'] = "C_public_cart/tambah_barang";
$route['simpan-belanja/(:any)'] = "C_public_cart/tambah_barang";

$route['update-belanja'] = "C_public_cart/update_cart";
$route['update-belanja/(:any)'] = "C_public_cart/update_cart";

$route['cekout'] = "C_public_cart/cekout";
$route['cekout-selesai'] = "C_public_cart/cekout_selesai";



$route['place-new-bid'] = "C_public_produk/place_bid";
$route['place-new-bid/(:any)'] = 'C_public_produk/place_bid';

$route['bid-placed'] = "C_public_produk/bid_placed";
$route['bid-placed/(:any)'] = 'C_public_produk/bid_placed';
//PUBLIC



