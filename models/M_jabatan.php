<?php
	class M_jabatan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_jabatan()
		{
			$query = $this->db->query("SELECT * FROM tb_jabatan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' ORDER BY nama_jabatan ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_jabatan_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_jabatan ".$cari." ORDER BY nama_jabatan ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT A.id_jabatan,A.nama_jabatan,A.ket_jabatan,A.tgl_insert,A.tgl_update,A.user,A.kode_kantor,COALESCE(B.JUMLAH,0) AS JUMLAH FROM tb_jabatan AS A
			LEFT JOIN
			(
				SELECT id_jabatan,COUNT(id_jabatan) AS JUMLAH FROM tb_hak_akses GROUP BY id_jabatan
			) AS B
			ON A.id_jabatan = B.id_jabatan ".$cari." ORDER BY nama_jabatan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_jabatan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_jabatan) AS JUMLAH FROM tb_jabatan ".$cari);
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
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_jabatan', $data); */
			
			$query = "INSERT INTO tb_jabatan
			 (
				 id_jabatan
				 ,nama_jabatan
				 ,ket_jabatan
				 ,tgl_insert
				 ,kode_kantor
				 ,user
			 )
			 Values
			 (
				 (
					 SELECT CONCAT('JBTN',FRMTGL,ORD) AS id_jabatan
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
							 COALESCE(MAX(CAST(RIGHT(id_jabatan,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_jabatan
							 WHERE DATE_FORMAT(tgl_insert,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
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
			   'nama_jabatan' => $nama,
			   'ket_jabatan' => $ket,
			   'user' => $id_user
			);
			
			$this->db->where('id_jabatan', $id);
			$this->db->update('tb_jabatan', $data);*/
			
			$query = "UPDATE tb_jabatan SET nama_jabatan = '".$nama."', ket_jabatan = '".$ket."', user = '".$id_user."', tgl_update = NOW() WHERE id_jabatan = '".$id."';";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			/*$this->db->where('id_jabatan',$id);
			$this->db->delete('tb_jabatan');*/
			
			$this->db->query("DELETE FROM tb_jabatan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND id_jabatan = '".$id."'");
		}
		
		function get_jabatan_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_jabatan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_jabatan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_jabatan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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