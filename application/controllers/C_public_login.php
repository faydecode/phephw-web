<?php

	class C_public_login extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->helper(array('form','url'));
			$this->load->model(array('M_public_get','M_member','M_email','M_akun'));
			$this->load->library(array('form_validation','email'));
			
		}
	
		public function index()
		{
			$this->load->view('front/page_login.html');
		}
		
		public function login()
		{
			$id_buyer = $this->M_member->get_kat_member()->BUYER;
			$id_seller = $this->M_member->get_kat_member()->SELLER;
			
			$data = array('is_login'=>false,'id_buyer'=>$id_buyer,'id_seller'=>$id_seller);
			
			$this->load->view('front/page_login.html',$data);
		}
		
		public function register()
		{
			$id_buyer = $this->M_member->get_kat_member()->BUYER;
			$id_seller = $this->M_member->get_kat_member()->SELLER;
			
			$data = array('is_login'=>false,'id_buyer'=>$id_buyer,'id_seller'=>$id_seller);
			$this->load->view('front/page_register.html',$data);
		}
		
		function cek_login()
		{
			$user = $_POST['user'];
            $pass = $_POST['password'];
			$tipe = $_POST['tipe'];
			
            $data_login = $this->M_public_get->get_login_costumer($user,md5($pass),$tipe);
    		if(!empty($data_login))
    		{
				//if($data_login->stat_member == "1")
				//{
					if ($data_login->avatar <> "")
					{
						$src = $data_login->avatar_url;
					}
					else
					{
						$src = base_url().'assets/global/users/loading.gif';
					}
					
					$member = array(
						'ses_public_id_user'  => $data_login->id_costumer,
						'ses_public_user'  => $user,
						'ses_public_pass'  => base64_encode($pass),
						'ses_public_nama_member' => $data_login->nama_lengkap,
						'ses_public_no_costumer' => $data_login->no_costumer,
						'ses_public_avatar_url' => $src,
						'ses_public_tgl_lahir' => $data_login->tgl_lahir,
						'ses_public_alamat' => $data_login->alamat_rumah_sekarang,
						'ses_public_hp' => $data_login->hp,
						'ses_public_tgl_lahir' => $data_login->tgl_lahir,
						'ses_public_kat_costumer' => $data_login->nama_kat_costumer,
						'ses_public_email' => $data_login->email_costumer,
						'ses_kode_kantor' => $data_login->id_costumer
					);
	 
					$this->session->set_userdata($member);
					//redirect('index.php/admin','location');
					header('Location: '.base_url());
				//} else {
				//	$this->session->set_flashdata('msg','Anda belum melakukan verifikasi email untuk akun ini. Silakan cek email verifikasi atau klik disini untuk melakukan verifikasi ulang.');  
				//	$this->session->set_flashdata('title','VERIFIKASI');
					//header('Location: '.base_url().'daftar-sukses');
				//	$data = array('is_login'=>false);
				//	$this->load->view('front/page/register_sukses.html',$data);
				//}
    		}
    		else
    		{
    			//redirect('index.php/login','location');
				$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Username atau password salah</div>');
				header('Location: '.base_url().'login-akun');
    		}
		}
		
		function do_register()
		{
			$kode_member = $this->M_akun->get_no_costumer()->no_costumer;
			$foto = '';
			
			//validasi form dlu
			$this->form_validation->set_rules('username','Username','required|is_unique[tb_costumer.username]'); 
			//$this->form_validation->set_rules('email','Email','required|valid_email|is_unique[tb_costumer.email_costumer]'); 
			$this->form_validation->set_rules('password', 'Password', 'required|min_length[6]|max_length[15]');  
			$this->form_validation->set_rules('password2', 'Password Confirmation', 'required|matches[password]');  
			
			if($this->form_validation->run() == FALSE)
			{
				$this->session->set_flashdata('msg',validation_errors());  
				header('Location: '.base_url().'daftar-akun');
			} else {
				$email = $_POST['email'];
				
				//generate id uniq
				$saltid = md5($email);
				$saltusername = md5($_POST['username']);
				$status = 0;
				
				if($this->M_member->simpan
				(
					$_POST['tipe']  //sementara di patok retail
					,''
					,$kode_member
					,$_POST['nama']
					,$_POST['panggilan']
					,$_POST['tgl_lahir']
					,$_POST['jkel']
					,''
					,$_POST['hp']
					,$_POST['username']
					,md5($_POST['password'])
					,$foto
					,base_url().'assets/images/member/'.$foto
					,$_POST['email']
					,''
					,''
					,''
				)) {
					//if($this->sendemail($email,$saltid,$_POST['panggilan'],$saltusername))
					//{
						//sukses kirim email
						$this->session->set_flashdata('msg','Pendaftaran akun berhasil. Silakan login.');  
						$this->session->set_flashdata('title','REGISTRASI');
						header('Location: '.base_url().'daftar-sukses');
						
					//} else {
					//	$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Please try again ...</div>');  
					//	header('Location: '.base_url().'daftar-akun');
					//}
				} else {
					//gagal simpan
					$this->session->set_flashdata('msg','<div class="alert alert-danger text-center">Something Wrong. Please try again ...</div>');  
					header('Location: '.base_url().'daftar-akun');
				}
				
			}
			
			
			
			//header('Location: '.base_url().'daftar-sukses');
			
		}
		
		function register_sukses()
		{
			$data = array('is_login'=>false);
			$this->load->view('front/page/register_sukses.html',$data);
		}
		
		function logout()
		{
			$this->session->unset_userdata('ses_public_id_user');
			$this->session->unset_userdata('ses_public_user');
			$this->session->unset_userdata('ses_public_pass');
			$this->session->unset_userdata('ses_public_nama_member');
            $this->session->unset_userdata('ses_public_no_costumer');
			$this->session->unset_userdata('ses_public_avatar_url');
			$this->session->unset_userdata('ses_public_tgl_lahir');
			$this->session->unset_userdata('ses_public_alamat');
			$this->session->unset_userdata('ses_public_hp');
			$this->session->unset_userdata('ses_public_tgl_lahir');
			$this->session->unset_userdata('ses_public_kat_costumer');
			$this->session->unset_userdata('ses_public_email');
			$this->session->unset_userdata('ses_kode_kantor');
			
			//redirect('index.php/login','location');
			header('Location: '.base_url());
		}
		
		function sendemail($email,$saltid,$nama,$saltusername)
		{  
			
			
			//Load email library
			$this->load->library('email');
			$this->load->config('email');
			$url = base_url()."v/".$saltid."/".$saltusername;

			
			$this->email->set_mailtype("html");
			$this->email->set_newline("\r\n");

			//Email content
			$htmlContent = "<h1>Verifikasi Akun</h1>";
			$htmlContent .= "<html><head><head></head><body><p>Hai {$nama},</p><p>Terima kasih telah mendaftar di warungakinini.com.</p><p>Silakan klik link di bawah ini untuk melakukan verifikasi.</p>".$url."<br/><p>Hormat kami,</p><p>Tim Warungakinini</p></body></html>";

			$this->email->to($email);
			$this->email->from('ryuur3i@gmail.com','Warungakinini.com');
			$this->email->subject('Please Verify Your Email Address');
			$this->email->message($htmlContent);

			//Send email
			//$this->email->send();
			
			if($this->email->send())
			{
				echo $this->email->print_debugger();
				return true;
			} else {
				echo $this->email->print_debugger();
				return false;
			}

		}  
		
		public function verifikasi()  
		{  
		  $hashemail = $this->uri->segment(2,0);
		  $hashuser = $this->uri->segment(3,0);
		
		
		  if($this->M_email->verifyemail($hashemail,$hashuser))  
		  {  
			$this->session->set_flashdata('msg','Proses aktivasi akun berhasil. Silakan klik <a href="'.base_url().'login-akun">disini</a> untuk melakukan login');
			$this->session->set_flashdata('title','AKTIVASI');
			header('Location: '.base_url().'daftar-sukses');
		  }  
		  else  
		  {  
			$this->session->set_flashdata('msg','Proses aktivasi gagal. Silakan lakukan registrasi ulang');
			$this->session->set_flashdata('title','AKTIVASI');
			header('Location: '.base_url().'daftar-sukses');
		  }  
	    }  
		
	}

?>