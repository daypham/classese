<?php
	$filepath = realpath(dirname(__FILE__));
	include_once ($filepath.'/../lib/database.php');
	include_once ($filepath.'/../helpers/format.php');
?>
<?php
	/**
	 * 
	 */
	class product
	{
		private $db;
		private $fm;

		function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function insert_pro($data, $files){

			$proName = mysqli_real_escape_string($this->db->link, $data['proName']);
			$category = mysqli_real_escape_string($this->db->link, $data['category']);
			$brand = mysqli_real_escape_string($this->db->link, $data['brand']);
			$proDesc = mysqli_real_escape_string($this->db->link, $data['proDesc']);
			$proPrice = mysqli_real_escape_string($this->db->link, $data['proPrice']);
			$proType = mysqli_real_escape_string($this->db->link, $data['proType']);

			//Ham su ly file img
			$permited = array('jpg', 'jpeg', 'png', 'gif');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_temp = $_FILES['image']['tmp_name'];

			$div = explode('.', $file_name);
			$file_ext = strtolower(end($div));
			$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
			$uploaded_image = "uploads/".$unique_image;

			if($proName == "" || $category == "" || $brand == "" || $proDesc == "" || $proPrice == "" || $proType == "" || $file_name == ""){
				$alert = "<span class='error'>Nhập thiếu thông tin!</span>";
				return $alert;
			}else{
				move_uploaded_file($file_temp, $uploaded_image);
				$query = "INSERT INTO product(productName,catId,brandId,productDesc,productPrice,productType,productImg) VALUES('$proName','$category','$brand','$proDesc','$proPrice','$proType','$unique_image')";
				$result = $this->db->insert($query);	
				if($result){
					$alert = "<span class='sussces'>Thêm sản phẩm thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Thêm sản phẩm không thành công</span>";
					return $alert;
				}
			}


		}

		public function show_pro(){
			$query = "SELECT product.*,category.cateName,brand.brandName FROM product INNER JOIN category ON product.catId = category.cateId INNER JOIN brand ON product.brandId = brand.brandId ORDER BY productId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function update_pro($data, $files, $id){
			$proName = mysqli_real_escape_string($this->db->link, $data['proName']);
			$category = mysqli_real_escape_string($this->db->link, $data['category']);
			$brand = mysqli_real_escape_string($this->db->link, $data['brand']);
			$proDesc = mysqli_real_escape_string($this->db->link, $data['proDesc']);
			$proPrice = mysqli_real_escape_string($this->db->link, $data['proPrice']);
			$proType = mysqli_real_escape_string($this->db->link, $data['proType']);

			//Ham su ly file img
			$permited = array('jpg', 'jpeg', 'png', 'gif');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_temp = $_FILES['image']['tmp_name'];

			$div = explode('.', $file_name);
			$file_ext = strtolower(end($div));
			$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
			$uploaded_image = "uploads/".$unique_image;
			
			if($proName == "" || $category == "" || $brand == "" || $proDesc == "" || $proPrice == "" || $proType == ""){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				if(!empty($file_name)){
					//Nếu ng dùng chọn ảnh
				if($file_size > 20480){
					$alert =  "<span class='error'>Yêu cầu file dưới 2M</span>";
					return $alert;
				}elseif(in_array($file_ext, $permited) == false){
					$alert =  "<span class='error'>Bạn có thể tải lên những file: ".implode(',', $permited)."</span>";
					return $alert;

				}
					$query = "UPDATE product SET 
					productName = '$proName'
					catId = '$category',
					brandId = '$brand',
					productDesc = '$proDesc',
					productPrice = '$proPrice',
					productType = '$proType',
					productImg = '$unique_image'
					WHERE productId = '$id' ";
				}else{
					//Nếu kh chọn ảnh
					$query = "UPDATE product SET 
					productName = '$proName',
					catId = '$category',
					brandId = '$brand',
					productDesc = '$proDesc',
					productPrice = '$proPrice',
					productType = '$proType'
					WHERE productId = '$id' ";


				}

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

		public function del_pro($proId){
			$query = "DELETE FROM product WHERE productId = '$proId'";
			$result = $this->db->delete($query);
			if($result){
					$alert = "<span class='sussces'>Xóa thành công</span>";
					return $alert;
			}else{
					$alert = "<span class='error'>Xóa không thành công</span>";
					return $alert;
			}

		}

		public function getproId($proId){
			$query = "SELECT * FROM product WHERE productId = '$proId'";
			$result = $this->db->select($query);
			return $result;
		}


		//END RACKEND
		public function getpro_nb(){
			$query = "SELECT * FROM product WHERE productType = 1";
			$result = $this->db->select($query);
			return $result;
		}

		public function getpro_new(){
			$sp_tung_trang = 4;
			if(!isset($_GET['trang'])){
				$trang = 1;
			}else{
				$trang = $_GET['trang'];
			}
			$tung_trang = ($trang - 1)*$sp_tung_trang;
			$query = "SELECT * FROM product ORDER BY productId DESC LIMIT $tung_trang,$sp_tung_trang";
			$result = $this->db->select($query);
			return $result;
		}

		public function getpro_details($proId){
			$query = "SELECT product.*,category.cateName,brand.brandName FROM product INNER JOIN category ON product.catId = category.cateId INNER JOIN brand ON product.brandId = brand.brandId WHERE product.productId = $proId";
			$result = $this->db->select($query);
			return $result;
		}

		public function getLastesDell(){
			$query = "SELECT * FROM product WHERE brandId = 1 ORDER BY productId DESC LIMIT 1 ";
			$result = $this->db->select($query);
			return $result;
		}

		public function getLastesSamsung(){
			$query = "SELECT * FROM product WHERE brandId = 2 ORDER BY productId DESC LIMIT 1 ";
			$result = $this->db->select($query);
			return $result;
		}

		public function getLastesApple(){
			$query = "SELECT * FROM product WHERE brandId = 3 ORDER BY productId DESC LIMIT 1 ";
			$result = $this->db->select($query);
			return $result;
		}

		public function getLastesXiaomi(){
			$query = "SELECT * FROM product WHERE brandId = 4 ORDER BY productId DESC LIMIT 1 ";
			$result = $this->db->select($query);
			return $result;
		}

		public function add_to_compare($productId, $customer_id){
			$productId = $this->fm->validation($productId);
			$customer_id = mysqli_real_escape_string($this->db->link, $customer_id);

			$query = "SELECT * FROM product WHERE productId = $productId";
			$result = $this->db->select($query)->fetch_array();

			$proName = $result['productName'];
			$proPrice = $result['productPrice'];
			$proImg = $result['productImg'];

			$check_cart = "SELECT * FROM compare WHERE productId = '$productId' AND customerId = '$customer_id'";
			$result_check = $this->db->select($check_cart);
			if($result_check){
				$msg = "Sản phẩm đã được thêm vào so sánh rồi rồi!!!";
				return $msg;
			}else{
			$query_insert_compare = "INSERT INTO compare(customerId,productId,productName,price,img) VALUES('$customer_id','$productId','$proName','$proPrice','$proImg')";
				$insert_compare = $this->db->insert($query_insert_compare);	
				if($insert_compare){
					$alert = "<span class='sussces'>Thêm vào so sánh thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Thêm vào so sánh không thành côngg</span>";
					return $alert;
				}
			}
		}

		public function get_compare($customer_id){
			$query = "SELECT * FROM compare WHERE customerId = $customer_id ORDER BY productId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function dell_all_compare($customer_id){
			$query = "DELETE FROM compare WHERE customerId = '$customer_id'";
			$result = $this->db->delete($query);
			return $result;

		}

		public function add_to_wishlist($productId, $customer_id){
			$productId = $this->fm->validation($productId);
			$customer_id = mysqli_real_escape_string($this->db->link, $customer_id);

			$query = "SELECT * FROM product WHERE productId = $productId";
			$result = $this->db->select($query)->fetch_array();

			$proName = $result['productName'];
			$proPrice = $result['productPrice'];
			$proImg = $result['productImg'];

			$check_wish = "SELECT * FROM wishlist WHERE productId = '$productId' AND customerId = '$customer_id'";
			$result_wish = $this->db->select($check_wish);
			if($result_wish){
				$msg = "Sản phẩm đã được thêm vào yêu thích rồi!!!";
				return $msg;
			}else{
			$query_insert_wishlist = "INSERT INTO wishlist(customerId,productId,productName,price,img) VALUES('$customer_id','$productId','$proName','$proPrice','$proImg')";
				$insert_wishlist = $this->db->insert($query_insert_wishlist);	
				if($insert_wishlist){
					$alert = "<span class='sussces'>Thêm vào yêu thích thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Thêm vào yêu thích không thành côngg</span>";
					return $alert;
				}
			}
		}

		public function get_wishlist($customer_id){
			$query = "SELECT * FROM wishlist WHERE customerId = $customer_id ORDER BY productId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function del_wishlist($customer_id,$id){
			$query = "DELETE FROM wishlist WHERE customerId = '$customer_id' AND productId = '$id'";
			$result = $this->db->delete($query);
			return $result;
		}

			public function insert_slider($data, $file){
			$sliderName = mysqli_real_escape_string($this->db->link, $data['slidername']);
			$type = mysqli_real_escape_string($this->db->link, $data['type']);

			//Ham su ly file img
			$permited = array('jpg', 'jpeg', 'png', 'gif');
			$file_name = $_FILES['image']['name'];
			$file_size = $_FILES['image']['size'];
			$file_temp = $_FILES['image']['tmp_name'];

			$div = explode('.', $file_name);
			$file_ext = strtolower(end($div));
			$unique_image = substr(md5(time()), 0, 10).'.'.$file_ext;
			$uploaded_image = "uploads/".$unique_image;

			if($sliderName == ""){
				$alert = "<span class='error'>Không bỏ trống</span>";
				return $alert;
			}else{
				move_uploaded_file($file_temp, $uploaded_image);
				$query = "INSERT INTO slider(sliderName,sliderImg,type) VALUES('$sliderName','$unique_image', '$type')";
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

		public function get_slider(){
			$query = "SELECT * FROM slider ORDER BY sliderId DESC";
			$result = $this->db->select($query);
			return $result;
		}

		public function search_product($tukhoa){
			$tukhoa = $this->fm->validation($tukhoa);
			$tukhoa = mysqli_real_escape_string($this->db->link, $tukhoa);
			$query = "SELECT * FROM product WHERE productName LIKE '%$tukhoa%'";
			$result = $this->db->select($query);
			return $result;

		}

		public function get_all_product(){
			$query = "SELECT * FROM product";
			$result = $this->db->select($query);
			return $result;
		}

	}
?>