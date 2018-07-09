<?php
	class M_get_produk extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_detail_diskon_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("SELECT * FROM tb_detail_diskon A
										LEFT JOIN tb_produk B
											ON A.id_produk = B.id_produk AND A.kode_kantor = B.kode_kantor
										".$cari." ORDER BY nama_d_diskon ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_detail_diskon_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_d_diskon) AS JUMLAH FROM tb_detail_diskon A ".$cari);
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
		  
		function simpan($id_h_diskon,$id_produk,$satuan,$nama,$nominal,$ket,$user,$kode_kantor)
		{
			$query = "
					INSERT INTO tb_detail_diskon
					(
								id_d_diskon,
								 id_h_diskon,
								 id_produk,
								 satuan,
								 nama_d_diskon,
								 nominal,
								 ket_d_diskon,
								 tgl_ins,
								 user_updt,
								 kode_kantor
					)
					VALUES 
					(
						(
						 SELECT CONCAT('DD',FRMTGL,ORD) AS id_d_diskon
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
								 COALESCE(MAX(CAST(RIGHT(id_d_diskon,5) AS UNSIGNED)) + 1,1) AS ORD
								 FROM tb_detail_diskon
								 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
								 AND kode_kantor = '".$kode_kantor."'
							 ) AS A
						 ) AS AA
						),
							'".$id_h_diskon."',
							'".$id_produk."',
							'".$satuan."',
							'".$nama."',
							'".$nominal."',
							'".$ket."',
							now(),
							'".$user."',
							'".$kode_kantor."'
					 );
			";
			
			$this->db->query($query);
		}
		
		function edit($id_d_diskon,$id_h_diskon,$id_produk,$satuan,$nama,$nominal,$ket,$user,$kode_kantor)
		{
			
			$query = "
				UPDATE tb_detail_diskon
				SET id_produk = '".$id_produk."',
				  satuan = '".$satuan."',
				  nama_d_diskon = '".$nama."',
				  nominal = '".$nominal."',
				  ket_d_diskon = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$user."'
				WHERE id_d_diskon = '".$id_d_diskon."'
					AND kode_kantor = '".$kode_kantor."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_detail_diskon WHERE id_d_diskon = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_detail_diskon_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_detail_diskon', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		 
		function get_detail_diskon($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_detail_diskon', array($berdasarkan => $cari));
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