<?php 

class M_public_cart extends CI_Model {

	function __construct()
	{
		parent::__construct();
	}
		
	function tampil_produk()
	{
		$q=$this->db->query("SELECT * from barang");
		return $q;
	}
	
	function get_produk_id($id_produk)
	{
		$query = $this->db->get_where('tb_produk', array('id_produk' => $id_produk));
		if($query->num_rows() > 0)
		{
			return $query;
		}
		else
		{
			return false;
		}
	}
	
	function list_alamat($id_costumer)
	{
		$query = $this->db->query("
			SELECT id_alamat,nama_alamat,CONCAT(detail_alamat,' Kec.',D.name,', ',REPLACE(C.name,'KABUPATEN',''),' ',B.name,' ',kodepos) AS detail_alamat
			FROM tb_alamat A
			LEFT JOIN provinces B
			  ON A.provinsi = B.id
			LEFT JOIN regencies C
			  ON A.kabkota = C.id
			LEFT JOIN districts D
			  ON A.kecamatan = D.id
			WHERE id_costumer = '".$id_costumer."'
		");
		
		if($query->num_rows() > 0)
		{
			return $query;
		}
		else
		{
			return false;
		}
	}
	
	function get_no_faktur()
	{
		$query = $this->db->query(
		"
			SELECT CONCAT('FKT',FRMTGL,ORD) AS no_faktur
			 From
			 (
				 SELECT CONCAT(Y,M) AS FRMTGL
				  ,CASE
					 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
					 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
					 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
					 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
					 ELSE CONCAT('0000',CAST(ORD AS CHAR))
					 END As ORD
				 From
				 (
					 SELECT
					 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
					 CAST(MID(NOW(),6,2) AS CHAR) AS M,
					 MID(NOW(),9,2) AS D,
					 COALESCE(MAX(CAST(RIGHT(no_faktur,5) AS UNSIGNED)) + 1,1) AS ORD
					 From tb_h_penjualan
					 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
					 
				 ) AS A
			 ) AS AA
		
		");
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
	function get_no_h_penjualan($id_costumer,$no_faktur)
	{
		$query = $this->db->query("
			SELECT id_h_penjualan FROM tb_h_penjualan
			WHERE id_costumer = '".$id_costumer."' AND no_faktur = '".$no_faktur."'
		");
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
	}
	
		
	function simpan_h_pesanan($id_costumer,$no_faktur,$biaya,$diskon,$bayar,$ket,$metode,$id_alamat)
	{
		$date = date("Y-m-d");
		
		$query = "
			INSERT INTO tb_h_penjualan
			(
						id_h_penjualan,
						 id_h_pemesanan,
						 id_costumer,
						 id_karyawan,
						 id_h_retur,
						 id_alamat,
						 no_faktur,
						 biaya,
						 diskon,
						 nominal_retur,
						 bayar,
						 tgl_pengajuan,
						 tgl_h_penjualan,
						 tgl_tempo,
						 status_penjualan,
						 isTunai,
						 ket_penjualan,
						 nama_set_roles,
						 bulan,
						 up,
						 optr_up,
						 naik_persen,
						 optr_naik_persen,
						 prsn_cicilan,
						 prsn_pokok,
						 prsn_marketing,
						 optr_marketing,
						 denda,
						 optr_denda,
						 frek_denda,
						 type_denda,
						 toleransi,
						 perkiraan_nominal,
						 type_h_penjualan,
						 tgl_ins,
						 tgl_updt,
						 user_updt,
						 kode_kantor,
						 sts_penjualan
			)
			VALUES (
					(
							 SELECT CONCAT('HP',FRMTGL,ORD) AS id_h_penjualan
							 From
							 (
								 SELECT CONCAT(Y,M) AS FRMTGL
								  ,CASE
									 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
									 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
									 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
									 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
									 ELSE CONCAT('0000',CAST(ORD AS CHAR))
									 END As ORD
								 From
								 (
									 SELECT
									 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
									 CAST(MID(NOW(),6,2) AS CHAR) AS M,
									 MID(NOW(),9,2) AS D,
									 COALESCE(MAX(CAST(RIGHT(id_h_penjualan,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_h_penjualan
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 
								 ) AS A
							 ) AS AA
					),
					'',
					'".$id_costumer."',
					'',
					'',
					'".$id_alamat."',
					'".$no_faktur."',
					'".$biaya."',     -- ongkos kirim, dll
					'".$diskon."',    -- diskon akumulasi
					'',
					'".$bayar."',     -- harga total+ongkir
					'0000-00-00',
					'".$date."', 
					'0000-00-00',  
					'".$metode."',
					'TUNAI',
					'".$ket."',
					'',
					'0',
					'0',
					'',
					'0',
					'',
					'0',
					'0',
					'0',
					'',
					'0',
					'',
					'0',
					'',
					'0',
					'0',
					'0',
					NOW(),
					'0000-00-00 00:00:00',
					'0',
					'',
					'PROSES' 
			);
		
		";
		//stat penjualan : PROSES VERIFIKASI,READY,SELESAI
		$this->db->query($query);
	}
	
	
	function simpan_d_pesanan($id_h_penjualan,$id_produk,$jumlah,$status_konversi,$konversi,$satuan_jual,
							  $jumlah_konversi,$diskon,$harga,$harga_konversi,$harga_ori,$ket
							)
	{
		$date = date("Y-m-d");
		$query = "
				INSERT INTO tb_d_penjualan
				(
						 id_d_penjualan,
							 id_h_penjualan,
							 id_d_penerimaan,
							 id_produk,
							 jumlah,
							 status_konversi,
							 konversi,
							 satuan_jual,
							 jumlah_konversi,
							 diskon,
							 harga,
							 harga_konversi,
							 harga_ori,
							 ket_d_penjualan,
							 tgl_ins,
							 tgl_updt,
							 user_updt,
							 kode_kantor)
				VALUES (
						(
							 SELECT CONCAT('DP',FRMTGL,ORD) AS id_d_penjualan
							 From
							 (
								 SELECT CONCAT(Y,M) AS FRMTGL
								  ,CASE
									 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
									 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
									 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
									 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
									 ELSE CONCAT('0000',CAST(ORD AS CHAR))
									 END As ORD
								 From
								 (
									 SELECT
									 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
									 CAST(MID(NOW(),6,2) AS CHAR) AS M,
									 MID(NOW(),9,2) AS D,
									 COALESCE(MAX(CAST(RIGHT(id_d_penjualan,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_d_penjualan
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 
								 ) AS A
							 ) AS AA
						 ),
						'".$id_h_penjualan."',
						'',
						'".$id_produk."',
						'".$jumlah."',    -- qty dalam satuan beli
						'".$status_konversi."',  -- kali/ bagi  ambil dari tb satuan konversi  -> status
						'".$konversi."',  -- ambil dari tb satuan konversi  -> besar_konversi
						'".$satuan_jual."',  -- satuan dari beli
						'".$jumlah_konversi."', -- jumlah * konversi
						'".$diskon."',
						'".$harga."',  -- harga penjualan dari satuan d pilih
						'".$harga_konversi."',  -- harga / konversi
						'".$harga_ori."',  -- harga / konversi
						'".$ket."',  -- keterangan diskon, beli 1 menang hiji
						NOW(),
						'0000-00-00 00:00:00',
						'0',
						'');
		";
		
		$this->db->query($query);
		
	}

	function get_harga_produk($id_produk,$kode_satuan)
	{
		$query = $this->db->query("
			SELECT A.id_produk,A.nama_produk,A.id_satuan,status as status_konversi,B.besar_konversi,C.kode_satuan,B.harga_jual
			FROM tb_produk A
			LEFT JOIN tb_satuan_konversi B
			  ON A.id_produk = B.id_produk
			LEFT JOIN tb_satuan C
			  ON B.id_satuan = C.id_satuan 
			WHERE A.id_produk = '".$id_produk."' AND C.kode_satuan = '".$kode_satuan."'
			UNION ALL
			SELECT A.id_produk,A.nama_produk,A.id_satuan,'*',0,B.kode_satuan,C.harga
			FROM tb_produk A
			LEFT JOIN tb_satuan B
			  ON A.id_satuan = B.id_satuan
			LEFT JOIN tb_harga C
			  ON A.id_produk = C.id AND group_by = 'produks' AND set_default = '1'
			WHERE A.id_produk = '".$id_produk."' AND B.kode_satuan = '".$kode_satuan."'			  
		");
		
		if($query->num_rows() > 0)
		{
			return $query->row();
		}
		else
		{
			return false;
		}
		
	}
	
	// Updated the shopping cart
	function validate_update_cart(){
		
		// Get the total number of items in cart
		$total = $this->cart->total_items();
		
		// Retrieve the posted information
		$item = $this->input->post('rowid');
	    $qty = $this->input->post('qty');

		// Cycle true all items and update them
		for($i=0;$i < $total;$i++)
		{
			// Create an array with the products rowid's and quantities. 
			$data = array(
               'rowid' => $item[$i],
               'qty'   => $qty[$i]
            );
            
            // Update the cart with the new information
			$this->cart->update($data);
		}

	}
	
	// Add an item to the cart
	function validate_add_cart_item(){
		
		$id = $this->input->post('product_id'); // Assign posted product_id to $id
		$cty = $this->input->post('quantity'); // Assign posted quantity to $cty
		
		$this->db->where('kode_barang', $id); // Select where id matches the posted id
		$query = $this->db->get('barang', 1); // Select the products where a match is found and limit the query by 1
		
		// Check if a row has been found
		if($query->num_rows > 0){
		
			foreach ($query->result() as $row)
			{
			    $data = array(
               		'id'      => $id,
               		'qty'     => $cty,
               		'price'   => $row->harga,
               		'name'    => $row->nama_barang
            	);

				$this->cart->insert($data); 
				
				return TRUE;
			}
		
		// Nothing found! Return FALSE!	
		}else{
			return FALSE;
		}
	}
}
