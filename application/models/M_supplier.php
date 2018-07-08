<?php
	class M_supplier extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_supplier_id($id_supplier)
		{
			//$query = $this->db->get_where('tb_supplier', array('id_supplier' => $id_supplier), $limit, $offset);
			$query = $this->db->get_where('tb_supplier', array('id_supplier' => $id_supplier,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
	/* 	function list_supplier_no_akun($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT A.id_supplier,A.nik_supplier, A.nama_supplier,A.pnd,A.tlp,A.email,A.avatar,A.avatar_url,A.alamat,A.ket_supplier,B.id_jabatan,B.nama_jabatan,C.id_akun,C.user,C.pass,C.pertanyaan1,C.pertanyaan2,C.jawaban1,C.jawaban2 FROM tb_supplier AS A
										LEFT JOIN tb_jabatan AS B ON A.id_jabatan = B.id_jabatan
										LEFT JOIN tb_akun AS C ON A.id_supplier = C.id_supplier
										WHERE C.user IS NULL ".$cari." ORDER BY nama_supplier ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		} */
		
		function list_supplier_limit($cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * 
										FROM tb_supplier AS A
										LEFT JOIN tb_kat_supplier AS B
											ON A.id_kat_supplier = B.id_kat_supplier
											AND A.kode_kantor = B.kode_kantor
										".$cari." ORDER BY nama_supplier ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_supplier_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(A.id_supplier) AS JUMLAH FROM tb_supplier AS A
										LEFT JOIN tb_kat_supplier AS B
											ON A.id_kat_supplier = B.id_kat_supplier
											AND A.kode_kantor = B.kode_kantor ".$cari);
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
			$id_kat_supplier
			,$kode_supplier
			,$nama_supplier
			,$pemilik
			,$siup
			,$situ
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
			   'id_kat_supplier' => $id_kat_supplier,
			   'kode_supplier' => $kode_supplier,
			   'nama_supplier' => $nama_supplier,
			   'pemilik_supplier' => $pemilik,
			   'siup' => $siup,
			   'situ' => $situ,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_supplier' => $keterangan,
			   'kode_kantor' => $kode_kantor,
			   'user_updt' => $user_updt
			);

			$this->db->insert('tb_supplier', $data); */
			
			$query = "INSERT INTO tb_supplier
			 (
				 id_supplier
				 ,id_kat_supplier
				 ,kode_supplier
				 ,nama_supplier
				 ,pemilik_supplier
				 ,siup
				 ,situ
				 ,tlp
				 ,email
				 ,avatar
				 ,avatar_url
				 ,alamat
				 ,ket_supplier
				 ,tgl_ins
				 ,kode_kantor
				 ,user_updt
			 )
			 Values
			 (
				 (
					 SELECT CONCAT('SP',FRMTGL,ORD) AS id_supplier
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
							 COALESCE(MAX(CAST(RIGHT(id_supplier,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_supplier
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
				 )
				,'".$id_kat_supplier."'
				,'".$kode_supplier."'
				,'".$nama_supplier."'
				,'".$pemilik."'
				,'".$siup."'
				,'".$situ."'
				,'".$tlp."'
				,'".$email."'
				,'".$avatar."'
				,'".$avatar_url."'
				,'".$alamat."'
				,'".$keterangan."'
				 ,NOW()
				 ,'".$kode_kantor."'
				 ,'".$user_updt."'
			 )";
		$this->db->query($query);

		}
		
		function edit_with_image
		(
			$id_supplier
			,$id_kat_supplier
			,$kode_supplier
			,$nama_supplier
			,$pemilik
			,$siup
			,$situ
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
			   'id_kat_supplier' => $id_kat_supplier,
			   'kode_supplier' => $kode_supplier,
			   'nama_supplier' => $nama_supplier,
			   'pemilik_supplier' => $pemilik,
			   'siup' => $siup,
			   'situ' => $situ,
			   'tlp' => $tlp,
			   'email' => $email,
			   'avatar' => $avatar,
			   'avatar_url' => $avatar_url,
			   'alamat' => $alamat,
			   'ket_supplier' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			$this->db->where('id_supplier', $id_supplier);
			$this->db->update('tb_supplier', $data);*/
			
			$query = "UPDATE tb_supplier SET 
				id_kat_supplier = '".$id_kat_supplier."'
				,kode_supplier = '".$kode_supplier."'
				,nama_supplier = '".$nama_supplier."'
				,pemilik_supplier = '".$pemilik."'
				,siup = '".$siup."'
				,situ = '".$situ."'
				,tlp = '".$tlp."'
				,email = '".$email."'
				,avatar = '".$avatar."'
				,avatar_url = '".$avatar_url."'
				,alamat = '".$alamat."'
				,ket_supplier = '". $keterangan."'
				, user_updt = '".$user_updt."'
				, tgl_updt = NOW() 
				WHERE id_supplier = '".$id_supplier."';";
			$this->db->query($query);
		}
		
		function edit_no_image
		(
			$id_supplier
			,$id_kat_supplier
			,$kode_supplier
			,$nama_supplier
			,$pemilik
			,$siup
			,$situ
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
			   'id_kat_supplier' => $id_kat_supplier,
			   'kode_supplier' => $kode_supplier,
			   'nama_supplier' => $nama_supplier,
			   'pemilik_supplier' => $pemilik,
			   'siup' => $siup,
			   'situ' => $situ,
			   'tlp' => $tlp,
			   'email' => $email,
			   'alamat' => $alamat,
			   'ket_supplier' => $keterangan,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt
			);
			
			$this->db->where('id_supplier', $id_supplier);
			$this->db->update('tb_supplier', $data);*/
			
			$query = "UPDATE tb_supplier SET 
				id_kat_supplier = '".$id_kat_supplier."'
				,kode_supplier = '".$kode_supplier."'
				,nama_supplier = '".$nama_supplier."'
				,pemilik_supplier = '".$pemilik."'
				,siup = '".$siup."'
				,situ = '".$situ."'
				,tlp = '".$tlp."'
				,email = '".$email."'
				,alamat = '".$alamat."'
				,ket_supplier = '". $keterangan."'
				, user_updt = '".$user_updt."'
				, tgl_updt = NOW() 
				WHERE id_supplier = '".$id_supplier."';";
			$this->db->query($query);
		}
		
		function hapus($id)
		{
			//$this->db->query('DELETE FROM tb_supplier WHERE id_supplier = '.$id.'');
			$this->db->query("DELETE FROM tb_supplier WHERE kode_kantor = '".$this->session->userdata('ses_kode_kantor')."' AND id_supplier = '".$id."'");
		}
		
		function get_supplier($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_supplier', array($berdasarkan => $cari,'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
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