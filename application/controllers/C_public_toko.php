<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class C_public_toko extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		// Your own constructor code
		$this->load->model(array('M_public_profile'));
	}
	
	public function index()
	{
		if((!empty($_GET['cari_alamat'])) && ($_GET['cari_alamat']!= ""))
		{
			$cari = $_GET['cari_alamat'];
		} else {
			$cari = '';
		}
		
		$data_profile = $this->M_public_profile->get_data_costumer($this->session->userdata('ses_public_id_user'));
		$list_alamat = $this->M_public_profile->list_alamat($this->session->userdata('ses_public_id_user'),$cari);
		$list_prov = $this->M_public_profile->load_prov();
		$get_toko = $this->M_public_profile->get_toko($this->session->userdata('ses_public_id_user'));
		
		$data = array(
			'id_costumer'=>$data_profile->id_costumer,
			'no_costumer'=>$data_profile->no_costumer,
			'tgl_pengajuan'=>$data_profile->tgl_pengajuan,
			'nama_lengkap'=>$data_profile->nama_lengkap,
			'panggilan'=>$data_profile->panggilan,
			'hp'=>$data_profile->hp,
			'alamat_rumah'=>$data_profile->alamat_rumah_sekarang,
			'avatar'=>$data_profile->avatar2,
			'avatar_url'=>$data_profile->avatar_url,
			'email'=>$data_profile->email_costumer,
			'username'=>$data_profile->username,
			'list_alamat'=>$list_alamat,
			'list_prov'=>$list_prov,
			'get_toko'=>$get_toko
		);
			
		$this->load->view('front/page/kelola_toko.html',$data);
	}
	
	public function aktivasi()
	{
		$this->M_public_profile->aktivasi_toko(
			$this->session->userdata('ses_public_id_user'),
			$_POST['nama'],
			$_POST['detail_alamat'],
			$_POST['kecamatan'],
			$_POST['kabupaten'],
			$_POST['provinsi'],
			$_POST['kodepos'],
			$_POST['keterangan']
		);
		
		header('Location: '.base_url().'kelola-toko');
		
	}
}