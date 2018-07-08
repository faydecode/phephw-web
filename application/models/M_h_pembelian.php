<?php
	class M_h_pembelian extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_h_pembelian()
		{
			$query = $this->db->query("SELECT * 
										FROM tb_h_pembelian AS A
										LEFT JOIN
										(
											SELECT id_h_pembelian,COUNT(id_h_pembelian) AS JUMLAH_PRODUK FROM tb_d_pembelian GROUP BY id_h_pembelian
										) AS B ON A.id_h_pembelian = B.id_h_pembelian
										ORDER BY nama_h_pembelian ASC");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_h_pembelian_limit($cari,$limit,$offset)
		{
			//$query = $this->db->query("SELECT * FROM tb_h_pembelian ".$cari." ORDER BY nama_h_pembelian ASC LIMIT ".$offset.",".$limit);
			$query = $this->db->query("SELECT 	A.id_h_pembelian,A.id_supplier,A.nama_h_pembelian,A.tgl_h_pembelian,A.ket_h_pembelian,
										B.kode_supplier,B.no_supplier,B.nama_supplier,B.pemilik_supplier,B.alamat
										,CONCAT('PO/',YEAR(A.tgl_h_pembelian),'/',MONTH(A.tgl_h_pembelian),'/'
											   ,UCASE(REPLACE((A.id_h_pembelian),'=',''))
										,'/',A.id_h_pembelian) AS NO_PO
										,COALESCE(C.JUMLAH_PRODUK,0) AS JUMLAH_PRODUK
										,COALESCE(C.ITEMS,0) AS ITEMS
										,COALESCE(C.HARGA,0) AS HARGA
										,COALESCE(C.GRANDTOTAL,0) AS GRANDTOTAL
										,COALESCE(D.TOTAL_BAYAR,0) AS TOTAL_BAYAR
										,(COALESCE(C.GRANDTOTAL,0)  - COALESCE(D.TOTAL_BAYAR,0)) AS SISA
										FROM
										tb_h_pembelian AS A
										LEFT JOIN tb_supplier AS B
										ON A.id_supplier = B.id_supplier 
										LEFT JOIN
										(
											SELECT id_h_pembelian,COUNT(id_h_pembelian) AS JUMLAH_PRODUK,SUM(jumlah) AS ITEMS, SUM(harga) AS HARGA, SUM(jumlah*harga) AS GRANDTOTAL FROM tb_d_pembelian GROUP BY id_h_pembelian
										) AS C ON A.id_h_pembelian = C.id_h_pembelian
										LEFT JOIN
										(
											SELECT id_h_pembelian, SUM(nominal) AS TOTAL_BAYAR FROM tb_d_pembelian_bayar GROUP BY id_h_pembelian
										) AS D ON A.id_h_pembelian = D.id_h_pembelian
										".$cari." ORDER BY A.tgl_ins DESC LIMIT ".$offset.",".$limit);
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_h_pembelian_limit($cari)
		{
			$query = $this->db->query("SELECT COUNT(id_h_pembelian) AS JUMLAH FROM
										tb_h_pembelian AS A
										LEFT JOIN tb_supplier AS B
										ON A.id_supplier = B.id_supplier ".$cari);
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function simpan($id_supplier,$nama_h_pembelian,$tgl_h_pembelian,$ket_h_pembelian,$id_user,$kode_kantor)
		{
			$jam = date('Y-m-d H:i:s');
			/*$data = array
			(
			   'id_supplier' => $id_supplier,
			   'nama_h_pembelian' => $nama_h_pembelian,
			   'tgl_h_pembelian' => $tgl_h_pembelian,
			   'ket_h_pembelian' => $ket_h_pembelian,
			   'tgl_ins' => $jam,
			   'tgl_updt' => $jam,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			);*/
			
			//$this->db->insert('tb_h_pembelian', $data); 
			
			$this->db->query("
				INSERT INTO tb_h_pembelian
							(id_h_pembelian,
							 id_supplier,
							 no_h_pembelian,
							 nama_h_pembelian,
							 tgl_h_pembelian,
							 ket_h_pembelian,
							 sts_pembelian,
							 tgl_ins,
							 user_updt,
							 kode_kantor)
				VALUES (
					(
						SELECT CONCAT('BL',FRMTGL,ORD) AS id_h_pembelian
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
									 COALESCE(MAX(CAST(RIGHT(id_h_pembelian,5) AS UNSIGNED)) + 1,1) AS ORD
									 From tb_h_pembelian
									 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
									 AND kode_kantor = '".$kode_kantor."'
								 ) AS A
							 ) AS AA
					),
						'".$id_supplier."',
						'',
						'".$nama_h_pembelian."',
						'".$tgl_h_pembelian."',
						'".$ket_h_pembelian."',
						'',
						NOW(),
						'".$id_user."',
						'".$kode_kantor."');
			");
			
		}
		
		function edit($id,$id_supplier,$nama_h_pembelian,$tgl_h_pembelian,$ket_h_pembelian,$id_user)
		{
			$jam = date('Y-m-d H:i:s');
			$data = array
			(
			   'id_supplier' => $id_supplier,
			   'nama_h_pembelian' => $nama_h_pembelian,
			   'tgl_h_pembelian' => $tgl_h_pembelian,
			   'ket_h_pembelian' => $ket_h_pembelian,
			   'tgl_updt' => $jam,
			   'user_updt' => $id_user
			);
			
			$this->db->where('id_h_pembelian', $id);
			$this->db->update('tb_h_pembelian', $data);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_h_pembelian WHERE id_h_pembelian = '".$id."'");
		}
		
		function get_h_pembelian_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_h_pembelian', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_h_pembelian($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_h_pembelian', array($berdasarkan => $cari));
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