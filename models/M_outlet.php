<?php
	class M_outlet extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_outlet_id($id_outlet)
		{
			//$query = $this->db->get_where('tb_outlet', array('id_outlet' => $id_outlet), $limit, $offset);
			$query = $this->db->get_where('tb_outlet', array('id_outlet' => $id_outlet,'kode_kantor' => $this->session->userdata('ses_kode_kantor') ));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_kode_outlet()
		{
			$query = $this->db->query(
			"
				SELECT CONCAT(FRMTGL,COALESCE(ORD,'0001')) AS kode_outlet
				FROM
				(
					SELECT CONCAT(Y,M) AS FRMTGL
					 ,CASE
						WHEN ORD >= 10 THEN CONCAT('00',CAST(ORD AS CHAR))
						WHEN ORD >= 100 THEN CONCAT('0',CAST(ORD AS CHAR))
						WHEN ORD >= 1000 THEN CAST(ORD AS CHAR)
						ELSE CONCAT('000',CAST(ORD AS CHAR))
						END AS ORD
					FROM
					(
						SELECT 
						CAST(LEFT(NOW(),4) AS CHAR) AS Y,
						CAST(MID(NOW(),6,2) AS CHAR) AS M,
						MID(NOW(),9,2) AS D,
						(MAX(CAST(RIGHT(kode_outlet,4) AS UNSIGNED)) + 1) AS ORD FROM tb_outlet
					) AS A
				) AS AA
			"
			);
			
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
	/* 	function list_outlet_no_akun($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_outlet,A.nik_outlet, A.nama_outlet,A.pnd,A.tlp,A.email,A.avatar,A.avatar_url,A.alamat,A.ket_outlet,B.id_jabatan,B.nama_jabatan,C.id_akun,C.user,C.pass,C.pertanyaan1,C.pertanyaan2,C.jawaban1,C.jawaban2 FROM tb_outlet AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan
										LEFT JOIN tb_akun AS C ON A.id_outlet = C.id_outlet
										WHERE C.user IS NULL ".$cari." ORDER BY nama_outlet ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		} */
		
		function list_outlet_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_outlet AS A
										LEFT JOIN tb_kat_outlet AS B
											ON A.id_kat_outlet = B.id_kat_outlet
										".$cari." ORDER BY nama_outlet ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_outlet_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_outlet) AS JUMLAH FROM tb_outlet A ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan
		(
			$id_kat_outlet
			,$kode_outlet
			,$nama_outlet
			,$tlp
			,$ketua
			,$alamat
			,$email
			,$keterangan
			,$kode_kantor
			,$user_updt
		)
		{
			$query = "
				INSERT INTO tb_outlet
				(
							 id_outlet,
							 id_kat_outlet,
							 kode_outlet,
							 nama_outlet,
							 tlp_outlet,
							 ketua_outlet,
							 email,
							 alamat_outlet,
							 ket_outlet,
							 tgl_ins,
							 user_updt,
							 kode_kantor)
				VALUES (
					(
					 SELECT CONCAT('MB',FRMTGL,ORD) AS id_outlet
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
							 COALESCE(MAX(CAST(RIGHT(id_outlet,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_outlet
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
						'".$id_kat_outlet."',
						'".$kode_outlet."',
						'".$nama_outlet."',
						'".$tlp."',
						'".$ketua."',
						'".$email."',
						'".$alamat."',
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
			$id_outlet
			,$id_kat_outlet
			,$kode_outlet
			,$nama_outlet
			,$tlp
			,$ketua
			,$alamat
			,$email
			,$keterangan
			,$user_updt
		)
		{
			$query = "
				UPDATE tb_outlet
				SET id_kat_outlet = '".$id_kat_outlet."',
				  kode_outlet = '".$kode_outlet."',
				  nama_outlet = '".$nama_outlet."',
				  tlp_outlet = '".$tlp."',
				  ketua_outlet = '".$ketua."',
				  email = '".$email."',
				  alamat_outlet = '".$alamat."',
				  ket_outlet = '".$keterangan."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."'
				WHERE id_outlet = '".$id_outlet."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";	
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_outlet WHERE id_outlet = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_outlet($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_outlet', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
	}
?>