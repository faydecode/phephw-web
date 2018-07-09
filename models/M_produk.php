<?php
	class M_produk extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_produk_id($id_produk)
		{
			$query = $this->db->get_where('tb_produk', array('id_produk' => $id_produk, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}

		
		function list_produk_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_produk AS A
										LEFT JOIN tb_kat_produk AS B ON A.id_kat_produk = B.id_kat_produk AND A.kode_kantor = B.kode_kantor
										LEFT JOIN tb_satuan AS C ON A.id_satuan = C.id_satuan AND A.kode_kantor = C.kode_kantor
										".$cari." ORDER BY nama_produk ASC LIMIT ".$offset.",".$limit);
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_produk_harga_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.*,
											   COALESCE(D.harga,0) harga
										FROM tb_produk AS A
										LEFT JOIN tb_kat_produk AS B ON A.id_kat_produk = B.id_kat_produk AND A.kode_kantor = B.kode_kantor
										LEFT JOIN tb_harga D ON A.id_produk = D.id AND A.kode_kantor = D.kode_kantor AND D.group_by = 'produks'
										".$cari." ORDER BY nama_produk ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function count_produk_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_produk) AS JUMLAH FROM tb_produk A ".$cari);
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_produk_limit_d_pembelian($id_d_pembelian,$cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT 
										A.id_produk
										,A.id_kat_produk
										,A.kode_produk
										,A.nama_produk
										,A.spek_produk
										,A.ket_produk
										,A.tgl_ins
										,A.tgl_updt
										,A.user_updt
										,A.kode_kantor
										,B.nama_kat_produk
										,B.ket_kat_produk
										,COALESCE(C.harga,0) AS harga

										FROM tb_produk AS A 
										LEFT JOIN tb_kat_produk AS B 
										ON A.id_kat_produk = B.id_kat_produk 
										LEFT JOIN
										(
											SELECT id, harga FROM tb_harga WHERE group_by = 'produks' AND set_default = 1
										) AS C
										ON A.id_produk = C.id
										LEFT JOIN
										(
											SELECT id_produk,COUNT(id_d_pembelian) AS JUMLAH FROM tb_d_pembelian WHERE id_h_pembelian = '".$id_d_pembelian."' GROUP BY id_produk
										) AS D
										ON A.id_produk = D.id_produk
										-- WHERE D.id_produk IS NULL ".$cari."
										WHERE A.id_produk LIKE '%%'  ".$cari."
										ORDER BY A.tgl_ins DESC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id_kat_produk
			,$id_satuan
			,$kode_produk
			,$nama_produk
			,$charge
			,$op_charge
			,$charge_beli
			,$op_beli
			,$min_stok
			,$max_stok
			,$spek_produk
			,$keterangan
			,$tag
			,$kode_kantor
			,$user_updt
		)
		{
			$query = "
					INSERT INTO tb_produk
					(
							 id_produk,
							 id_kat_produk,
							 id_satuan,
							 kode_produk,
							 nama_produk,
							 charge,
							 optr_charge,
							 charge_beli,
							 optr_charge_beli,
							 min_stock,
							 max_stock,
							 spek_produk,
							 tag_produk,
							 ket_produk,
							 tgl_ins,
							 user_updt,
							 kode_kantor
					)
					VALUES ( (
					 SELECT CONCAT('KS',FRMTGL,ORD) AS id_produk
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
							 COALESCE(MAX(CAST(RIGHT(id_produk,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_produk
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 -- AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
							'".$id_kat_produk."',
							'".$id_satuan."',
							'".$kode_produk."',
							'".$nama_produk."',
							'".$charge."',
							'".$op_charge."',
							'".$charge_beli."',
							'".$op_beli."',
							'".$min_stok."',
							'".$max_stok."',
							'".$spek_produk."',
							'".$tag."',
							'".$keterangan."',
							now(),
							'".$user_updt."',
							'".$kode_kantor."'
					 );
			";
			
			$this->db->query($query);
			
		}
		
		function edit_no_image
		(
			$id_produk
			,$id_kat_produk
			,$id_satuan
			,$kode_produk
			,$nama_produk
			,$charge
			,$op_charge
			,$charge_beli
			,$op_beli
			,$min_stok
			,$max_stok
			,$spek_produk
			,$keterangan
			,$tag
			,$user_updt
		)
		{
			$query = "
				UPDATE tb_produk
				SET id_kat_produk = '".$id_kat_produk."',
				  id_satuan = '".$id_satuan."',
				  kode_produk = '".$kode_produk."',
				  nama_produk = '".$nama_produk."',
				  charge = '".$charge."',
				  optr_charge = '".$op_charge."',
				  charge_beli = '".$charge_beli."',
				  optr_charge_beli = '".$op_beli."',
				  min_stock = '".$min_stok."',
				  max_stock = '".$max_stok."',
				  spek_produk = '".$spek_produk."',
				  tag_produk = '".$tag."',
				  ket_produk = '".$keterangan."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."'
				WHERE id_produk = '".$id_produk."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_produk WHERE id_produk = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_produk($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_produk', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_kat_produk_by($id_produk)
		{
			$query = $this->db->query("
								SELECT id_kat_produk as id_kat_produk FROM tb_produk
								WHERE id_produk = '".$id_produk."' 
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
	}
?>