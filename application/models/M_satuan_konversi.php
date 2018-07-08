<?php
	class M_satuan_konversi extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_konversi_limit($kode_kantor,$cari,$offset,$limit)
		{
			$query = $this->db->query("CALL SP_PRODUKSATUANKONVERSI('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
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
			$query = $this->db->field_data("CALL SP_PRODUKSATUANKONVERSI('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
			
			return $query;
			
		}
		
		
		
		function count_konversi_limit($cari)
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
			
			//$query = $this->db->get_where('tb_satuan', array('kode_satuan' => $kode));
			
			//$id_satuan = $query->row();
			
			//$test = $id_satuan->id_satuan;
			
			$cek = $this->db->query("SELECT id_satuan_konversi 
									FROM tb_satuan_konversi A
									LEFT JOIN tb_satuan B ON A.id_satuan = B.id_satuan AND A.kode_kantor = B.kode_kantor
									WHERE A.id_produk = '".$id_produk."' AND A.id_satuan = '".$id_satuan."' AND A.kode_kantor = '".$kode_kantor."'"); 
									
			if($nilai == 0)
			{
				$this->db->query("DELETE FROM tb_satuan_konversi WHERE id_produk = '".$id_produk."' AND id_satuan = '".$id_satuan."' AND kode_kantor = '".$kode_kantor."'"); 
			}
			else
			{
				if($cek->num_rows() > 0)
				{
					//update
					$this->db->query("UPDATE tb_satuan_konversi
										SET besar_konversi = '".$nilai."'
										WHERE id_produk = '".$id_produk."' AND id_satuan = '".$id_satuan."' AND kode_kantor = '".$kode_kantor."'"); 
										
										
				}
				else
				{
					//insert
					/*$jam = date('Y-m-d H:i:s'); 
					
					$data = array
					(
					   'id_produk' => $id_produk,
					   'id_satuan' => $id_satuan,
					   'status' => '0',
					   'besar_konversi' => $nilai,
					   'harga_jual' => 0,
					   'ket_satuan_konversi' => '',
					   'tgl_ins' => $jam,
					   'user_updt' => $user,
					   'kode_kantor' => $kode_kantor
					);

					$this->db->insert('tb_satuan_konversi', $data); */
					
					$query = "INSERT INTO tb_satuan_konversi
					 (
						id_satuan_konversi
						 ,id_produk
						 ,id_satuan
						 ,status
						 ,besar_konversi
						 ,harga_jual
						 ,ket_satuan_konversi
						 ,tgl_ins
						 ,kode_kantor
						 ,user_updt
					 )
					 Values
					 (
						 (
							 SELECT CONCAT(FRMTGL,ORD) AS id_satuan_konversi
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
									 COALESCE(MAX(CAST(RIGHT(id_satuan_konversi,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_satuan_konversi
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 AND kode_kantor = '".$kode_kantor."'
								 ) AS A
							 ) AS AA
						 )
						 ,'".$id_produk."'
						 ,'".$id_satuan."'
						 ,'*'
						 ,'".$nilai."'
						 ,'0'
						 ,''
						 ,NOW()
						 ,'".$kode_kantor."'
						 ,'".$id_user."'
					 )";
					$this->db->query($query);

					
					
				}
			}
			
			 
			 
			
		}
		
		
	}
?>