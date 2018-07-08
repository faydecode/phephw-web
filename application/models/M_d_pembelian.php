<?php
	class M_d_pembelian extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_d_pembelian($id_h_pembelian,$cari)
		{
			$query = $this->db->query("
										SELECT *,(A.harga-A.GET_DISKON) AS harga, (A.jumlah * (A.harga-A.GET_DISKON)) AS GRAND_TOTAL FROM
										(
											SELECT A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah,A.harga,A.harga AS harga_ori,A.kode_satuan,A.nama_satuan
											,B.kode_produk,B.nama_produk
											,A.diskon,A.optr_diskon
											,IF(A.optr_diskon ='%', ((A.harga/100)*A.diskon), (A.diskon)) AS GET_DISKON
											FROM tb_d_pembelian AS A
											LEFT JOIN tb_produk AS B
											ON A.id_produk = B.id_produk
										) AS A
										WHERE A.id_h_pembelian = '".$id_h_pembelian."'
										".$cari." ");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function cek_h_pembelian($id)
		{
			$query = $this->db->query("SELECT 
											*,id_h_pembelian
											,CONCAT('PO/',YEAR(tgl_h_pembelian),'/',MONTH(tgl_h_pembelian),'/'
											   ,UCASE(REPLACE(TO_BASE64(id_h_pembelian),'=',''))
											,'/',id_h_pembelian) AS NO_PO
											FROM tb_h_pembelian WHERE id_h_pembelian = '".$id."'");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan($id_h_pembelian,$id_produk,$jumlah,$harga,$diskon,$optr_diskon,$kode_satuan,$nama_satuan,$user,$kode_kantor)
		{
			$jam = date('Y-m-d H:i:s'); 
			/*$data = array
			(
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_produk' => $id_produk,
			   'jumlah' => $jumlah,
			   'harga' => $harga,
			   'diskon' => $diskon,
			   'optr_diskon' => $optr_diskon,
			   'kode_satuan'=>$kode_satuan,
			   'nama_satuan'=>$nama_satuan,
			   'tgl_ins' => $jam,
			   'tgl_updt' => $jam,
			   'user_updt' => $user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_d_pembelian', $data); 
			*/
			
			$this->db->query("
				INSERT INTO tb_d_pembelian
							(id_d_pembelian,
							 id_h_pembelian,
							 id_produk,
							 jumlah,
							 harga,
							 diskon,
							 optr_diskon,
							 kode_satuan,
							 nama_satuan,
							 status_konversi,
							 konversi,
							 acc,
							 tgl_ins,
							 user_updt,
							 kode_kantor)
				VALUES (
					(
					   SELECT CONCAT('BYRD',FRMTGL,ORD) AS id_d_pembelian
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
								 COALESCE(MAX(CAST(RIGHT(id_d_pembelian,5) AS UNSIGNED)) + 1,1) AS ORD
								 From tb_d_pembelian
								 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
								 AND kode_kantor = '".$kode_kantor."'
							 ) AS A
						 ) AS AA
					),
						'".$id_h_pembelian."',
						'".$id_produk."',
						'".$jumlah."',
						'".$harga."',
						'".$diskon."',
						'".$optr_diskon."',
						'".$kode_satuan."',
						'".$nama_satuan."',
						'',
						'',
						'',
						NOW(),
						'".$user."',
						'".$kode_kantor."');
			
			");
			
		}
		
		
		
		function edit($id,$id_h_pembelian,$id_produk,$jumlah,$harga,$diskon,$optr_diskon,$kode_satuan,$nama_satuan,$user)
		{ 
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_produk' => $id_produk,
			   'jumlah' => $jumlah,
			   'harga' => $harga,
			   'diskon' => $diskon,
			   'optr_diskon' => $optr_diskon,
			   'kode_satuan'=>$kode_satuan,
			   'nama_satuan'=>$nama_satuan,
			   'tgl_ins' => $jam,
			   'tgl_updt' => $jam,
			   'user_updt' => $user
			);
			
			$this->db->where('id_d_pembelian', $id);
			$this->db->update('tb_d_pembelian', $data);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_d_pembelian WHERE id_d_pembelian = '".$id."'");
		}
		
		function get_d_pembelian_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_d_pembelian', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_d_pembelian($berdasarkan,$cari)
        {
            //$query = $this->db->get_where('tb_d_pembelian', array($berdasarkan => $cari));
			$query = $this->db->query("
										SELECT *,(A.harga-A.GET_DISKON) AS harga, (A.jumlah * (A.harga-A.GET_DISKON)) AS GRAND_TOTAL FROM
										(
											SELECT A.id_d_pembelian,A.id_h_pembelian,A.id_produk,A.jumlah,A.harga,A.harga AS harga_ori,A.kode_satuan,A.nama_satuan
											,B.kode_produk,B.nama_produk
											,A.diskon,A.optr_diskon
											,IF(A.optr_diskon ='%', ((A.harga/100)*A.diskon), (A.diskon)) AS GET_DISKON
											FROM tb_d_pembelian AS A
											LEFT JOIN tb_produk AS B
											ON A.id_produk = B.id_produk
										) AS A
										WHERE ".$berdasarkan." = ".$cari);
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