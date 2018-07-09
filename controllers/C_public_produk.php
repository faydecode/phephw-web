<?php

	class C_public_produk extends CI_Controller
	{
		public function __construct()
		{
			
			parent::__construct();
			$this->load->model(array('M_home','M_auction'));
		}
	
		public function index()
		{
			
			$this->load->view('front/page/produk.html');
		}
		
		public function place_bid()
		{
			$id_produk = $this->uri->segment(2,0);
			$list_kategori = $this->M_home->list_kategori();
			$detail_produk = $this->M_home->detail_produk_pakai_id($id_produk);
			$list_hot_deal = $this->M_home->list_hot_deal(0,10);
			
			$list_auction = $this->M_auction->list_auction_limit(" WHERE A.id_produk = '".$id_produk."'",0,1000);
			if(!empty($list_auction))
			{
				$list_auction = $list_auction->row();
				$id_auction = $list_auction->id_auction;
			}
			else
			{
				$id_auction = "";
			}
			
			$list_d_auction = $this->M_home->list_detail_auction(" WHERE A.id_auction = '".$id_auction."'");
			
			$data = array('list_kategori' => $list_kategori,'detail_produk' => $detail_produk,'list_hot_deal' => $list_hot_deal
							, 'isCart' => '0'
							,'id_auction' => $id_auction
							,'id_produk' => $id_produk
							,'list_d_auction' => $list_d_auction
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
		
		public function simpan()
		{
			if (!empty($_POST['stat_edit']))
			{
				// $this->M_auction->edit(
						// $_POST['stat_edit'],
						// $_POST['id_produk'],
						// $_POST['id_member'],
						// $_POST['kode_auction'],
						// $_POST['nama_auction'],
						// $_POST['date_mulai'],
						// $_POST['date_sampai'],
						// $_POST['harga_mulai'],
						// $_POST['ket_auction'],
						// $_POST['verified'],
						// $this->session->userdata('ses_public_id_user'),
						// $this->session->userdata('ses_kode_kantor')
					// );
				// header('Location: '.base_url().'admin-auction?'.http_build_query($_GET));
			}
			else
			{
				$this->M_home->simpan_d_auction
					(
						$_POST['id_auction'],
						$this->session->userdata('ses_public_id_user'),
						$_POST['id_produk'],
						$_POST['nominal'],
						$_POST['pesan'],
						$this->session->userdata('ses_public_id_user'),
						$this->session->userdata('ses_public_id_user')
					
					
						// $_POST['id_produk'],
						// $_POST['id_member'],
						// $_POST['kode_auction'],
						// $_POST['nama_auction'],
						// $_POST['date_mulai'],
						// $_POST['date_sampai'],
						// $_POST['harga_mulai'],
						// $_POST['ket_auction'],
						// $_POST['verified'],
						// $this->session->userdata('ses_public_id_user'),
						// $this->session->userdata('ses_public_id_user'),
						// $this->session->userdata('ses_kode_kantor')
					);
				header('Location: '.base_url().'place-new-bid/'.$_POST['id_produk'].'?'.http_build_query($_GET));
			}
		}
		
		public function hapus_bid()
		{
			$id_produk = $this->uri->segment(2,0);
			$id_d_auction = $this->uri->segment(3,0);
			$this->M_home->hapus_bid($id_d_auction);
			header('Location: '.base_url().'place-new-bid/'.$id_produk.'?'.http_build_query($_GET));
		}
		
	}
	
?>