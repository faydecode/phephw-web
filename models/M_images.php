<?php
	class M_images extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_images_limit($id,$group,$cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT * FROM tb_images WHERE id = '". $id ."' AND group_by = '". $group ."'
										".$cari." ORDER BY tgl_ins ASC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_images_limit($id,$group,$cari)
		{
			$query = $this->db->query("SELECT COUNT(id) AS JUMLAH FROM tb_images WHERE id = '". $id ."' AND group_by = '". $group ."'
										".$cari);
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
			$id
			,$group_by
			,$nama
			,$foto
			,$foto_url
			,$ket
			,$user_updt
			,$kode_kantor
		)
		{
			$query = "
				INSERT INTO tb_images
				(
							 id_images,
							 id,
							 group_by,
							 img_nama,
							 img_file,
							 img_url,
							 ket_img,
							 tgl_ins,
							 user_updt,
							 kode_kantor
				)
				VALUES (
						(
							 SELECT CONCAT('IMG',FRMTGL,ORD) AS id_images
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
									 COALESCE(MAX(CAST(RIGHT(id_images,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_images
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 -- AND kode_kantor = '".$kode_kantor."'
								 ) AS A
							 ) AS AA
						 ),
						'".$id."',
						'".$group_by."',
						'".$nama."',
						'".$foto."',
						'".$foto_url."',
						'".$ket."',
						now(),
						'".$user_updt."',
						'".$kode_kantor."'
				 );
			";

			$this->db->query($query);
			
		}
		
		function edit_with_image
		(
			$id_images
			,$id
			,$group_by
			,$nama
			,$foto
			,$foto_url
			,$ket
			,$user_updt
			,$kode_kantor
		)
		{
			$query = "
				UPDATE tb_images
				SET id = '".$id."',
				  img_nama = '".$nama."',
				  img_file = '".$foto."',
				  img_url = '".$foto_url."',
				  ket_img = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$user_updt."'
				WHERE id_images = '".$id_images."'
					AND kode_kantor = '".$kode_kantor."';
			";

			$this->db->query($query);
		}
		
		function edit_no_image
		(
			$id_images
			,$id
			,$group_by
			,$nama
			,$ket
			,$user_updt
			,$kode_kantor
		)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id' => $id,
			   'group_by' => $group_by,
			   'img_nama' => $nama,
			   'ket_img' => $ket,
			   'tgl_updt' => $jam,
			   'user_updt' => $user_updt,
			);
			
			$this->db->where('id_images', $id_images);
			$this->db->update('tb_images', $data);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_images WHERE id_images = '".$id."' AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."'");
		}
		
		function get_images_id($id_images)
		{
			$query = $this->db->get_where('tb_images', array('id_images' => $id_images, 'kode_kantor' => $this->session->userdata('ses_kode_kantor')));
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		
		function get_images($id,$group_by,$berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_images', array('id'=>$id,'group_by'=>$group_by,$berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
        }
		
		function get_id_images($kode_kantor)
		{
			$query = $this->db->query( "
				
					 SELECT CONCAT('IMG',FRMTGL,ORD) AS id_images
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
							 COALESCE(MAX(CAST(RIGHT(id_images,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_images
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 -- AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
			");
			
			if($query->num_rows() > 0)
            {
                return $query->row();
            }
            else
            {
                return false;
            }
		}
	}
?>