<?php
	class M_kat_outlet extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kat_outlet()
		{
			$query = $this->db->query("SELECT * FROM tb_kat_outlet 
										 WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
										ORDER BY nama_kat_outlet ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_kat_outlet_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_kat_outlet ".$cari." ORDER BY nama_kat_outlet ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT A.id_kat_outlet,A.nama_kat_outlet,A.ket_kat_outlet FROM tb_kat_outlet AS A
									  ".$cari." ORDER BY nama_kat_outlet ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_kat_outlet_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_kat_outlet) AS JUMLAH FROM tb_kat_outlet ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan($nama,$ket,$id_user,$kode_kantor)
		{
			$query = "
					INSERT INTO tb_kat_outlet
					(
								id_kat_outlet,
								 kode_kat_outlet,
								 nama_kat_outlet,
								 ket_kat_outlet,
								 tgl_ins,
								 kode_kantor,
								 user_updt
					)
					VALUES (
						(
					 SELECT CONCAT('KO',FRMTGL,ORD) AS id_kat_outlet
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
							 COALESCE(MAX(CAST(RIGHT(id_kat_outlet,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_kat_outlet
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
							'',
							'".$nama."',
							'".$ket."',
							now(),
							'".$kode_kantor."',
							'".$id_user."'
					 );
			";
			
			
			
			$this->db->query($query);
		}
		
		function edit($id,$nama,$ket,$id_user)
		{
			$query = "
				UPDATE tb_kat_outlet
				SET nama_kat_outlet = '".$nama."',
				  ket_kat_outlet = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$id_user."'
				WHERE id_kat_outlet = '".$id."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_kat_outlet WHERE id_kat_outlet = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_kat_outlet_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_outlet', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor') ));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_kat_outlet($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_outlet', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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