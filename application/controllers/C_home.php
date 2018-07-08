<?php

	class C_home extends CI_Controller
	{
		public function __construct()
		{
			parent::__construct();
			$this->load->model(array('M_home','M_auction'));
			$this->load->library(array('cart')); 
			
		}
	
		public function index()
		{
			
			if((!empty($_GET['cari'])) && ($_GET['cari']!= "")  )
			{
				$cari = $_GET['cari'];
			} else {
				$cari = "";
			}
			
			//informasi login
			if(($this->session->userdata('ses_public_user') == null) or ($this->session->userdata('ses_public_pass') == null))
			{
				$is_login=false;
				$nama_member = '';
			} else {
				$is_login=true;
				$nama_member = $this->session->userdata('ses_public_user');
			}
			
			
			//$type = $this->uri->segment(2,0);
			
			$list_kategori = $this->M_home->list_kategori();
			$list_produk = $this->M_home->list_produk('','1',0,20);
			$list_hot_deal = $this->M_home->list_hot_deal(0,10);
			
			$list_auction = $this->M_auction->list_auction_limit(' WHERE DATE(date_mulai) >= DATE(NOW()) AND DATE(date_sampai) >= DATE(NOW())',0,1000);
			
			$list_cart = $this->cart->contents();
			$total_item = $this->cart->total_items();
			$total_price = $this->cart->total();
			
			$data = array('list_kategori' => $list_kategori,'list_auction' => $list_auction,'list_produk' => $list_produk,'list_hot_deal' => $list_hot_deal,'aktif_item' => 'all',
						  'list_cart' => $list_cart, 'total_item' => $total_item,'total_price'=>$total_price,'isCart' => '0',
						  'is_login'=>$is_login,'nama_member'=>$nama_member
						);
			
			$this->load->view('front/home.html',$data);
		}
		
		public function by()
		{
			$type = $_POST['type'];
			
			if($type==2) $type = '';
			
			$list_produk = $this->M_home->list_produk('',$type,0,20);
			
			if($type != 0)
			{
				$list_auction = $this->M_auction->list_auction_limit(' WHERE DATE(date_mulai) >= DATE(NOW()) AND DATE(date_sampai) >= DATE(NOW()) ',0,1000);
				echo'<ul class="list-unstyled nomargin nopadding text-left">';
										
					if(!empty($list_auction))
					{  
						$list_result = $list_auction->result();
						
						foreach($list_result as $row)
						{
					
							echo'
							<li class="clearfix"><!-- item -->
								<div class="thumbnail featured clearfix pull-left">
									<a href="<?php echo base_url(); ?>p/'.$row->id_produk.'">
										<img  src="'.base_url().'assets/images/produk/'.$row->IMG_FILE.'" width="120" height="120" alt="featured item">
									</a>
								</div>

								<a class="block size-12" href="'.base_url().'p/'.$row->kode_produk.'">'.$row->nama_produk.'</a>
								<div class="size-12 text-left">Rp '. number_format($row->harga_mulai).'</div>
								<!-- <a class="block size-12"> -->
									<!-- <?php echo $row->nama_supplier; ?> -->
								<!-- </a> -->
								<div class="rating rating-4 size-13 width-100 text-left"><!-- rating-0 ... rating-5 --></div>
								<div class="size-12 text-left">
									'.$row->tag_produk.'
								</div>
							</li><!-- /item -->
							';
					
						}
					}
					
					
				echo'</ul>';
			}
			else
			{
			
				echo '<ul class="list-unstyled nomargin nopadding text-left">';
				
				
				if(!empty($list_produk)) 
				{  
					$list_result = $list_produk->result();
					
					foreach($list_result as $row)
					{

				
						echo '<li class="clearfix">';
						echo '	<div class="thumbnail featured clearfix pull-left">';
						echo '		<a href="'.base_url().'p/'.$row->id_produk.'">';
						echo '			<img  src="'.base_url().'assets/images/produk/'.$row->avatar.'" width="120" height="120" alt="featured item">';
						echo '		</a>';
						echo '	</div>';
						echo '	<a class="block size-12" href="'.base_url().'p/'.$row->kode_produk.'">'.$row->nama_produk.'</a>';
						echo '	<div class="size-12 text-left">Rp "'.number_format($row->harga).'</div>';
						echo '	<a class="block size-12">';
						echo '		'.$row->nama_supplier.'';
						echo '	</a>';
						echo '	<div class="rating rating-4 size-13 width-100 text-left"><!-- rating-0 ... rating-5 --></div>';
						echo '	<div class="size-12 text-left">';
						echo '		'.$row->tag_produk.'';
						echo '	</div>';
						echo '</li><!-- /item -->';
					}
				}
				echo '</ul>';
			}
		}
		
		
		public function detail_produk()
		{
			$kode_produk = $this->uri->segment(2,0);
			$detail_produk = $this->M_home->detail_produk($kode_produk);
			$detail_images = $this->M_home->detail_images_produk($kode_produk);
			$list_kategori = $this->M_home->list_kategori();
			$list_hot_deal = $this->M_home->list_hot_deal(0,10);
			
			$kat_produk = $this->M_home->get_kat_produk_by($kode_produk)->nama_kat_produk;
			$related_produk = $this->M_home->list_produk($kat_produk,'',0,10);
			
			$data = array('list_kategori' => $list_kategori,'detail_produk'=>$detail_produk,
						  'detail_images'=> $detail_images,'list_hot_deal' => $list_hot_deal,'related_produk'=>$related_produk
						  , 'isCart' => '0'
						  );
			
			$this->load->view('front/page/detail_produk.html',$data);
		}
		
		public function produk()
		{
			$id_kat_produk = $this->uri->segment(2,0);
			$list_cart = $this->cart->contents();
			$total_item = $this->cart->total_items();
			$total_price = $this->cart->total();
			
			if($id_kat_produk == "all") 
			{
				$id_kat_produk = '';
				$aktif_item = 'all';
			} else {
				$aktif_item = $id_kat_produk;
			}
			
			$list_kategori = $this->M_home->list_kategori();
			$list_produk = $this->M_home->list_produk($id_kat_produk,'',0,10);
			//$count_produk = $this->M_home->count_produk_limit($id_kat_produk)->JUMLAH;
			
			$data = array('list_kategori' => $list_kategori, 'page' => 'home','list_produk' => $list_produk, 'aktif_item' => $aktif_item,
  						  'list_cart' => $list_cart, 'total_item' => $total_item,'total_price'=>$total_price,'isCart' => '0'
						);
			
			$this->load->view('front/home.html',$data);
		}
	}
?>