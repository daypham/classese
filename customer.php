<?php
	$filepath = realpath(dirname(__FILE__));
	include_once($filepath.'/../lib/database.php');
	include_once($filepath.'/../helpers/format.php');
?>
<?php
	/**
	 * 
	 */
	class customer
	{
		private $db;
		private $fm;

		function __construct()
		{
			$this->db = new Database();
			$this->fm = new Format();
		}

		public function insert_customer($data){
			$name = mysqli_real_escape_string($this->db->link, $data['name']);
			$city = mysqli_real_escape_string($this->db->link, $data['city']);
			$zipcode = mysqli_real_escape_string($this->db->link, $data['zipcode']);
			$email = mysqli_real_escape_string($this->db->link, $data['email']);
			$address = mysqli_real_escape_string($this->db->link, $data['address']);
			$country = mysqli_real_escape_string($this->db->link, $data['country']);
			$phone = mysqli_real_escape_string($this->db->link, $data['phone']);
			$password = mysqli_real_escape_string($this->db->link, md5($data['password']));

			if($name == "" || $city == "" || $zipcode == "" || $email == "" || $address == "" || $country == "" || $phone == "" || $password ==""){
				$alert = "<span class='error'>Nhập thiếu thông tin!</span>";
				return $alert;
			}else{
				$check_email = "SELECT * FROM customer WHERE email = '$email'";
				$result_check_email = $this->db->select($check_email);
				if($result_check_email){
					$alert = "<span class='error'>Email đã tồn tại rồi!!!</span>";
					return $alert;
				}else{
					$query = "INSERT INTO customer(name,city,zipcode,email,address,country,phone,password) VALUES('$name','$city','$zipcode','$email','$address','$country','$phone','$password')";
					$result = $this->db->insert($query);
					if($result){
					$alert = "<span class='sussces'>Đăng ký thành công</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Đăng ký không thành công</span>";
					return $alert;
				}
				}

			}

			
		}

		public function login_customer($data){
			$email = mysqli_real_escape_string($this->db->link, $data['email']);
			$password = mysqli_real_escape_string($this->db->link, md5($data['password']));
			if($email == "" || $password == ""){
				$alert = "<span class='error'>Nhập thiếu thông tin!</span>";
				return $alert;
			}else{
				$check_login = "SELECT * FROM customer WHERE email = '$email' AND password = '$password' LIMIT 1";
				$result_check_login = $this->db->select($check_login);
				if($result_check_login){
					$value = $result_check_login->fetch_array();
					Session::set('customer_login', true);
					Session::set('customer_id', $value['id']);
					Session::set('customer_name', $value['name']);
					header('Location:order.php');

				}else{
					$alert = "<span class='error'>Email hoặc password bị sai!!!</span>";
					return $alert;
				}
				}

			}

		public function show_customer($id){
			$check_customer = "SELECT * FROM customer WHERE id = '$id' LIMIT 1";
				$result_customer = $this->db->select($check_customer);
			return $result_customer;
		}

		public function update_customer($data, $id){
			$name = mysqli_real_escape_string($this->db->link, $data['name']);
			$email = mysqli_real_escape_string($this->db->link, $data['email']);
			$address = mysqli_real_escape_string($this->db->link, $data['address']);
			$phone = mysqli_real_escape_string($this->db->link, $data['phone']);

			if($name == "" || $email == "" || $address == "" || $phone == ""){
				$alert = "<span class='error'>Nhập thiếu thông tin!</span>";
				return $alert;
			}else{
				$query = "UPDATE customer SET name = '$name',email = '$email',address = '$address',phone = '$phone' WHERE id = '$id'";
				$result = $this->db->update($query);
				if($result){
				$alert = "<span class='sussces'>Cập nhật thành công!!</span>";
					return $alert;
				}else{
					$alert = "<span class='error'>Cập nhật không thành công!!</span>";
					return $alert;
				}
				}

			}

		}
?>