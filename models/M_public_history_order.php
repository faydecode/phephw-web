<?php
	class M_public_history_order extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function get_header_pembelian($id_costumer)
		{
			$query = $this->db->query("
				SELECT id_h_penjualan,id_costumer,no_faktur,DATE_FORMAT(tgl_h_penjualan, '%d %M %Y') AS tgl_h_penjualan,
				FORMAT(biaya,'#,###') AS biaya,FORMAT(bayar,'#,###') AS bayar,sts_penjualan,COALESCE(B.nama_penerima,'') as nama_penerima,COALESCE(B.detail_alamat,'') detail_alamat
				FROM tb_h_penjualan A
				LEFT JOIN 
				(
					SELECT id_alamat,nama_penerima,nama_alamat,CONCAT(detail_alamat,' Kec.',D.name,', ',REPLACE(C.name,'KABUPATEN',''),' ',B.name,' ',kodepos) AS detail_alamat
					FROM tb_alamat A
					LEFT JOIN provinces B
					  ON A.provinsi = B.id
					LEFT JOIN regencies C
					  ON A.kabkota = C.id
					LEFT JOIN districts D
					  ON A.kecamatan = D.id
					WHERE id_costumer = '".$id_costumer."'
				) B ON A.id_alamat = B.id_alamat
				WHERE id_costumer = '".$id_costumer."'
				AND sts_penjualan <> 'SELESAI'
				ORDER BY tgl_h_penjualan ASC
			");
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		
		function get_detail_pembelian($id_costumer)
		{
			$query = $this->db->query("
				SELECT A.id_h_penjualan,B.id_produk,C.nama_produk,jumlah,satuan_jual,harga,
					   CONCAT('@',FORMAT(harga,'#,###'),' x ',jumlah) ket,FORMAT(harga*jumlah,'#,###') AS total
				FROM tb_h_penjualan A
				LEFT JOIN tb_d_penjualan B
				  ON A.id_h_penjualan = B.id_h_penjualan
				LEFT JOIN tb_produk C
				  ON B.id_produk = C.id_produk
				WHERE A.id_costumer = '".$id_costumer."'
				AND sts_penjualan <> 'SELESAI'
			");
			//AND A.id_h_penjualan = '".$id_h_penjualan."'
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
			
		}
		
		function history_header_pembelian($status,$fromdate,$todate,$id_costumer,$cari)
		{
			$query = $this->db->query("
				SELECT id_h_penjualan,id_costumer,no_faktur,DATE_FORMAT(tgl_h_penjualan, '%d %M %Y') AS tgl_h_penjualan,
				FORMAT(biaya,'#,###') AS biaya,FORMAT(bayar,'#,###') AS bayar,sts_penjualan,COALESCE(B.nama_penerima,'') as nama_penerima,COALESCE(B.detail_alamat,'') detail_alamat
				FROM tb_h_penjualan A
				LEFT JOIN 
				(
					SELECT id_alamat,nama_penerima,nama_alamat,CONCAT(detail_alamat,' Kec.',D.name,', ',REPLACE(C.name,'KABUPATEN',''),' ',B.name,' ',kodepos) AS detail_alamat
					FROM tb_alamat A
					LEFT JOIN provinces B
					  ON A.provinsi = B.id
					LEFT JOIN regencies C
					  ON A.kabkota = C.id
					LEFT JOIN districts D
					  ON A.kecamatan = D.id
					WHERE id_costumer = '".$id_costumer."'
				) B ON A.id_alamat = B.id_alamat
				WHERE id_costumer = '".$id_costumer."'
					AND DATE(A.tgl_h_penjualan) BETWEEN '".$fromdate."' AND '".$todate."'
					AND A.sts_penjualan like '%".$status."%'
					AND A.no_faktur like '%".$cari."%'
				ORDER BY tgl_h_penjualan ASC
			
			");
			
			/*SELECT id_h_penjualan,id_costumer,no_faktur,DATE_FORMAT(tgl_h_penjualan, '%d %M %Y') as tgl_h_penjualan,
				FORMAT(biaya,'#,###') as biaya,FORMAT(bayar,'#,###') as bayar,sts_penjualan,COALESCE(B.nama_penerima,'') as nama_penerima,COALESCE(B.detail_alamat,'') detail_alamat
				FROM tb_h_penjualan
				WHERE id_costumer = '".$id_costumer."'
				AND DATE(tgl_h_penjualan) BETWEEN '".$fromdate."' AND '".$todate."'
				AND sts_penjualan like '%".$status."%'
				AND no_faktur like '%".$cari."%'
				ORDER BY tgl_h_penjualan DESC
				*/
			
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function history_detail_pembelian($status,$fromdate,$todate,$id_costumer,$cari)
		{
			$query = $this->db->query("
				SELECT A.id_h_penjualan,B.id_produk,C.nama_produk,jumlah,satuan_jual,harga,
					   CONCAT('@',FORMAT(harga,'#,###'),' x ',jumlah) ket,FORMAT(harga*jumlah,'#,###') AS total
				FROM tb_h_penjualan A
				LEFT JOIN tb_d_penjualan B
				  ON A.id_h_penjualan = B.id_h_penjualan
				LEFT JOIN tb_produk C
				  ON B.id_produk = C.id_produk
				WHERE A.id_costumer = '".$id_costumer."'
				AND DATE(tgl_h_penjualan) BETWEEN '".$fromdate."' AND '".$todate."'
				AND sts_penjualan like '%".$status."%'
				AND no_faktur like '%".$cari."%'
			");
			//AND A.id_h_penjualan = '".$id_h_penjualan."'
			
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