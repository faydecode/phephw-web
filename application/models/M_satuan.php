<?php
	class M_satuan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_satuan()
		{
			$query = $this->db->query("SELECT * FROM tb_satuan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'
									  ORDER BY nama_satuan ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_satuan_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_satuan ".$cari." ORDER BY nama_satuan ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT A.id_satuan,A.kode_satuan,A.nama_satuan,A.ket_satuan FROM tb_satuan AS A
									  ".$cari." ORDER BY nama_satuan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_satuan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_satuan) AS JUMLAH FROM tb_satuan ".$cari);
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
			$query = $this->db->query("SELECT '1' as DIVI,B.id_produk,A.id_satuan,nama_satuan,kode_satuan
										FROM tb_satuan A
										LEFT JOIN tb_produk B
											ON A.id_satuan = B.id_satuan
										WHERE B.id_produk = '".$id."'

										UNION ALL

										SELECT '2' as DIVI,A.id_produk,A.id_satuan,nama_satuan,kode_satuan
										FROM tb_satuan_konversi A
										LEFT JOIN tb_produk B
											ON A.id_produk = B.id_produk
										LEFT JOIN tb_satuan C
											ON A.id_satuan = C.id_satuan
										WHERE A.id_produk = '".$id."'
										ORDER BY DIVI
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
		  
		function simpan($kode,$nama,$ket,$id_user,$kode_kantor)
		{
			/* $data = array
			(
			   'kode_satuan' => $kode,
			   'nama_satuan' => $nama,
			   'ket_satuan' => $ket,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			); */
			
			$query = "INSERT INTO tb_satuan
					(
						id_satuan,
						kode_satuan,
						nama_satuan,
						ket_satuan,
						tgl_ins,
						kode_kantor,
						user_updt
					)
					VALUES (
						(
					 SELECT CONCAT('KS',FRMTGL,ORD) AS id_satuan
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
							 COALESCE(MAX(CAST(RIGHT(id_satuan,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_satuan
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
						'".$kode."',
						'".$nama."',
						'".$ket."',
						now(),
						'".$kode_kantor."',
						'".$id_user."'
					);
			";

			$this->db->query($query);
		}
		
		function edit($id,$kode,$nama,$ket,$id_user,$kode_kantor)
		{
			/* $data = array
			(
   			   'kode_satuan' => $nama,
			   'nama_satuan' => $nama,
			   'ket_satuan' => $ket,
			   'user_updt' => $id_user
			);
			 */
			$query = "
				UPDATE tb_satuan
				SET kode_satuan = '".$kode."',
				  nama_satuan = '".$nama."',
				  ket_satuan = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$id_user."'
				WHERE id_satuan = '".$id."'
					AND kode_kantor = '".$kode_kantor."';
			";
			 
			 
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_satuan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND id_satuan = '".$id."'");
			//$this->db->where('id_satuan',$id);
			//$this->db->delete('tb_satuan');
		}
		
		function get_satuan_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_satuan', array($berdasarkan => $cari,'kode_kantor'=>$this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		 
		function get_satuan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_satuan', array($berdasarkan => $cari));
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