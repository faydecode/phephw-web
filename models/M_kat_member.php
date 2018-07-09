<?php
	class M_kat_member extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kat_member()
		{
			$query = $this->db->query("SELECT * FROM tb_kat_costumer 
									   WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' 
									   ORDER BY nama_kat_costumer ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_kat_member_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_kat_costumer ".$cari." ORDER BY nama_kat_costumer ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT A.id_kat_costumer,A.nama_kat_costumer,A.ket_kat_costumer FROM tb_kat_costumer AS A
									  ".$cari." ORDER BY nama_kat_costumer ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_kat_member_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_kat_costumer) AS JUMLAH FROM tb_kat_costumer ".$cari);
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
				INSERT INTO tb_kat_costumer
				(
							id_kat_costumer,
							 set_default,
							 nama_kat_costumer,
							 STATUS,
							 optr_harga,
							 harga,
							 hirarki_harga,
							 ket_kat_costumer,
							 tgl_ins,
							 user_updt,
							 kode_kantor
				)
				VALUES (
					(
					 SELECT CONCAT('KM',FRMTGL,ORD) AS id_kat_costumer
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
							 COALESCE(MAX(CAST(RIGHT(id_kat_costumer,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_kat_costumer
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
						'1',
						'".$nama."',
						'1',
						'',
						'0',
						'0',
						'".$ket."',
						now(),
						'".$id_user."',
						'".$kode_kantor."'
				);
			";
			
			$this->db->query($query);
			
		}
		
		function edit($id,$nama,$ket,$id_user)
		{
			$query = "
				UPDATE tb_kat_costumer
				SET nama_kat_costumer = '".$nama."',
				  ket_kat_costumer = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$id_user."'
				WHERE id_kat_costumer = '".$id."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_kat_costumer WHERE id_kat_costumer = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_kat_member_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_costumer', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_kat_member($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_costumer', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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