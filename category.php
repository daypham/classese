<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/database.php');
	include_once ($filepath.'/../helpers/format.php');
?>
<?php
	/**
	 * 
	 */
	class category
	{
		private $db;
		private $fm;

		function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function insert_category($catName){
			$catName = $this->fm->validation($catName);

			$catName = mysqli_real_escape_string($this->db->link, $catName);

			if(empty($catName)){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				$query = "INSERT INTO category(cateName) VALUES ('$catName') ";
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

		public function show_category(){
			$query = "SELECT * FROM category ORDER BY cateId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function update_category($catName, $catId){
			$catName = $this->fm->validation($catName);

			$catName = mysqli_real_escape_string($this->db->link, $catName);
			$catId = mysqli_real_escape_string($this->db->link, $catId);
			
			if(empty($catName)){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				$query = "UPDATE category SET cateName = '$catName' WHERE cateId = '$catId' ";
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

		public function del_category($catId){
			$query = "DELETE FROM category WHERE cateId = '$catId'";
			$result = $this->db->delete($query);
			if($result){
					$alert = "<span class='sussces'>Xóa thành công</span>";
					return $alert;
			}else{
					$alert = "<span class='error'>Xóa không thành công</span>";
					return $alert;
			}

		}

		public function getcatId($catId){
			$query = "SELECT * FROM category WHERE cateId = '$catId'";
			$result = $this->db->select($query);
			return $result;
		}

		public function show_category_fontend(){
			$query = "SELECT * FROM category ORDER BY cateId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function get_product_by_cat($id){
			$query = "SELECT * FROM product, category WHERE cateId = catId AND catId = $id ORDER BY productId DESC";
			$result = $this->db->select($query);
			return $result;
		}
		

	}
?>