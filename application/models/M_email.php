<?php
	class M_email extends CI_Model 
	{

		function __construct()
		{
			parent::__construct();
		}
		
		public function verifyemail($hashemail,$hashuser)  
	    {  
			/*$data = array('status' => 1);  
		    $this->db->where('md5(email)', $key);  
		    return $this->db->update('user', $data); 
			*/
			$query = $this->db->query("SELECT * FROM tb_costumer
									   WHERE MD5(email_costumer) = '".$hashemail."' AND MD5(username) = '".$hashuser."'");
			if($query->num_rows() > 0)
			{
				$query = $this->db->query("
					UPDATE tb_costumer
					SET status = '1'
					WHERE MD5(email_costumer) = '".$hashemail."' AND MD5(username) = '".$hashuser."'");
				
				if($query)
				{
					return true;
				} else {
					return false;
				}
			}
			else
			{
				return false;
			}
	    }  
		
	}
?>