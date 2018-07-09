<?php
	class M_harga_satuan extends CI_Model 
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function list_harga_limit($kode_kantor,$cari,$offset,$limit)
		{
			$query = $this->db->query("CALL SP_PRODUKHARGASATUAN('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function getIdsatuan($kode_satuan)
		{
			$query = "SELECT * FROM tb_satuan WHERE kode_satuan = '".$kode_satuan."' ;";
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_field($kode_kantor,$cari,$offset,$limit)
		{
			$query = $this->db->field_data("CALL SP_PRODUKHARGASATUAN('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
			
			return $query;
			
		}
		
		
		function count_harga_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_produk) AS JUMLAH FROM tb_produk ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan($id_satuan,$id_produk,$nilai,$user,$kode_kantor)
		{
			
			/*
				cek dulu, produk dan satuan nya, 
				klo satuannya sama dengan satuan default, insert/update ke tabel tb_harga
				klo beda berarti datanya masuk ke tb_satuan_konversi
			*/
			
			$cek = $this->db->query("SELECT * FROM tb_produk
									 WHERE id_produk = '".$id_produk."' AND id_satuan = '".$id_satuan."' AND kode_kantor = '".$kode_kantor."'"); 
									 
			
				if($cek->num_rows() > 0)
				{
					$query = $this->db->query("SELECT * FROM tb_harga WHERE id = '".$id_produk."' AND group_by = 'produks' AND kode_kantor = '".$kode_kantor."'"); 
					
					if($query->num_rows() > 0)
					{				
						//update
						/*$jam = date('Y-m-d H:i:s'); 
						$data = array
						(
						   'harga' => $nilai,
						   'tgl_updt' => $jam,
						   'user_updt' => $user
						);

						$this->db->where('id', $id_produk);
						$this->db->update('tb_harga', $data);*/
						
						$query = "UPDATE tb_harga SET harga = '".$nilai."', user_updt = '".$user."', tgl_updt = NOW() WHERE id = '".$id_produk."' AND group_by = 'produks' AND kode_kantor = '".$kode_kantor."';";
						$this->db->query($query);
						
					} else {
						//insert
						/*$jam = date('Y-m-d H:i:s'); 
						
						$data = array
						(
						   'id' => $id_produk,
						   'group_by' => 'produks',
						   'nama_harga' => 'harga normal',
						   'harga' => $nilai,
						   'ket_harga' => '',
						   'set_default' => 1,
						   'tgl_ins' => $jam,
						   'user_updt' => $user,
						   'kode_kantor' => $kode_kantor
						);

						$this->db->insert('tb_harga', $data); */
						
						$query = "INSERT INTO tb_harga
						 (
							 id_harga
							 ,id
							 ,group_by
							 ,nama_harga
							 ,harga
							 ,ket_harga
							 ,set_default
							 ,tgl_ins
							 ,kode_kantor
							 ,user_updt
						 )
						 Values
						 (
							 (
								 SELECT CONCAT('HRG',FRMTGL,ORD) AS id_harga
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
										 COALESCE(MAX(CAST(RIGHT(id_harga,5) AS UNSIGNED)) + 1,1) AS ORD
										 From tb_harga
										 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
										 AND kode_kantor = '".$kode_kantor."'
									 ) AS A
								 ) AS AA
							 )
							 ,'".$id_produk."'
							 ,'produks'
							 ,'Harga Normal'
							 ,'".$nilai."'
							 ,''
							 ,1
							 ,NOW()
							 ,'".$kode_kantor."'
							 ,'".$user."'
						 )";
					$this->db->query($query);
						
						
					}	
					

			
				}
				else
				{
					//update
					$jam = date('Y-m-d H:i:s'); 

					$query = $this->db->query("UPDATE tb_satuan_konversi
												SET harga_jual = ".$nilai.",user_updt = '".$user."',tgl_updt = NOW()
												WHERE id_produk = '".$id_produk."' AND id_satuan = '".$id_satuan."' AND kode_kantor = '".$kode_kantor."';"); 
												
												
				} 
			
			
			
			 
			
		}
		
	}
?>