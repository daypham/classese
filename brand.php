<?php
	$filepath = realpath(dirname(__FILE__));
	include_once($filepath.'/../lib/database.php');
	include_once($filepath.'/../helpers/format.php');
?>
<?php
	/**
	 * 
	 */
	class brand
	{
		private $db;
		private $fm;

		function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function insert_brand($brandName){
			$brandName = $this->fm->validation($brandName);

			$brandName = mysqli_real_escape_string($this->db->link, $brandName);

			if(empty($brandName)){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				$query = "INSERT INTO brand(brandName) VALUES ('$brandName') ";
				$result = $this->db->insert($query);
				if($result){
					$alert = "<span class='sussces'>Thêm thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Thêm không thành công</span>";
					return $alert;
				}
			}


		}

		public function show_brand(){
			$query = "SELECT * FROM brand ORDER BY brandId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function update_brand($brandName, $brandId){
			$brandName = $this->fm->validation($brandName);

			$brandName = mysqli_real_escape_string($this->db->link, $brandName);
			$brandId = mysqli_real_escape_string($this->db->link, $brandId);
			
			if(empty($brandName)){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				$query = "UPDATE brand SET brandName = '$brandName' WHERE brandId = '$brandId' ";
				$result = $this->db->update($query);
				if($result){
					$alert = "<span class='sussces'>Cập nhật thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Cập nhật không thành công</span>";
					return $alert;
				}
			}
		}

		public function del_brand($brandId){
			$query = "DELETE FROM brand WHERE brandId = '$brandId'";
			$result = $this->db->delete($query);
			if($result){
					$alert = "<span class='sussces'>Xóa thành công</span>";
					return $alert;
			}else{
					$alert = "<span class='error'>Xóa không thành công</span>";
					return $alert;
			}

		}

		public function getbrandId($brandId){
			$query = "SELECT * FROM brand WHERE brandId = '$brandId'";
			$result = $this->db->select($query);
			return $result;
		}





	}
?>