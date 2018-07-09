<?php
	class M_d_penerimaan extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_d_pembelian_produk($id_h_penerimaan,$cari,$limit,$offset)
		{
			$query = $this->db->query("	
							SELECT AA.*,(AA.harga_ori - AA.GET_DISKON) AS harga FROM
							(
								SELECT A.id_h_pembelian,A.id_d_pembelian,A.id_produk,A.jumlah,A.harga AS harga_ori,A.kode_satuan,A.nama_satuan,A.diskon,A.optr_diskon
									,IF(A.optr_diskon ='%', ((A.harga/100)*A.diskon), (A.diskon)) AS GET_DISKON
									,B.NO_PO,B.nama_supplier
									,C.kode_produk
									,C.nama_produk
									,C.kode_satuan_ori
									,COALESCE(D.SUDAH_TERIMA,0) AS SUDAH_TERIMA
									,(A.jumlah - COALESCE(D.SUDAH_TERIMA,0)) AS SISA
								FROM tb_d_pembelian AS A
								INNER JOIN 
								(
									SELECT DISTINCT A.id_h_pembelian,CONCAT('PO/',YEAR(A.tgl_h_pembelian),'/',MONTH(A.tgl_h_pembelian),'/'
														,UCASE(REPLACE(TO_BASE64(A.id_h_pembelian),'=',''))
														,'/',A.id_h_pembelian) AS NO_PO, B.nama_supplier
														FROM tb_h_pembelian AS A
														INNER JOIN tb_supplier AS B
														ON A.id_supplier = B.id_supplier
								) AS B ON A.id_h_pembelian = B.id_h_pembelian
								INNER JOIN 
								(
									SELECT A.id_produk,A.kode_produk,A.nama_produk,COALESCE(B.kode_satuan,'') AS kode_satuan_ori FROM tb_produk AS A
									LEFT JOIN tb_satuan AS B ON A.id_satuan = B.id_satuan
								)AS C ON A.id_produk = C.id_produk
								LEFT JOIN
								(
									SELECT B.id_h_pembelian,B.id_d_pembelian,SUM(B.diterima) AS SUDAH_TERIMA 
									FROM tb_h_penerimaan AS A
									INNER JOIN tb_d_penerimaan AS B ON A.id_h_penerimaan = B.id_h_penerimaan
									GROUP BY B.id_h_pembelian,B.id_d_pembelian
								) AS D ON A.id_d_pembelian = D.id_d_pembelian AND A.id_h_pembelian = D.id_h_pembelian
							) AS AA
							WHERE SISA > 0
							".$cari." ORDER BY nama_produk ASC LIMIT ".$offset.",".$limit);
							
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function list_d_penerimaan($id_h_penerimaan,$cari)
		{
			$query = $this->db->query("	
							SELECT A.id_d_penerimaan,A.id_h_penerimaan,A.id_h_pembelian,A.id_d_pembelian,A.id_produk,A.diterima,A.konversi,A.kode_satuan,A.nama_satuan,A.harga_beli,A.harga_konversi
							,B.kode_produk,B.nama_produk,B.kode_satuan AS kode_satuan_ori,COALESCE(C.JUMLAH_RETUR,0) AS JUMLAH_RETUR	
							FROM tb_d_penerimaan AS A
							LEFT JOIN 
							(
								SELECT  A.id_produk, A.nama_produk,A.kode_produk,B.kode_satuan 
								FROM tb_produk AS A
								LEFT JOIN tb_satuan AS B ON A.id_satuan = B.id_satuan
							) AS B ON A.id_produk = B.id_produk
							LEFT JOIN
							(
								SELECT id_d_penerimaan,id_produk,SUM(jumlah) AS JUMLAH_RETUR FROM tb_retur GROUP BY id_d_penerimaan,id_produk
							) AS C ON A.id_d_penerimaan = C.id_d_penerimaan AND A.id_produk = C.id_produk
							WHERE id_h_penerimaan = ".$id_h_penerimaan."
							".$cari." ORDER BY B.nama_produk ASC");
							
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		function simpan($id_h_penerimaan,$id_h_pembelian,$id_d_pembelian,$id_produk,$diterima,$konversi,$kode_satuan,$nama_satuan,$harga_beli,$harga_konversi,$id_user,$kode_kantor)
		{
			$jam = date('Y-m-d H:i:s'); 
			$data = array
			(
			   'id_h_penerimaan' => $id_h_penerimaan,
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_d_pembelian' => $id_d_pembelian,
			   'id_produk' => $id_produk,
			   'diterima' => $diterima,
			   'konversi' => $konversi,
			   'kode_satuan' => $kode_satuan,
			   'nama_satuan' => $nama_satuan,
			   'harga_beli' => $harga_beli,
			   'harga_konversi' => $harga_konversi,
			   'tgl_ins' => $jam,
			   'tgl_updt' => $jam,
			   'user_updt' => $id_user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_d_penerimaan', $data); 
		}
		
		function edit($id_d_penerimaan,$id_h_penerimaan,$id_h_pembelian,$id_d_pembelian,$id_produk,$diterima,$konversi,$kode_satuan,$nama_satuan,$harga_beli,$harga_konversi,$id_user)
		{
			$jam = date('Y-m-d H:i:s');
			$data = array
			(
			   'id_h_penerimaan' => $id_h_penerimaan,
			   'id_h_pembelian' => $id_h_pembelian,
			   'id_d_pembelian' => $id_d_pembelian,
			   'id_produk' => $id_produk,
			   'diterima' => $diterima,
			   'konversi' => $konversi,
			   'kode_satuan' => $kode_satuan,
			   'nama_satuan' => $nama_satuan,
			   'harga_beli' => $harga_beli,
			   'harga_konversi' => $harga_konversi,
			   'tgl_updt' => $jam,
			   'user_updt' => $id_user
			);
			
			$this->db->where('id_d_penerimaan', $id_d_penerimaan);
			$this->db->update('tb_d_penerimaan', $data);
		}
		
		function edit_perubahan_d_pembelian($id_d_penerimaan,$harga_beli,$harga_konversi)
		{
			$jam = date('Y-m-d H:i:s');
			$data = array
			(
			   'harga_beli' => $harga_beli,
			   'harga_konversi' => $harga_konversi
			);
			
			$this->db->where('id_d_penerimaan', $id_d_penerimaan);
			$this->db->update('tb_d_penerimaan', $data);
		}
		
		function hapus($id)
		{
			$this->db->query('DELETE FROM tb_d_penerimaan WHERE id_d_penerimaan = '.$id.'');
		}
		
		function get_d_penerimaan_num_rows($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_d_penerimaan', array($berdasarkan => $cari));
            if($query->num_rows() > 0)
            {
                return $query->num_rows();
            }
            else
            {
                return false;
            }
        }
		
		function get_d_penerimaan($berdasarkan,$cari)
        {
            $query = $this->db->get_where('tb_d_penerimaan', array($berdasarkan => $cari));
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