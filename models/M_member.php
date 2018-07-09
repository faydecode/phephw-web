<?php
	class M_member extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_member_id($id_costumer)
		{
			//$query = $this->db->get_where('tb_costumer', array('id_costumer' => $id_costumer), $limit, $offset);
			$query = $this->db->get_where('tb_costumer', array('id_costumer' => $id_costumer,'kode_kantor' => $this->session->userdata('ses_kode_kantor') ));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function get_kat_member()
		{
			$query = $this->db->query("
										SELECT SUM(CASE WHEN nama_kat_costumer = 'BUYER' THEN id_kat_costumer ELSE '' END) AS BUYER,
											   SUM(CASE WHEN nama_kat_costumer = 'SELLER' THEN id_kat_costumer ELSE '' END) AS SELLER
										FROM tb_kat_costumer"
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
		
		function list_member_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_costumer AS A
										LEFT JOIN tb_kat_costumer AS B
											ON A.id_kat_costumer = B.id_kat_costumer and A.kode_kantor = B.kode_kantor
										".$cari." ORDER BY A.nama_lengkap ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_member_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_costumer) AS JUMLAH FROM tb_costumer A ".$cari);
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
			$id_kat_costumer
			,$id_karyawan
			,$kode_member
			,$nama_costumer
			,$panggilan
			,$tgl_lahir
			,$jenis_kelamin
			,$alamat
			,$hp
			,$username
			,$pass
			,$avatar
			,$avatar_url
			,$email
			,$ket_costumer
			,$kode_kantor
			,$user_updt
		)
		{
			$query = "
				INSERT INTO tb_costumer
				(
							 id_costumer,
							 id_karyawan,
							 id_kat_costumer,
							 tgl_pengajuan,
							 no_costumer,
							 nama_lengkap,
							 panggilan,
							 nik,
							 no_ktp,
							 tgl_lahir,
							 jenis_kelamin,
							 status,
							 alamat_rumah_sekarang,
							 hp,
							 status_rumah,
							 lama_menempati,
							 pendidikan,
							 ibu_kandung,
							 nama_perusahaan,
							 alamat_perusahaan,
							 bidang_usaha,
							 jabatan,
							 penghasilan_perbulan,
							 nama_lengkap_pnd,
							 alamat_rumah_pnd,
							 hp_pnd,
							 pekerjaan,
							 hubungan,
							 username,
							 pass,
							 avatar,
							 avatar_url,
							 tgl_ins,
							 user_updt,
							 kode_kantor,
							 email_costumer,
							 ket_costumer
				)
				VALUES (
					(
					 SELECT CONCAT('MB',FRMTGL,ORD) AS id_costumer
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
							 COALESCE(MAX(CAST(RIGHT(id_costumer,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_costumer
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 ),
						'".$id_karyawan."',
						'".$id_kat_costumer."',
						now(),
						'".$kode_member."',
						'".$nama_costumer."',
						'".$nama_costumer."',
						'',
						'',
						'".$tgl_lahir."',
						'".$jenis_kelamin."',
						'0',
						'".$alamat."',
						'".$hp."',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'',
						'".$username."',
						'".$pass."',
						'".$avatar."',
						'".$avatar_url."',
						now(),
						'".$user_updt."',
						'".$kode_kantor."',
						'".$email."',
						'".$ket_costumer."'
				 );
			";

			return $this->db->query($query);
		}
		
		function edit_with_image
		(
			$id_costumer
			,$id_kat_costumer
			,$nama_costumer
			,$tgl_lahir
			,$jenis_kelamin
			,$alamat
			,$hp
			,$username
			,$pass
			,$avatar
			,$avatar_url
			,$email
			,$ket_costumer
			,$user_updt
		)
		{
			$query = "
				UPDATE tb_costumer
				SET id_kat_costumer = '".$id_kat_costumer."',
				  nama_lengkap = '".$nama_costumer."',
				  tgl_lahir = '".$tgl_lahir."',
				  jenis_kelamin = '".$jenis_kelamin."',
				  alamat_rumah_sekarang = '".$alamat."',
				  hp = '".$hp."',
				  username = '".$username."',
				  pass = '".$pass."',
				  avatar = '".$avatar."',
				  avatar_url = '".$avatar_url."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."',
				  email_costumer = '".$email."',
				  ket_costumer = '".$ket_costumer."'
				WHERE id_costumer = '".$id_costumer."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);

		}
		
		function edit_no_image
		(
			$id_costumer
			,$id_kat_costumer
			,$nama_costumer
			,$tgl_lahir
			,$jenis_kelamin
			,$alamat
			,$hp
			,$username
			,$pass
			,$email
			,$ket_costumer
			,$user_updt
		)
		{
			$query = "
				UPDATE tb_costumer
				SET id_kat_costumer = '".$id_kat_costumer."',
				  nama_lengkap = '".$nama_costumer."',
				  tgl_lahir = '".$tgl_lahir."',
				  jenis_kelamin = '".$jenis_kelamin."',
				  alamat_rumah_sekarang = '".$alamat."',
				  hp = '".$hp."',
				  username = '".$username."',
				  pass = '".$pass."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."',
				  email_costumer = '".$email."',
				  ket_costumer = '".$ket_costumer."'
				WHERE id_costumer = '".$id_costumer."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_costumer WHERE id_costumer = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_member($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_costumer', array($berdasarkan => $cari, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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