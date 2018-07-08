<?php
	class M_kat_supplier extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_kat_supplier()
		{
			$query = $this->db->query("SELECT * FROM tb_kat_supplier WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_kat_supplier ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_kat_supplier_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_kat_supplier ".$cari." ORDER BY nama_kat_supplier ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("
									SELECT 	A.id_kat_supplier,A.nama_kat_supplier,A.ket_kat_supplier
									,A.tgl_ins,A.tgl_updt,A.user_updt,A.kode_kantor 
									FROM tb_kat_supplier AS A
									".$cari." ORDER BY nama_kat_supplier ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_kat_supplier_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_kat_supplier) AS JUMLAH FROM tb_kat_supplier ".$cari);
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
			/*$data = array
			(
			   'nama_kat_supplier' => $nama,
			   'ket_kat_supplier' => $ket,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_kat_supplier', $data);*/
			
			$query = "INSERT INTO tb_kat_supplier
			 (
				 id_kat_supplier
				 ,nama_kat_supplier
				 ,ket_kat_supplier
				 ,tgl_ins
				 ,kode_kantor
				 ,user_updt
			 )
			 Values
			 (
				 (
					 SELECT CONCAT('KS',FRMTGL,ORD) AS id_kat_supplier
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
							 COALESCE(MAX(CAST(RIGHT(id_kat_supplier,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_kat_supplier
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 )
				 ,'".$nama."'
				 ,'".$ket."'
				 ,NOW()
				 ,'".$kode_kantor."'
				 ,'".$id_user."'
			 )";
			$this->db->query($query);
		}
		
		function edit($id,$nama,$ket,$id_user)
		{
			/*$data = array
			(
			   'nama_kat_supplier' => $nama,
			   'ket_kat_supplier' => $ket,
			   'user_updt' => $id_user
			);
			
			$this->db->where('id_kat_supplier', $id);
			$this->db->update('tb_kat_supplier', $data);*/
			
			$query = "
				UPDATE tb_kat_supplier SET 
				nama_kat_supplier = '".$nama."'
				, ket_kat_supplier = '".$ket."'
				, user_updt = '".$id_user."'
				, tgl_updt = NOW() 
				WHERE id_kat_supplier = '".$id."';";
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			//$this->db->query('DELETE FROM tb_kat_supplier WHERE id_kat_supplier = '.$id.'');
			/*$this->db->where('id_kat_supplier',$id);
			$this->db->delete('tb_kat_supplier');*/
			
			$this->db->query("DELETE FROM tb_kat_supplier WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND id_kat_supplier = '".$id."'");
		}
		
		function get_kat_supplier_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_supplier', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_kat_supplier($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_kat_supplier', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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