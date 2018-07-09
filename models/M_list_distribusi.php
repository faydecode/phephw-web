<?php
	class M_list_distribusi extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		function list_distribusi($no_faktur)
		{
			$query = $this->db->query("SELECT *,'' as stat FROM tb_distribusi_order A
										LEFT JOIN tb_outlet B
										  ON A.id_outlet = B.id_outlet
										LEFT JOIN tb_h_penjualan C
										  ON A.id_h_penjualan = C.id_h_penjualan
										WHERE C.no_faktur = '".$no_faktur."'");
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