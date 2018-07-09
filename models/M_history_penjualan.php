<?php
	class M_history_penjualan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_penjualan($tgl_from,$tgl_to,$cari_faktur,$cari_status,$offset,$limit)
		{
			$query = $this->db->query("
					SELECT id_h_penjualan,A.id_costumer,nama_lengkap,no_faktur,biaya,bayar,tgl_h_penjualan,status_penjualan,sts_penjualan,
					CASE WHEN sts_penjualan = 'PROSES' THEN 'Proses Verifikasi'
						WHEN sts_penjualan = 'READY' THEN 'Siap dikirim/diambil'
						WHEN sts_penjualan = 'SELESAI' THEN 'Transaksi Selesai' END AS nama_status,
						   nama_penerima,nama_alamat,CONCAT(detail_alamat,' Kec.',C4.name,', ',REPLACE(C3.name,'KABUPATEN',''),' ',C2.name,' ',kodepos) AS detail_alamat
					FROM tb_h_penjualan A
					LEFT JOIN tb_costumer B
					  ON A.id_costumer = B.id_costumer
					LEFT JOIN tb_alamat C1
					  ON A.id_alamat = C1.id_alamat
					LEFT JOIN provinces C2
					  ON C1.provinsi = C2.id
					LEFT JOIN regencies C3
					  ON C1.kabkota = C3.id
					LEFT JOIN districts C4
					  ON C1.kecamatan = C4.id
					WHERE tgl_h_penjualan BETWEEN '".$tgl_from."' AND '".$tgl_to."'
					/*AND sts_penjualan LIKE '%".$cari_status."%'
					AND no_faktur LIKE '%".$cari_faktur."%'*/
					ORDER BY tgl_h_penjualan ASC
					LIMIT ".$offset.",".$limit."
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
		
		function list_d_penjualan($id_h_penjualan,$id_costumer)
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
				AND A.id_h_penjualan = '".$id_h_penjualan."'
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
		
		function count_penjualan_limit($tgl_from,$tgl_to,$cari_faktur,$cari_status)
		{
			$query = $this->db->query("SELECT COUNT(id_h_penjualan) AS JUMLAH FROM tb_h_penjualan 
									   WHERE DATE_FORMAT(tgl_h_penjualan,'%d-%m-%Y') BETWEEN '".$tgl_from."' AND '".$tgl_to."'");
			if($query->num_rows() > 0)
			{
				return $query->row();
			}
			else
			{
				return false;
			}
		}
		
		function update($id_penjualan,$status)
		{
			$query = $this->db->query("
				UPDATE tb_h_penjualan
					SET sts_penjualan = '".$status."'
				WHERE id_h_penjualan = '".$id_penjualan."'
			");
		}		
	}
?>