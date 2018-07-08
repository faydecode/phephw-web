<?php
	class M_public_profile extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_data_costumer($id_costumer)
		{
			$query = $this->db->query("SELECT A.*,CASE WHEN avatar='' THEN 'noimage.png' ELSE avatar END AS avatar2
									   FROM tb_costumer A
									   WHERE id_costumer = '".$id_costumer."'");
										
			if($query->num_rows() > 0)
			{
				return $query->row();
			} else {
				return false;
			}
		}
		
		function get_toko($id_costumer)
		{
			$query = $this->db->query("SELECT nama_toko FROM tb_toko
									   WHERE id_costumer = '".$id_costumer."'");
										
			if($query->num_rows() > 0)
			{
				return $query->row();
			} else {
				return false;
			}
		}
		
		function aktivasi_toko($id_costumer,$nama_toko,$alamat,$kec,$kab,$prov,$kodepos,$ket)
		{
			$query = $this->db->query("
				INSERT INTO tb_toko
							(id_toko,
							 id_costumer,
							 kode_toko,
							 nama_toko,
							 alamat_toko,
							 id_kecamatan,
							 id_kota,
							 id_provinsi,
							 kodepos,
							 ket_toko,
							 tgl_ins)
				VALUES ((
					 SELECT CONCAT('TK',FRMTGL,ORD) AS id_toko
					 FROM
					 (
						 SELECT CONCAT(Y,M) AS FRMTGL
						  ,CASE
							 WHEN (ORD >= 10 AND ORD < 99) THEN CONCAT('000',CAST(ORD AS CHAR))
							 WHEN (ORD >= 100 AND ORD < 999) THEN CONCAT('00',CAST(ORD AS CHAR))
							 WHEN (ORD >= 1000 AND ORD < 9999) THEN CONCAT('0',CAST(ORD AS CHAR))
							 WHEN ORD >= 10000 THEN CAST(ORD AS CHAR)
							 ELSE CONCAT('0000',CAST(ORD AS CHAR))
							 END AS ORD
						 FROM
						 (
							 SELECT
							 CAST(LEFT(NOW(),4) AS CHAR) AS Y,
							 CAST(MID(NOW(),6,2) AS CHAR) AS M,
							 MID(NOW(),9,2) AS D,
							 COALESCE(MAX(CAST(RIGHT(id_toko,5) AS UNSIGNED)) + 1,1) AS ORD
							 FROM tb_toko
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
						
						 ) AS A
					 ) AS AA
					),
						'".$id_costumer."',
						'',
						'".$nama_toko."',
						'".$alamat."',
						'".$kec."',
						'".$kab."',
						'".$prov."',
						'".$kodepos."',
						'".$ket."',
						NOW());
			");
			
			if($query)
			{
				return true;
			} else 
			{
				return false;
			}
		}
		
		function update_profile($id_user,$nama_lengkap,$panggilan,$alamat,$hp,$email)
		{
			$query = $this->db->query("
						UPDATE tb_costumer
						SET nama_lengkap = '".$nama_lengkap."',
						  panggilan = '".$panggilan."',
						  alamat_rumah_sekarang = '".$alamat."',
						  hp = '".$hp."',
						  tgl_updt = NOW(),
						  email_costumer = '".$email."'
						WHERE id_costumer = '".$id_user."';
			");
			
			if($query)
			{
				return true;
			} else 
			{
				return false;
			}
		}
		
		function update_password($id_costumer,$username,$password)
		{
			$query = $this->db->query("
				UPDATE tb_costumer
				SET pass = '".$password."'
				WHERE id_costumer = '".$id_costumer."' AND username = '".$username."'
			");
			
			if($query)
			{
				return true;
			} else 
			{
				return false;
			}
		}
		
		function cek_user($id_costumer,$username,$pass)
		{
			$query = $this->db->query("
				SELECT * FROM tb_costumer
				WHERE id_costumer = '".$id_costumer."' AND username = '".$username."'
				AND pass = '".$pass."'
			");
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			} else {
				return false;
			}

		}
		
		function update_avatar($id_costumer,$avatar)
		{
			$query = $this->db->query("
				UPDATE tb_costumer
				SET avatar = '".$avatar."'
				WHERE id_costumer = '".$id_costumer."' 
			");
			
			if($query)
			{
				return true;
			} else 
			{
				return false;
			}
		}
		
		
		function list_alamat($id_costumer,$cari)
		{
			$query = $this->db->query("
				SELECT A.*,B.name AS prov,REPLACE(C.name,'KABUPATEN ','') AS kab,D.name AS kec 
				FROM tb_alamat A
				LEFT JOIN provinces B
				  ON A.provinsi = B.id
				LEFT JOIN regencies C
				  ON A.kabkota = C.id
				LEFT JOIN districts D
				  ON A.kecamatan = D.id
				WHERE id_costumer = '".$id_costumer."'
				AND nama_alamat like '%".$cari."%'
			");
			
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function simpan_alamat($id_costumer,$nama_alamat,$nama_penerima,$telepon,$negara,
							   $provinsi,$kabkota,$kec,$desa,$kodepos,$detail_alamat)
		{
			$query = $this->db->query("
				INSERT INTO tb_alamat
				(
							 id_alamat,
							 id_costumer,
							 nama_alamat,
							 nama_penerima,
							 telepon,
							 negara,
							 provinsi,
							 kabkota,
							 kecamatan,
							 desa,
							 kodepos,
							 detail_alamat,
							 tgl_ins,
							 kode_kantor)
				VALUES (
								(
							 SELECT CONCAT('AL',FRMTGL,ORD) AS id_alamat
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
									 COALESCE(MAX(CAST(RIGHT(id_alamat,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_alamat
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
								
								 ) AS A
							 ) AS AA
						 ),
						'".$id_costumer."',
						'".$nama_alamat."',
						'".$nama_penerima."',
						'".$telepon."',
						'".$negara."',
						'".$provinsi."',
						'".$kabkota."',
						'".$kec."',
						'".$desa."',
						'".$kodepos."',
						'".$detail_alamat."',
						NOW(),
						''
				 );
			");
		}
		
		function update_alamat($id_alamat,$nama_alamat,$nama_penerima,$telepon,$provinsi,$kabkota,$kec,$kodepos,$detail_alamat)
		{
			$query = $this->db->query("
				UPDATE tb_alamat
				SET nama_alamat = '".$nama_alamat."',
				  nama_penerima = '".$nama_penerima."',
				  telepon = '".$telepon."',
				  provinsi = '".$provinsi."',
				  kabkota = '".$kabkota."',
				  kecamatan = '".$kec."',
				  kodepos = '".$kodepos."',
				  detail_alamat = '".$detail_alamat."',
				  tgl_updt = NOW()
				WHERE id_alamat = '".$id_alamat."'

			");
		}
		
		function hapus_alamat($id_alamat)
		{
			$query = $this->db->query("DELETE
										FROM tb_alamat
										WHERE id_alamat = '".$id_alamat."'
										");
										
		}
		
		
		function load_prov()
		{
			$query = $this->db->query("
				SELECT * FROM provinces
				ORDER BY name
			");
			
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function load_kab($id_prov)
		{
			$query = $this->db->query("
				SELECT * FROM regencies
				WHERE province_id = '".$id_prov."'
				ORDER BY name
			");
			
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function load_kec($id_kab)
		{
			$query = $this->db->query("
				SELECT * FROM districts
				WHERE regency_id = '".$id_kab."'
				ORDER BY name
			");
			
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		
	}
?>