<?php
	class M_distribusi extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_mitra($cari)
		{
			$query = $this->db->query("SELECT * FROM tb_outlet A
										LEFT JOIN tb_kat_outlet B
										  ON A.id_kat_outlet = B.id_kat_outlet
										".$cari." LIMIT 0,10");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function get_mitra($id_distribusi)
		{
			$query = $this->db->query("SELECT A.id_outlet,B.kode_outlet,B.nama_outlet,B.ketua_outlet,B.alamat_outlet
										FROM tb_distribusi_order A
										LEFT JOIN tb_outlet B
										  ON A.id_outlet = B.id_outlet
										LEFT JOIN tb_kat_outlet C
										  ON B.id_kat_outlet = C.id_kat_outlet
										WHERE A.id_distribusi = '".$id_distribusi."'
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
		
		
		function list_produk_po($no_faktur)
		{
			$query = $this->db->query("SELECT B.id_d_penjualan,A.id_h_penjualan,B.id_produk,C.kode_produk,C.nama_produk,
											   B.jumlah,B.satuan_jual,COALESCE(id_distribusi,'0') AS stat
										FROM tb_h_penjualan A
										LEFT JOIN tb_d_penjualan B
										  ON A.id_h_penjualan = B.id_h_penjualan
										LEFT JOIN tb_produk C
										  ON B.id_produk = C.id_produk
										LEFT JOIN tb_distribusi_order D
										  ON A.id_h_penjualan = D.id_h_penjualan AND B.id_d_penjualan = D.id_d_penjualan
										WHERE A.no_faktur = '".$no_faktur."'");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function simpan_distribusi($id_h_penjualan,$id_d_penjualan,$id_outlet,$id_produk,$user,$kode_kantor)
		{
			$query = $this->db->query("INSERT INTO tb_distribusi_order
													(id_distribusi,
													 id_h_penjualan,
													 id_d_penjualan,
													 id_outlet,
													 id_produk,
													 tgl_ins,
													 user_updt,
													 kode_kantor
													 )
										VALUES (
											(
											SELECT CONCAT('DO',FRMTGL,ORD) AS id_distribusi
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
													 COALESCE(MAX(CAST(RIGHT(id_distribusi,5) AS UNSIGNED)) + 1,1) AS ORD
													 FROM tb_distribusi_order
													 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
													 -- AND kode_kantor = ''
												 ) AS A
											 ) AS AA
											),
												'".$id_h_penjualan."',
												'".$id_d_penjualan."',
												'".$id_outlet."',
												'".$id_produk."',
												NOW(),
												'".$user."',
												'".$kode_kantor."'
												);
		");
			
			
			return $query;
		}
	}
?>