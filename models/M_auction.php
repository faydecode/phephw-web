<?php

	Class M_auction Extends CI_Model
	{
		function __construct()
		{
			parent::__construct();
		}
		
		function list_auction_limit($cari,$offset,$limit)
		{
			$query = " 
						SELECT A.id_auction,A.id_produk,A.id_member,A.kode_auction,A.nama_auction,A.date_mulai,A.date_sampai,A.harga_mulai,A.ket_auction 
							,COALESCE(B.kode_produk,'') AS kode_produk
							,COALESCE(B.nama_produk,'') AS nama_produk
							,COALESCE(B.tag_produk,'') AS tag_produk
							,COALESCE(C.no_costumer,'') AS no_costumer
							,COALESCE(C.nama_lengkap,'') AS nama_lengkap
							,COALESCE(MAX(D.img_file),'') AS IMG_FILE
							,COALESCE(MAX(D.img_url),'') AS IMG_URL
						FROM tb_auction AS A
						LEFT JOIN tb_produk AS B ON A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
						LEFT JOIN tb_costumer AS C ON A.id_member = C.id_costumer
						LEFT JOIN tb_images AS D ON A.id_produk = D.id AND D.group_by = 'produks' AND A.kode_kantor = D.kode_kantor
						".$cari."
						GROUP BY
							A.id_auction,A.id_produk,A.id_member,A.kode_auction,A.nama_auction,A.date_mulai,A.date_sampai,A.harga_mulai,A.ket_auction 
							,COALESCE(B.kode_produk,'')
							,COALESCE(B.nama_produk,'')
							,COALESCE(C.no_costumer,'')
							,COALESCE(C.nama_lengkap,'')  
						ORDER BY A.tgl_ins DESC LIMIT ".$offset.",".$limit.";"
					;
					
			$query = $this->db->query($query);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function count_auction_limit($cari)
		{
			$query = " SELECT COALESCE(COUNT(A.id_auction),0) AS JUMLAH 
						FROM tb_auction AS A
						LEFT JOIN tb_produk AS B ON A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
						LEFT JOIN tb_costumer AS C ON A.id_member = C.id_costumer
						LEFT JOIN tb_images AS D ON A.id_produk = D.id AND D.group_by = 'produks' AND A.kode_kantor = D.kode_kantor ".$cari." ;"
						;
						
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
		
		function simpan(
			$id_produk,
			$id_member,
			$kode_auction,
			$nama_auction,
			$date_mulai,
			$date_sampai,
			$harga_mulai,
			$ket_auction,
			$verified,
			$user_ins,
			$user_updt,
			$kode_kantor
		)
		{
			$query = "
						INSERT INTO tb_auction
						(
							id_auction,
							id_produk,
							id_member,
							kode_auction,
							nama_auction,
							date_mulai,
							date_sampai,
							harga_mulai,
							ket_auction,
							verified,
							tgl_ins,
							tgl_updt,
							user_ins,
							user_updt,
							kode_kantor
						)
						VALUES
						(
							(
								 SELECT CONCAT('AC',FRMTGL,ORD) AS id_auction
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
										 COALESCE(MAX(CAST(RIGHT(id_auction,5) AS UNSIGNED)) + 1,1) AS ORD
										 From tb_auction
										 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
										 -- AND kode_kantor = ''
									 ) AS A
								 ) AS AA
							),
							'".$id_produk."',
							'".$id_member."',
							'".$kode_auction."',
							'".$nama_auction."',
							'".$date_mulai."',
							'".$date_sampai."',
							'".$harga_mulai."',
							'".$ket_auction."',
							'".$verified."',
							NOW(),
							NOW(),
							'".$user_ins."',
							'".$user_updt."',
							'".$kode_kantor."'
						)
			";

			$this->db->query($query);
		}
		
		function edit(
			$id_auction,
			$id_produk,
			$id_member,
			$kode_auction,
			$nama_auction,
			$date_mulai,
			$date_sampai,
			$harga_mulai,
			$ket_auction,
			$verified,
			$user_updt,
			$kode_kantor
		)
		{
			$query = "
				UPDATE tb_auction
				SET id_produk = '".$id_produk."',
				  id_member = '".$id_member."',
				  kode_auction = '".$kode_auction."',
				  nama_auction = '".$nama_auction."',
				  date_mulai = '".$date_mulai."',
				  date_sampai = '".$date_sampai."',
				  harga_mulai = '".$harga_mulai."',
				  ket_auction = '".$ket_auction."',
				  verified = '".$verified."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."'
				WHERE id_auction = '".$id_auction."'
				AND kode_kantor = '".$kode_kantor."';
			";
			 
			 
			$this->db->query($query);
		}
	}

?>