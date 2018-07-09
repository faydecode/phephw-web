<?php
	class M_bayar_pembelian extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_pembayaran_pembelian_limit($id,$cari,$limit,$offset)
		{
			$query = $this->db->query("
										SELECT 			id_supplier,id_d_bayar,id_h_pembelian,id_bank,cara,nominal,ket,tgl_bayar,tgl_ins,tgl_updt,user_updt,kode_kantor,nama_h_pembelian,NO_PO
										,CONCAT('PMBS/',YEAR(tgl_bayar),'/',MONTH(tgl_bayar),'/'
										,UCASE(REPLACE(TO_BASE64(id_d_bayar),'=',''))
										,'/',id_d_bayar) AS NO_KWITANSI
										FROM
										(
											SELECT
												B.id_supplier,B.id_d_bayar,B.id_h_pembelian,B.id_bank,B.cara,B.nominal,B.ket,B.tgl_bayar,B.tgl_ins,B.tgl_updt,B.user_updt,B.kode_kantor
												,A.nama_h_pembelian
												,CONCAT('PO/',YEAR(A.tgl_h_pembelian),'/',MONTH(A.tgl_h_pembelian),'/'
											   ,UCASE(REPLACE(TO_BASE64(A.id_h_pembelian),'=',''))
												,'/',A.id_h_pembelian) AS NO_PO 
											FROM tb_h_pembelian AS A
											RIGHT JOIN tb_d_pembelian_bayar AS B ON A.id_h_pembelian = B.id_h_pembelian
											".$id." LIMIT ".$offset.",".$limit."
										) AS AA ".$cari." ORDER BY tgl_ins DESC;");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function count_pembayaran_pembelian_limit($id,$cari)
		{
			$query = $this->db->query("
										SELECT 	COUNT(id_d_bayar) AS JUMLAH
										FROM
										(
											SELECT
												B.id_supplier,B.id_d_bayar,B.id_h_pembelian,B.id_bank,B.cara,B.nominal,B.ket,B.tgl_bayar,B.tgl_ins,B.tgl_updt,B.user_updt,B.kode_kantor
												,A.nama_h_pembelian
												,CONCAT('PO/',YEAR(A.tgl_h_pembelian),'/',MONTH(A.tgl_h_pembelian),'/'
											   ,UCASE(REPLACE(TO_BASE64(A.id_h_pembelian),'=',''))
												,'/',A.id_h_pembelian) AS NO_PO 
											FROM tb_h_pembelian AS A
											RIGHT JOIN tb_d_pembelian_bayar AS B ON A.id_h_pembelian = B.id_h_pembelian
											".$id."
										) AS AA ".$cari.";");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		
		function simpan($id_h_pembelian,$id_supplier,$cara,$nominal,$ket,$tgl_bayar,$id_user,$kode_kantor)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s');
			
			/*$data = array
			(
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_supplier' => $id_supplier,
			   'cara' => $cara,
			   'nominal' => $nominal,
			   'ket' => $ket,
			   'tgl_bayar' => $tgl_bayar,
			   'tgl_ins' =>$jam,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_d_pembelian_bayar', $data); 
			*/
			$this->db->query("
				INSERT INTO tb_d_pembelian_bayar
							(id_d_bayar,
							 id_h_pembelian,
							 id_supplier,
							 id_bank,
							 cara,
							 norek,
							 nama_bank,
							 atas_nama,
							 nominal,
							 ket,
							 type_bayar,
							 id_pembayaran_supplier,
							 tgl_bayar,
							 tgl_ins,
							 user_updt,
							 kode_kantor)
				VALUES (
					(
					 SELECT CONCAT('BYR',FRMTGL,ORD) AS id_d_bayar
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
							 COALESCE(MAX(CAST(RIGHT(id_d_bayar,5) AS UNSIGNED)) + 1,1) AS ORD
							 From tb_d_pembelian_bayar
							 WHERE DATE_FORMAT(tgl_ins,'%m-%Y') = DATE_FORMAT(NOW(),'%m-%Y')
							 AND kode_kantor = '".$kode_kantor."'
						 ) AS A
					 ) AS AA
					),
						'".$id_h_pembelian."',
						'".$id_supplier."',
						'',
						'".$cara."',
						'',
						'',
						'',
						'".$nominal."',
						'".$ket."',
						'',
						'',
						'".$tgl_bayar."',
						NOW(),
						'".$id_user."',
						'".$kode_kantor."');
			");
			
		}
		
		function edit($id_d_bayar,$id_supplier,$id_h_pembelian,$cara,$nominal,$ket,$tgl_bayar,$id_user)
		{
			$date = date('Y-m-d'); 
			$jam = date('Y-m-d H:i:s');
			
			$data = array
			(
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_supplier' => $id_supplier,
			   'cara' => $cara,
			   'nominal' => $nominal,
			   'ket' => $ket,
			   'tgl_bayar' => $tgl_bayar,
			   'tgl_updt' =>$jam,
			   'user_updt' => $id_user
			);

			$this->db->where('id_d_bayar', $id_d_bayar);
			$this->db->update('tb_d_pembelian_bayar', $data);
		}
		
		function hapus($id)
		{
			$this->db->query("DELETE FROM tb_d_pembelian_bayar WHERE id_d_bayar = '".$id."'");
		}
		
		function list_pembelian_masih_hutang($id_supplier)
		{
			$query = $this->db->query("SELECT AA.id_h_pembelian,AA.SISA FROM
										(
											SELECT 	A.id_h_pembelian,A.id_supplier,A.tgl_h_pembelian,A.tgl_ins
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
										) AS AA
										WHERE AA.SISA > 0 AND AA.id_supplier = '".$id_supplier."'
										ORDER BY  AA.tgl_h_pembelian ASC, AA.tgl_ins ASC, AA.id_h_pembelian ASC;");
			if($query->num_rows() > 0)
            {
                return $query;
            }
            else
            {
                return false;
            }
		}
		
		function sum_pembelian_masih_hutang($id_supplier)
		{
			$query = $this->db->query("SELECT SUM(AA.SISA) AS ALLSISA FROM
										(
											SELECT 	A.id_h_pembelian,A.id_supplier,A.tgl_h_pembelian,A.tgl_ins
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
										) AS AA
										WHERE AA.SISA > 0 AND AA.id_supplier = '".$id_supplier."'
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