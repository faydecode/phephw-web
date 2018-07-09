<?php
	class M_diskon_produk extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_diskon()
		{
			$query = $this->db->query("SELECT * FROM tb_header_diskon A
										LEFT JOIN tb_produk B 
											ON A.id = B.id_produk AND A.kode_kantor = B.kode_kantor
										LEFT JOIN tb_kat_costumer C 
											ON A.id_kat_costumer = C.id_kat_costumer AND A.kode_kantor = C.kode_kantor
										ORDER BY nama_diskon ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_diskon_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_header_diskon A
										LEFT JOIN tb_produk B 
											ON A.id = B.id_produk AND A.kode_kantor = B.kode_kantor
										LEFT JOIN tb_kat_costumer C 
											ON A.id_kat_costumer = C.id_kat_costumer AND A.kode_kantor = C.kode_kantor
									  ".$cari." ORDER BY nama_diskon ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_diskon_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_h_diskon) AS JUMLAH FROM tb_header_diskon A ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_satuan_by_produk($id)
		{
			$query = $this->db->query("SELECT B.id_produk,A.id_satuan,nama_satuan,kode_satuan
										FROM tb_satuan A
										LEFT JOIN tb_produk B
											ON A.id_satuan = B.id_satuan
										WHERE B.id_produk = '".$id."'

										UNION ALL

										SELECT A.id_produk,A.id_satuan,nama_satuan,kode_satuan
										FROM tb_satuan_konversi A
										LEFT JOIN tb_produk B
											ON A.id_produk = B.id_produk
										LEFT JOIN tb_satuan C
											ON A.id_satuan = C.id_satuan
										WHERE A.id_produk = '".$id."'");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		  
		function simpan($nama,$id_produk,$id_kat_costumer,$optr_diskon,$satuan,$satuan_diskon,$besar_diskon,
						$besar_pembelian,$kondisi,$tgl_dari,$tgl_sampai,$ket,$user,$kode_kantor)
		{
			$query = "
					INSERT INTO tb_header_diskon
								(id_h_diskon,
								 id_kat_costumer,
								 id,
								 group_by,
								 nama_diskon,
								 besar_diskon,
								 optr_diskon,
								 ket_diskon,
								 dari,
								 sampai,
								 satuan,
								 satuan_diskon,
								 optr_kondisi,
								 besar_pembelian,
								 set_default,
								 tgl_ins,
								 user_updt,
								 kode_kantor)
					VALUES (
						(
						 SELECT CONCAT('DP',FRMTGL,ORD) AS id_h_diskon
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
								 COALESCE(MAX(CAST(RIGHT(id_h_diskon,5) AS UNSIGNED)) + 1,1) AS ORD
								 From tb_header_diskon
								 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
								 AND kode_kantor = '".$kode_kantor."'
							 ) AS A
						 ) AS AA
						),
							'".$id_kat_costumer."',
							'".$id_produk."',
							'produks',
							'".$nama."',
							'".$besar_diskon."',
							'".$optr_diskon."',
							'".$ket."',
							'".$tgl_dari."',
							'".$tgl_sampai."',
							'".$satuan."',
							'".$satuan_diskon."',
							'".$kondisi."',
							'".$besar_pembelian."',
							'0',
							now(),
							'".$user."',
							'".$kode_kantor."'
					 );
			";
			
			$this->db->query($query);
		}
		
		function edit($id_diskon,$nama,$id_produk,$id_kat_costumer,$optr_diskon,$satuan,$satuan_diskon,$besar_diskon,
					  $besar_pembelian,$kondisi,$tgl_dari,$tgl_sampai,$ket,$user,$kode_kantor)
		{
			
			$query = "
				UPDATE tb_header_diskon
				SET id_kat_costumer = '".$id_kat_costumer."',
				  id = '".$id_produk."',
				  nama_diskon = '".$nama."',
				  besar_diskon = '".$besar_diskon."',
				  optr_diskon = '".$optr_diskon."',
				  ket_diskon = '".$ket."',
				  dari = '".$tgl_dari."',
				  sampai = '".$tgl_sampai."',
				  satuan = '".$satuan."',
				  satuan_diskon = '".$satuan_diskon."',
				  optr_kondisi = '".$kondisi."',
				  besar_pembelian = '".$besar_pembelian."',
				  tgl_updt = now(),
				  user_updt = '".$user."'
				WHERE id_h_diskon = '".$id_diskon."'
					AND kode_kantor = '".$kode_kantor."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			//$this->db->query('DELETE FROM tb_diskon WHERE id_diskon = '.$id.'');
			$this->db->where('id_h_diskon',$id);
			$this->db->delete('tb_header_diskon');
		}
		
		function get_diskon_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_header_diskon', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		 
		function get_diskon($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_header_diskon', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        } 
	}
?>