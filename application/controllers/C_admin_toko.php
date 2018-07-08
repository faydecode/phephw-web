<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_toko extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		//$this->load->model(array('M_public_profile'));
	}
	
	public function index()
	{
		$data_transaksi_jual = 0;//$this->m_dash->getTransaksiJual();
		$data_transaksi_beli = 0;//$this->m_dash->getTransaksiBeli();
		$data_total_jual = 0;//$this->m_dash->getTotalJual();
		$data_total_beli = 0;//$this->m_dash->getTotalBeli();
		$st_penjualan = 0;//$this->m_dash->st_penjualan();
		$st_uang_keluar = 0;//$this->m_dash->st_uang_keluar();
		
		$data = array('page_content'=>'admin_dashboard','data_transaksi_jual'=>$data_transaksi_jual,'data_transaksi_beli'=>$data_transaksi_beli,'data_total_jual'=>$data_total_jual,'data_total_beli'=>$data_total_beli,'st_penjualan'=>$st_penjualan,'st_uang_keluar'=>$st_uang_keluar);
		$this->load->view('toko/container',$data);
	}
	
	public function close()
	{
		header('Location: '.base_url());
	}
}