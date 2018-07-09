<?php
	class M_harga_member extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		
		function list_harga_member_limit($kode_kantor,$cari,$offset,$limit)
		{
			$query = $this->db->query("CALL SP_PRODUKHARGASATUANCOSTUMER('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
			if($query->num_rows() > 0)
			{
				return $query;
			}
			else
			{
				return false;
			}
		}
		
		/*function list_field($kode_kantor,$cari,$offset,$limit)
		{
			$query = $this->db->field_data("CALL SP_PRODUKHARGASATUANCOSTUMER('". $kode_kantor ."','". $cari ."',". $offset .",". $limit .")");
			
			return $query;
		}*/
		

		
		function simpan($id_produk,
						$id_satuan,
						$id_kat_member,
						$harga,
						$besar_persen,
						$ket,
						$user,
						$kode_kantor)
		{
			
			$jam = date('Y-m-d H:i:s'); 
			
			$data = array
			(
			   'id_produk' => $id_produk,
			   'id_satuan' => $id_satuan,
			   'id_costumer' => $id_kat_member,
			   'harga' => $harga,
			   'besar_prsn' => $besar_persen,
			   'optr_prsn' => '%',
			   'ket' => $ket,
			   'tgl_ins' => $jam,
			   'user_updt' => $user,
			   'kode_kantor' => $kode_kantor
			);

			$this->db->insert('tb_produk_harga_satuan_costumer', $data);  
				
		}
		
		function edit(
					$id_phsc,
					$id_produk,
					$id_satuan,
					$id_kat_member,
					$harga,
					$besar_persen,
					$ket,
					$user)
		{	
			$query ="
				UPDATE tb_produk_harga_satuan_costumer
				SET id_produk = '".$id_produk."',
				  id_satuan = '".$id_satuan."',
				  id_costumer = '".$id_kat_member."',
				  harga = '".$harga."',
				  besar_prsn = '".$besar_persen."',
				  ket = '".$ket."',
				  tgl_updt = now(),
				  user_updt = '".$user."'
				WHERE id_phsc = '".$id_phsc."'
					AND kode_kantor = '".$this->session->userdata('ses_kode_kantor')."';
			";
			
			$this->db->query($query);
		}
		
	}
?>