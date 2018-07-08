        <style><!-- 
        SPAN.searchword { background-color:yellow; }
        // -->
        </style>
        <script src="<?=base_url();?>assets-toko/global/js/searchhi.js" type="text/javascript" language="JavaScript"></script>
        <script language="JavaScript"><!--
        function loadSearchHighlight()
        {
          SearchHighlight();
          document.searchhi.h.value = searchhi_string;
          if( location.hash.length > 1 ) location.hash = location.hash;
        }
        // -->
        </script>
    <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
          <div class="user-panel">
            <div class="pull-left image">
              <img src="<?php echo $this->session->userdata('ses_avatar_url');?>" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
              <p><?php echo $this->session->userdata('ses_nama_karyawan');?></p>
              <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
          </div>
          <!-- search form -->
          <form method="get" name="searchhi" class="sidebar-form" action="#">
            <div class="input-group">
              <input type="text" name="h" onkeyup="oWhichSubmit(this)" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
          </form>
          <!-- /.search form -->
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
			<!-- CEK HAK AKSES FROM DATABASE -->
				<?php
					/*$akses_group1 = $this->m_akun->get_hak_akses_group1($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group = $this->m_akun->get_hak_akses_group1_main_group($this->session->userdata('ses_id_jabatan'));
					$akses_group1_main_group_sub_main = $this->m_akun->get_hak_akses_group1_main_group_sub_group($this->session->userdata('ses_id_jabatan'));*/
				?>
			<!-- CEK HAK AKSES FROM DATABASE -->
		  
            <li class="header">MAIN NAVIGATION</li>
            <!-- <li class="active treeview"> -->
			<li id="dashboard" class="treeview">
              <a href="<?=base_url()?>kelola-toko"><i class="fa fa-dashboard"></i> <span>Dashboard</span></a>
            </li>
			
			<!-- CEK AKSES GROUP1 = 1 -->
			
				<!--<li id="inputdata" class="treeview">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Input Data (Basis Data)</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">-->
					
					<!-- CEK AKSES PRODUK -->
							<!--<li id="input-data-produk">
							  <a href="#"><i class="fa fa-folder"></i> Produk <i class="fa fa-angle-left pull-right"></i></a>
							  <ul class="treeview-menu">
								
									<li id="input-data-produk-satuan"><a href="<?=base_url()?>toko-satuan"><i class="fa fa-circle-o"></i> Satuan Produk </a></li>
								
									<li id="input-data-produk-produk"><a href="<?=base_url()?>toko-produk"><i class="fa fa-circle-o"></i> Produk </a></li>
							  </ul>
							</li>-->
					<!-- CEK AKSES PRODUK -->
					
				  <!--</ul>
				</li>-->
				
				<li id="input-data-produk-produk" class="treeview">
				  <a href="<?=base_url()?>toko-produk">
					<i class="fa fa-folder"></i> <span>Produk</span>
				  </a>
				</li>
			<!-- CEK AKSES GROUP1 = 1 -->
			
			<!-- CEK AKSES GROUP1 = 4 OPERASI-->
				<li class="treeview" id="operasi">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Operasi</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					<!--<li id="operasi-konversi-satuan"><a href="<?=base_url()?>admin-satuan-konversi"><i class="fa fa-circle-o"></i> Konversi Satuan Produk </a></li>-->
					<li id="operasi-harga-produk"><a href="<?=base_url()?>toko-harga-satuan"><i class="fa fa-circle-o"></i> Harga Satuan Produk </a></li>
					<!--<li id="operasi-harga-member"><a href="<?=base_url()?>admin-harga-member"><i class="fa fa-circle-o"></i> Harga Satuan Member </a></li>-->
					
							<!--<li id="operasi-diskon">
							  
							  <a href="#"><i class="fa fa-folder"></i> Diskon <i class="fa fa-angle-left pull-right"></i></a>-->
							  
							  
							  <!--<ul class="treeview-menu">
									<li id="operasi-diskon-produk"><a href="<?=base_url()?>toko-diskon-produk"><i class="fa fa-circle-o"></i> Diskon per Produk </a></li>
							  </ul>-->
							<!--</li>-->
							
							<li id="operasi-auction">
							  
							  <a href="<?php echo base_url();?>admin-auction"><i class="fa fa-folder"></i> Auction</a>
							</li>
					<!-- CEK AKSES KARYAWAN -->
				  </ul>
				</li>
		
			<!-- CEK AKSES GROUP1 = 4 OPERASI-->
			
			<!-- CEK AKSES GROUP1 = 4 TRANSAKSI-->
				<li class="treeview" id="transaksi">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Transaksi</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					<!--<li id="transaksi-pembelian">
					  <a href="#"><i class="fa fa-folder"></i> Pembelian <i class="fa fa-angle-left pull-right"></i></a>
					  
					  <ul class="treeview-menu">

						<li id="transaksi-penerimaan"><a href="<?=base_url()?>admin-transaksi-h-pembelian"><i class="fa fa-circle-o"></i> Pembelian </a></li>

					  </ul>
					</li>
					-->
					<li id="transaksi-penjualan"><a href="<?=base_url()?>history-penjualan"><i class="fa fa-circle-o"></i> Penjualan </a></li>
					<!--<li id="transaksi-penjualan-mitra"><a href="<?=base_url()?>history-penjualan-mitra"><i class="fa fa-circle-o"></i> Penjualan Mitra </a></li>-->
					<!--<li id="transaksi-retur">
					  <a href="#"><i class="fa fa-folder"></i> Retur <i class="fa fa-angle-left pull-right"></i></a>
					  
					  <ul class="treeview-menu">

						<li id="transaksi-retur-pembelian"><a href="<?=base_url()?>admin-jabatan"><i class="fa fa-circle-o"></i> Retur Pembelian </a></li>

						<li id="transaksi-retur-penjualan"><a href="<?=base_url()?>admin-karyawan"><i class="fa fa-circle-o"></i> Retur Penjualan </a></li>

					  </ul>
					</li>-->
				  </ul>
				</li>
			<!-- CEK AKSES GROUP1 = 4 TRANSAKSI-->
			
			<!-- CEK AKSES GROUP1 = 5 LAPORAN-->
				<li class="treeview" id="laporan">
				  <a href="#">
					<i class="fa fa-laptop"></i> <span>Laporan</span>
					<i class="fa fa-angle-left pull-right"></i>
				  </a>
				  <ul class="treeview-menu">
					
					<!-- CEK AKSES LAPORAN PENJUALAN -->
							<li id="laporan-produk"><a href="<?=base_url()?>admin-laporan-penjualan"><i class="fa fa-circle-o"></i> Laporan Penjualan</a></li>
					<!-- CEK AKSES LAPORAN PENJUALAN -->
					
	
					<!-- CEK AKSES LAPORAN RINCIAN PRODUK -->
							<li id="laporan-member"><a href="<?=base_url()?>admin-laporan-rincian-produk"><i class="fa fa-circle-o"></i> Laporan Buyer</a></li>
					<!-- CEK AKSES LAPORAN RINCIAN PRODUK -->
					
					<!-- CEK AKSES LAPORAN COSTUMER -->
						<!--	<li id="laporan-supplier"><a href="<?=base_url()?>admin-laporan-costumer"><i class="fa fa-circle-o"></i> Laporan Supplier</a></li>-->
					<!-- CEK AKSES LAPORAN COSTUMER -->

	
				  </ul>
				</li>
			<!-- CEK AKSES GROUP1 = 5 LAPORAN-->
			
			
			
			
			<!-- CEK AKSES GROUP1 = 7 SETTING-->
				<!--	<li id="setting-stock" class="treeview">
					  <a href="<?=base_url()?>admin-setting-stock">
						<i class="fa fa-share"></i> <span>Setting/backup Stock Produk</span>
					  </a>
					</li>
			<!-- CEK AKSES GROUP1 = 7 SETTING-->
			
			
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>