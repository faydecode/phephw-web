<?php
	class M_public_get extends CI_Model 
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function get_satuan_harga($id_produk,$kode_satuan)
		{
			$query = $this->db->query(" SELECT HARGA_DEFT FROM
										 (
											 SELECT A.id_produk, A.id_satuan AS STN_DEFT, B.harga AS HARGA_DEFT
											 FROM tb_produk AS A
											 LEFT JOIN tb_harga AS B ON A.id_produk = B.id AND A.kode_kantor = B.kode_kantor AND B.set_default = 1 AND B.group_by = 'produks'
											 WHERE A.id_produk = '".$id_produk."'
											 UNION ALL
											 SELECT C.id_produk,C.id_satuan,C.harga_jual FROM tb_satuan_konversi AS C 
											 WHERE  C.id_produk = '".$id_produk."' AND C.id_satuan <> ''
										) AS A
									LEFT JOIN tb_satuan AS B ON A.STN_DEFT  = B.id_satuan
									WHERE B.kode_satuan = '".$kode_satuan."'");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_list_admin()
		{
			$query = $this->db->query("
					SELECT * FROM tb_akun A
					LEFT JOIN tb_karyawan B
					  ON A.id_karyawan = B.id_karyawan
					LEFT JOIN tb_jabatan C
					  ON B.id_jabatan = C.id_jabatan
					WHERE nama_jabatan = 'Admin Aplikasi'
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
		
		function get_login_costumer($user,$pass,$tipe)
		{
			$query = $this->db->query("SELECT *,A.status as stat_member FROM tb_costumer A
									LEFT JOIN tb_kat_costumer B
									  ON A.id_kat_costumer = B.id_kat_costumer
									WHERE username = '".$user."' AND pass = '".$pass."'
										  AND A.id_kat_costumer = '".$tipe."'
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
		
		function get_date($tgl,$range)
		{
			$query = $this->db->query("SELECT DATE_FORMAT(NOW()-INTERVAL '{$range}' DAY,'%d-%m-%Y') AS tgl");
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
	}
?>