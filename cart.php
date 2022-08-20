<?php
	$filepath = realpath(dirname(__FILE__));
	include_once($filepath.'/../lib/database.php');
	include_once($filepath.'/../helpers/format.php');
?>
<?php
	/**
	 * 
	 */
	class cart
	{
		private $db;
		private $fm;

		function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function add_to_cart($quantity, $id){
			$quantity = $this->fm->validation($quantity);
			$quantity = mysqli_real_escape_string($this->db->link, $quantity);
			$id = mysqli_real_escape_string($this->db->link, $id);
			$sId = session_id();

			$query = "SELECT * FROM product WHERE productId = $id";
			$result = $this->db->select($query)->fetch_array();

			$proName = $result['productName'];
			$proPrice = $result['productPrice'];
			$proImg = $result['productImg'];

			$check_cart = "SELECT * FROM cart WHERE productId = '$id' AND sId = '$sId' ";
			$result_check = $this->db->select($check_cart);
			if($result_check){
				$msg = "Sản phẩm đã được thêm vào giỏ hàng rồi!!!";
				return $msg;
			}else{
			$query_insert_cart = "INSERT INTO cart(productId,sId,productName,quantity,price,img) VALUES('$id','$sId','$proName','$quantity','$proPrice','$proImg')";
				$insert_cart = $this->db->insert($query_insert_cart);	
				if($insert_cart){
					header('Location:cart.php');
				}else{
					header('Location:404.php');
				}
			}

		}

		public function get_pro_cart(){
			$sId = session_id();
			$query = "SELECT * FROM cart WHERE sId = '$sId'";
			$result = $this->db->select($query);
			return $result;
		}

		public function update_cart($quantity, $cartId){
			$quantity = mysqli_real_escape_string($this->db->link, $quantity);
			$cartId = mysqli_real_escape_string($this->db->link, $cartId);

			$query = "UPDATE cart SET 
					quantity = '$quantity'
					WHERE cartId = '$cartId'";

			$result = $this->db->update($query);
			if($result){
				header('Location:cart.php');
			}else{
				$msg = "Cập nhật không thành công";
				return $msg;
			}

		}

		public function del_cart($id){
			$id = mysqli_real_escape_string($this->db->link, $id);
			$query = "DELETE FROM cart WHERE cartId = '$id'";
			$result = $this->db->delete($query);
			if($result){
					header('Location:cart.php');
			}else{
					$alert = "<span class='error'>Xóa không thành công</span>";
					return $alert;
			}
		}

		public function check_cart(){
			$sId = session_id();
			$query = "SELECT * FROM cart WHERE sId = '$sId'";
			$result = $this->db->select($query);
			return $result;
		}

		public function check_order($customer_id){
			$query = "SELECT * FROM payment WHERE customerId = '$customer_id'";
			$result = $this->db->select($query);
			return $result;
		}

		public function dell_all_data(){
			$sId = session_id();
			$query = "DELETE FROM cart WHERE sId = '$sId'";
			$result = $this->db->delete($query);
			return $result;
		}

		public function insert_order($customer_id){
			$sId = session_id();
			$query = "SELECT * FROM cart WHERE sId = '$sId'";
			$get_product = $this->db->select($query);
			if($get_product){
				while($result = $get_product->fetch_array()){
					$productId = $result['productId'];
					$productName = $result['productName'];
					$quantity = $result['quantity'];
					$price = $result['price'] * $quantity;
					$img = $result['img'];
					$customer_id = $customer_id;

					$query_insert_order = "INSERT INTO payment(productId,productName,customerId,quantity,price,img) VALUES('$productId','$productName','$customer_id','$quantity','$price','$img')";
					$insert_order = $this->db->insert($query_insert_order);

				}
			}

		}

		public function get_cart_ordered($customer_id){
			$query = "SELECT * FROM payment WHERE customerId = '$customer_id'";
			$result = $this->db->select($query);
			return $result;
		}

		public function get_inbox_cart(){
			$query = "SELECT * FROM payment ORDER BY date_order";
			$result = $this->db->select($query);
			return $result;
		}

		public function shiftid($id, $time, $price){
			$id = mysqli_real_escape_string($this->db->link, $id);
			$time = mysqli_real_escape_string($this->db->link, $time);
			$price = mysqli_real_escape_string($this->db->link, $price);

			$query_payment = "UPDATE payment SET 
					status = '1'
					WHERE payment.id = '$id' AND payment.date_order = '$time' AND payment.price = '$price' ";
			$result_payment = $this->db->update($query_payment);
			if($result_payment){
				$msg = "Cập thành công";
				return $msg;
			}else{
				$msg = "Cập nhật không thành công";
				return $msg;
			}


		}


	}
?>