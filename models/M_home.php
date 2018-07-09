<?php
	class M_home extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kategori()
		{
			$query = $this->db->query("SELECT '1' AS DIVI, 'all' AS id_kat_produk,'ALL' AS nama_kat_produk,COUNT(B.id_produk) AS JUMLAH,'all' AS LINK_KAT
										FROM tb_kat_produk A
										LEFT JOIN tb_produk B
										   ON A.id_kat_produk = B.id_kat_produk
										UNION ALL
										SELECT '2' AS DIVI, A.id_kat_produk,A.nama_kat_produk,COUNT(B.id_produk) JUMLAH,
											   LOWER(REPLACE(nama_kat_produk,' ','-')) AS LINK_KAT
										FROM tb_kat_produk A
										LEFT JOIN tb_produk B
											ON A.id_kat_produk = B.id_kat_produk
											-- AND A.kode_kantor = B.kode_kantor
										GROUP BY A.id_kat_produk,A.nama_kat_produk
										ORDER BY DIVI,nama_kat_produk ASC
										");
										//WHERE A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function get_kat_produk_by($kode_produk)
		{
			$query = $this->db->query("
								SELECT LOWER(REPLACE(nama_kat_produk,' ','-')) AS nama_kat_produk 
								FROM tb_produk A
								LEFT JOIN tb_kat_produk B
								   ON A.id_kat_produk = B.id_kat_produk
								WHERE A.kode_produk = '".$kode_produk."'
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
		
		function list_produk($categori,$type_produk,$offset,$limit)
		{
			$query = $this->db->query("SELECT 
								A.id_produk
								,A.id_kat_produk
								,A.id_satuan
								,A.kode_produk
								,A.nama_produk
								,E.nama_toko AS nama_supplier
								,A.tag_produk
								,MAX(COALESCE(D.img_file,'noimage.png')) AS avatar
								-- ,D.img_url AS avatar_url
								,COALESCE(B.harga,0) AS harga
								,C.kode_satuan
							FROM tb_produk A
							LEFT JOIN tb_harga B
								ON A.id_produk = B.id
								-- AND A.kode_kantor = B.kode_kantor
							LEFT JOIN tb_satuan C
								ON A.id_satuan = C.id_satuan
								-- AND A.kode_kantor = C.kode_kantor
							LEFT JOIN tb_images D
								ON A.id_produk = D.id
								 AND A.kode_kantor = D.kode_kantor
							LEFT JOIN tb_toko E
								ON A.kode_kantor = E.id_costumer
							LEFT JOIN tb_kat_produk F
								ON A.id_kat_produk = F.id_kat_produk
							WHERE type_produk LIKE '%".$type_produk."%'
								 AND LOWER(REPLACE(F.nama_kat_produk,' ','-')) LIKE '%".$categori."%' 
							GROUP BY A.id_produk,A.id_kat_produk,A.id_satuan,A.kode_produk,A.nama_produk,COALESCE(B.harga,0),C.kode_satuan,nama_supplier,A.tag_produk
							LIMIT ".$offset.",".$limit);
									   //AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function list_hot_deal($offset,$limit)
		{
			$query = $this->db->query("SELECT 
								A.id_produk
								,A.id_kat_produk
								,A.id_satuan
								,A.kode_produk
								,A.nama_produk
								,F.nama_toko AS nama_supplier
								,A.tag_produk
								,MAX(COALESCE(D.img_file,'noimage.png')) AS avatar
								-- ,D.img_url AS avatar_url
								,COALESCE(B.harga,0) AS harga
								,C.kode_satuan
							FROM tb_produk A
							LEFT JOIN tb_harga B
								ON A.id_produk = B.id
								-- AND A.kode_kantor = B.kode_kantor
							LEFT JOIN tb_satuan C
								ON A.id_satuan = C.id_satuan
								-- AND A.kode_kantor = C.kode_kantor
							LEFT JOIN tb_images D
								ON A.id_produk = D.id
								-- AND A.kode_kantor = D.kode_kantor
							LEFT JOIN tb_hot_deal E
								ON A.id_produk = E.id_produk
							LEFT JOIN tb_toko F
								ON A.kode_kantor = F.id_costumer
							WHERE E.set_aktif = '1' 
							GROUP BY A.id_produk,A.id_kat_produk,A.id_satuan,A.kode_produk,A.nama_produk,COALESCE(B.harga,0),C.kode_satuan,nama_supplier,A.tag_produk
							LIMIT ".$offset.",".$limit);
									   //AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			if($query->num_rows() > 0)
			{
				return $query;
			} else {
				return false;
			}
		}
		
		function detail_produk($kode)
		{
			$query = $this->db->query("
				SELECT A.id_produk,
					   A.id_kat_produk,
					   A.nama_produk,
					   B.nama_kat_produk,
					   A.spek_produk,
					   A.ket_produk,
					   F.nama_toko nama_supplier,
					   A.tag_produk,
					   C.kode_satuan,
					   A.type_produk,
					   C.id_satuan,
					   COALESCE(D.harga,0) AS harga,
					   MAX(COALESCE(E.img_file,'noimage.png')) AS img_file
				FROM tb_produk A
				LEFT JOIN tb_kat_produk B 
				  ON A.id_kat_produk = B.id_kat_produk
				  -- AND A.kode_kantor = B.kode_kantor
				LEFT JOIN tb_satuan C
				  ON A.id_satuan = C.id_satuan
				  -- AND A.kode_kantor = C.kode_kantor
				LEFT JOIN tb_harga D
				  ON A.id_produk = D.id
				  -- AND A.kode_kantor = D.kode_kantor
				 LEFT JOIN tb_images E
				  ON A.id_produk = E.id
				     AND A.kode_kantor = E.kode_kantor
				 LEFT JOIN tb_toko F
					ON A.kode_kantor = F.id_costumer
				WHERE A.kode_produk = '".$kode."'
				-- WHERE A.id_produk = '".$kode."'				
				GROUP BY A.id_produk,
					   A.id_kat_produk,
					   A.nama_produk,
					   A.spek_produk,
					   B.nama_kat_produk,
					   A.ket_produk,
					   A.nama_supplier,
					   A.tag_produk,
					   COALESCE(D.harga,0)
				LIMIT 1
			");
			//AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function detail_produk_pakai_id($id)
		{
			$query = $this->db->query("
				SELECT A.id_produk,
					   A.id_kat_produk,
					   A.nama_produk,
					   B.nama_kat_produk,
					   A.spek_produk,
					   A.ket_produk,
					   F.nama_toko nama_supplier,
					   A.tag_produk,
					   C.kode_satuan,
					   A.type_produk,
					   C.id_satuan,
					   COALESCE(D.harga,0) AS harga,
					   MAX(COALESCE(E.img_file,'noimage.png')) AS img_file
				FROM tb_produk A
				LEFT JOIN tb_kat_produk B 
				  ON A.id_kat_produk = B.id_kat_produk
				  -- AND A.kode_kantor = B.kode_kantor
				LEFT JOIN tb_satuan C
				  ON A.id_satuan = C.id_satuan
				  -- AND A.kode_kantor = C.kode_kantor
				LEFT JOIN tb_harga D
				  ON A.id_produk = D.id
				  -- AND A.kode_kantor = D.kode_kantor
				 LEFT JOIN tb_images E
				  ON A.id_produk = E.id
				     AND A.kode_kantor = E.kode_kantor
				 LEFT JOIN tb_toko F
					ON A.kode_kantor = F.id_costumer
				WHERE A.id_produk = '".$id."'				
				GROUP BY A.id_produk,
					   A.id_kat_produk,
					   A.nama_produk,
					   A.spek_produk,
					   B.nama_kat_produk,
					   A.ket_produk,
					   A.nama_supplier,
					   A.tag_produk,
					   COALESCE(D.harga,0)
				LIMIT 1
			");
			//AND A.kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_detail_auction($cari)
		{
			$query = "SELECT A.*
						,COALESCE(B.nama_lengkap,'') AS nama_lengkap
						,COALESCE(B.avatar,'') AS avatar
						,COALESCE(B.avatar_url,'') AS avatar_url
					FROM tb_d_auction AS A
					LEFT JOIN tb_costumer AS B ON A.id_member = B.id_costumer
					".$cari."
					ORDER BY A.nominal DESC, A.tgl_ins DESC
					;";
					
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
		
		function detail_images_produk($id)
		{
			$query = $this->db->query("
				SELECT * FROM tb_images A
				LEFT JOIN tb_produk B
				   ON A.id = B.id_produk AND A.kode_kantor = B.kode_kantor
				WHERE B.kode_produk = '".$id."'
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
		
		function hapus_bid($id_d_auction)
		{
			$query = "DELETE FROM tb_d_auction WHERE id_d_auction = '".$id_d_auction."'";
			$this->db->query($query);
		}
		
		function simpan_d_auction(
			$id_auction,
			$id_member,
			$id_produk,
			$nominal,
			$pesan,
			$user_ins,
			$user_updt
		)
		{
			$query = "
						INSERT INTO tb_d_auction
						(
							id_d_auction,
							id_auction,
							id_member,
							id_produk,
							nominal,
							pesan,
							tgl_ins,
							tgl_updt,
							user_ins,
							user_updt
						)
						VALUES
						(
							(
								 SELECT CONCAT('DAC',FRMTGL,ORD) AS id_d_auction
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
										 COALESCE(MAX(CAST(RIGHT(id_d_auction,5) AS UNSIGNED)) + 1,1) AS ORD
										 From tb_d_auction
										 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
										 -- AND kode_kantor = ''
									 ) AS A
								 ) AS AA
							),
							'".$id_auction."',
							'".$id_member."',
							'".$id_produk."',
							'".$nominal."',
							'".$pesan."',
							NOW(),
							NOW(),
							'".$user_ins."',
							'".$user_updt."'
						)
			";

			$this->db->query($query);
		}
		
	
	}
?>