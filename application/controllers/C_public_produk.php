<?php

	class C_public_produk extends CI_Controller
	{
		public function __construct()
		{
			
			parent::__construct();
			$this->load->model(array('M_home'));
		}
	
		public function index()
		{
			
			$this->load->view('front/page/produk.html');
		}
		
		public function place_bid()
		{
			$id_produk = $this->uri->segment(2,0);
			$list_kategori = $this->M_home->list_kategori();
			$detail_produk = $this->M_home->detail_produk($id_produk);
			$list_hot_deal = $this->M_home->list_hot_deal(0,10);
			
			$data = array('list_kategori' => $list_kategori,'detail_produk' => $detail_produk,'list_hot_deal' => $list_hot_deal
							, 'isCart' => '0'
			);
			
			$this->load->view('front/page/place-bid.html',$data);
			
		}
		
		public function bid_placed()
		{
			if(($this->session->userdata('ses_public_user') == null) or ($this->session->userdata('ses_public_pass') == null))
			{
				$data = array('is_login'=> false, 'isCart' => '0');
				
				$this->load->view('front/page/shop_cekout.html',$data);
			} else {
				$data = array('is_login'=> false, 'isCart' => '0');
				$this->load->view('front/page/bid-placed.html');
			}
		}
		
		public function add_cart()
		{
			$id_produk = $this->uri->segment(2,0);
			$list_kategori = $this->M_home->list_kategori();
			$detail_produk = $this->M_home->detail_produk($id_produk);
			$list_hot_deal = $this->M_home->list_hot_deal(0,10);
			
			$data = array('list_kategori' => $list_kategori,'detail_produk' => $detail_produk,'list_hot_deal' => $list_hot_deal);
			
			$this->load->view('front/page/place-bid.html',$data);
			
		}
	}
	
?>