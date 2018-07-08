<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_admin_auction extends CI_Controller 
{
	public function __construct()
	{
		parent::__construct();
		$this->load->model(array('M_auction','M_produk'));
	}
	
	public function index()
	{
		/*if(($this->session->userdata('ses_user_admin') == null) or ($this->session->userdata('ses_pass_admin') == null))
		{
			header('Location: '.base_url().'toko-login');
		}
		else
		{
			$cek_ses_login = $this->M_akun->get_cek_login($this->session->userdata('ses_user_admin'),md5(base64_decode($this->session->userdata('ses_pass_admin'))));
			
			if(!empty($cek_ses_login))
			{
		*/	
				if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
				{
					$cari = $_GET['cari'];
				}
				else
				{
					$cari = "";
				}
				
				$this->load->library('pagination');
				$config['first_url'] = site_url('admin-auction?'.http_build_query($_GET));
				$config['base_url'] = site_url('admin-auction/');
				$config['total_rows'] = $this->M_auction->count_auction_limit($cari)->JUMLAH;
				$config['uri_segment'] = 2;	
				$config['per_page'] = 10;
				$config['num_links'] = 2;
				$config['suffix'] = '?' . http_build_query($_GET, '', "&");
				$config['first_page'] = 'Awal';
				$config['last_page'] = 'Akhir';
				$config['next_page'] = '&laquo;';
				$config['prev_page'] = '&raquo;';
				
				
				$config['full_tag_open'] = '<div><ul class="pagination">';
				$config['full_tag_close'] = '</ul></div>';
				$config['first_link'] = '&laquo; First';
				$config['first_tag_open'] = '<li class="prev page">';
				$config['first_tag_close'] = '</li>';
				$config['last_link'] = 'Last &raquo;';
				$config['last_tag_open'] = '<li class="next page">';
				$config['last_tag_close'] = '</li>';
				$config['next_link'] = 'Next &rarr;';
				$config['next_tag_open'] = '<li class="next page">';
				$config['next_tag_close'] = '</li>';
				$config['prev_link'] = '&larr; Previous';
				$config['prev_tag_open'] = '<li class="prev page">';
				$config['prev_tag_close'] = '</li>';
				$config['cur_tag_open'] = '<li class="active"><a href="">';
				$config['cur_tag_close'] = '</a></li>';
				$config['num_tag_open'] = '<li class="page">';
				$config['num_tag_close'] = '</li>';
				
				
				//inisialisasi config
				$this->pagination->initialize($config);
				$halaman = $this->pagination->create_links(); 
				
				$list_auction = $this->M_auction->list_auction_limit($cari,$this->uri->segment(2,0),$config['per_page']);
				$list_produk = $this->M_produk->list_produk_limit('',1000,0);
				
				$data=array('page_content'=>'admin_auction','list_auction'=>$list_auction,'halaman'=>$halaman,'list_produk' => $list_produk);
				$this->load->view('toko/container',$data);
			/*}
			else
			{
				header('Location: '.base_url().'toko-login');
			}
			
		}*/
	}
	
	public function simpan()
	{
		if (!empty($_POST['stat_edit']))
		{
			$this->M_auction->edit(
					$_POST['stat_edit'],
					$_POST['id_produk'],
					$_POST['id_member'],
					$_POST['kode_auction'],
					$_POST['nama_auction'],
					$_POST['date_mulai'],
					$_POST['date_sampai'],
					$_POST['harga_mulai'],
					$_POST['ket_auction'],
					$_POST['verified'],
					$this->session->userdata('ses_public_id_user'),
					$this->session->userdata('ses_kode_kantor')
				);
			header('Location: '.base_url().'admin-auction?'.http_build_query($_GET));
		}
		else
		{
			$this->M_auction->simpan
				(
					$_POST['id_produk'],
					$_POST['id_member'],
					$_POST['kode_auction'],
					$_POST['nama_auction'],
					$_POST['date_mulai'],
					$_POST['date_sampai'],
					$_POST['harga_mulai'],
					$_POST['ket_auction'],
					$_POST['verified'],
					$this->session->userdata('ses_public_id_user'),
					$this->session->userdata('ses_public_id_user'),
					$this->session->userdata('ses_kode_kantor')
				);
			header('Location: '.base_url().'admin-auction?'.http_build_query($_GET));
		}
		
		//echo 'ade';
	}
		
}