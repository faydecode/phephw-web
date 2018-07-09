<?php
	class M_karyawan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_karyawan_id($id_karyawan)
		{
			//$query = $this->db->get_where('tb_karyawan', array('id_karyawan' => $id_karyawan), $limit, $offset);
			$query = $this->db->get_where('tb_karyawan', array('id_karyawan' => $id_karyawan,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function list_karyawan_no_akun($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_karyawan,A.nik_karyawan, A.nama_karyawan,A.pnd,A.tlp,A.email,A.avatar,A.avatar_url,A.alamat,A.ket_karyawan,B.id_jabatan,B.nama_jabatan,C.id_akun,C.user,C.pass,C.pertanyaan1,C.pertanyaan2,C.jawaban1,C.jawaban2 FROM tb_karyawan AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan AND A.kode_kantor = B.kode_kantor
										LEFT JOIN tb_akun AS C ON A.id_karyawan = C.id_karyawan AND A.kode_kantor = C.kode_kantor
										WHERE C.user IS NULL ".$cari." ORDER BY nama_karyawan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_karyawan_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_karyawan AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan AND A.kode_kantor = B.kode_kantor
										".$cari." ORDER BY nama_karyawan ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_karyawan_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(A.id_karyawan) AS JUMLAH 
										FROM tb_karyawan AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan AND A.kode_kantor = B.kode_kantor ".$cari);
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
			$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$avatar
			,$avatar_url
			,$alamat
			,$keterangan
			,$kode_kantor
			,$user_updt
		)
		{
			//Tidak ditambah tgl_ins dan tgl_updt karena sudah current stamp di databasenya
			/*$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   'no_karyawan' => $no_karyawan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'kode_kantor' => $kode_kantor,
			   'user_updt' => $user_updt
			);

			$this->db->insert('tb_karyawan', $data); */
			
			$query = "
					 INSERT INTO tb_karyawan
					  (
						  id_karyawan
						  ,id_jabatan
						  ,no_karyawan
						  ,nik_karyawan
						  ,nama_karyawan
						  ,pnd
						  ,tlp
						  ,email
						  ,alamat
						  ,ket_karyawan
						  ,tgl_ins
						  ,kode_kantor
						  ,user_updt
					  )
					  Values
					  (
						  (
							SELECT CONCAT('KRYWN',FRMTGL,ORD) AS id_karyawan
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
									 COALESCE(MAX(CAST(RIGHT(id_karyawan,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_karyawan
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 AND kode_kantor = '".$kode_kantor."'
								 ) AS A
							 ) AS AA
						  )
						  ,'".$id_jabatan."'
						  ,'".$no_karyawan."'
						  ,'".$nik_karyawan."'
						  ,'".$nama_karyawan."'
						  ,'".$pnd."'
						  ,'".$tlp."'
						  ,'".$email."'
						  ,'".$alamat."'
						  ,'".$keterangan."'
						  ,NOW()
						  ,'".$kode_kantor."'
						  ,'".$user_updt."'
					  )

					";
				$this->db->query($query);
		}
		
		function edit_with_image
		(
			$id_karyawan
			,$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$avatar
			,$avatar_url
			,$alamat
			,$keterangan
			,$user_updt
		)
		{
			/*$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			$this->db->where('id_karyawan', $id_karyawan);
			$this->db->update('tb_karyawan', $data);*/
			
			$query = "UPDATE tb_karyawan SET 
						id_jabatan = '".$id_jabatan."'
						, nik_karyawan = '".$nik_karyawan."'
						, nama_karyawan = '".$nama_karyawan."'
						, pnd = '".$pnd."'
						, tlp = '".$tlp."'
						, email = '".$email."'
						, avatar = '".$avatar."'
						, avatar_url = '".$avatar_url."'
						, alamat = '".$alamat."'
						, ket_karyawan = '".$ket_karyawan."'
						, user_updt = '".$user_updt."'
						, tgl_updt = NOW() 
						WHERE id_karyawan = '".$id_karyawan."';";
			$this->db->query($query);
		}
		
		function edit_no_image
		(
			$id_karyawan
			,$id_jabatan
			,$no_karyawan
			,$nik_karyawan
			,$nama_karyawan
			,$pnd
			,$tlp
			,$email
			,$alamat
			,$keterangan
			,$user_updt
		)
		{
			/*$id = date('ymdHis'); 
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_jabatan' => $id_jabatan,
			   'nik_karyawan' => $nik_karyawan,
			   'nama_karyawan' => $nama_karyawan,
			   'pnd' => $pnd,
			   'tlp' => $tlp,
			   'email' => $email,
			   'alamat' => $alamat,
			   'ket_karyawan' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			$this->db->where('id_karyawan', $id_karyawan);
			$this->db->update('tb_karyawan', $data);*/
			
			$query = "UPDATE tb_karyawan SET 
						id_jabatan = '".$id_jabatan."'
						, nik_karyawan = '".$nik_karyawan."'
						, nama_karyawan = '".$nama_karyawan."'
						, pnd = '".$pnd."'
						, tlp = '".$tlp."'
						, email = '".$email."'
						, alamat = '".$alamat."'
						, ket_karyawan = '".$ket_karyawan."'
						, user_updt = '".$user_updt."'
						, tgl_updt = NOW() 
						WHERE id_karyawan = '".$id_karyawan."';";
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_karyawan WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND id_karyawan = '".$id."'");
		}
		
		function get_karyawan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_karyawan', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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