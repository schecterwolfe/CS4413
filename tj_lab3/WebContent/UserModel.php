<?php
class UserModel{
	private $db;
	private $error;
	private $result;
	private $user;
	
	public function __construct($hostname, $username, $userpass, $dbname, $user){
		$this->error = "";
		$this->user = $user;
		$hostStr = "mysql:host=$hostname;dbname=$dbname;charset=utf8";
		$this->db = new PDO ( $hostStr, $username, $userpass );
		$this->db->setAttribute ( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
		$this->db->setAttribute ( PDO::ATTR_EMULATE_PREPARES, false );
	}
	
	public function getError(){
		return $this->error;
	}
	
	public function setUser($name){
		$this->user = $name;
	}
	
	public function getCount() {
		if (! isset ($this->results ) /*or ! $this->results*/) {
			$stmt = $this->db->query ( "SELECT * FROM profentry" );
			return $stmt->rowCount ();
		} else {
			return $this->results->rowCount ();
		}
	}
	
	public function deleteALL(){
		return $this->db->query ( "DELETE FROM profentry" );
	}
	
	public function getProfile(){
		$stmt = $this->db->prepare("SELECT * FROM profentry WHERE name=?");
		$stmt->execute(array($this->user));
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );
		if($result == false){
			$this->error = "user profile does not exsist";
		}
		return $result;
	}
	
	public function getUser($name){
		$stmt = $this->db->prepare("SELECT * FROM profentry WHERE name=?");
		$stmt->execute(array($name));
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );
		if($result == false){
			$this->error = "user profile does not exsist";
		}
		return $result;
	}
	
	public function nextProfile(){
		if (!isset ( $this->results ) or ! $this->results) {
			$this->results = $this->db->query ( 'SELECT * FROM profentry ORDER BY prof_id DESC');
		}
		return $this->results->fetch ( PDO::FETCH_ASSOC );
	}
	
	/*
	public function getIcon($name){
		$stmt = $this->db->prepare("SELECT * FROM profentry WHERE name=?");
		$stmt->execute(array($name));
		$result = $stmt->fetch ( PDO::FETCH_ASSOC );
		if($result == false){
			$this->error = "user profile does not exsist";
			return $result;
		}
		
		$image = imagecreatefromjpeg("./photos/".$result['photo']);
		$thumbImage = imagecreatetruecolor(50, 50);
		imagecopyresized($thumbImage, $image, 0, 0, 0, 0, 50, 50, 100, 100);
		imagedestroy($image);
		imagejpeg($thumbImage); //you does not want to save.. just display
		//imagedestroy($thumbImage);
	}
	*/
	
	public function editProfile($vals, $file){
		$returnVals = $this->validate ($vals, $file);
		if($returnVals == 0){
			$this->error = $this->error."(0 returned on validate)";
			return false;
		}
		
		//this defaults the profile to the logged in user
		$returnVals['name'] = $this->user;
		//if this is a new entry then..
		if($this->getProfile()){
			$stmt = $this->db->prepare ("DELETE FROM profentry where NAME=?");
			if($stmt->execute(array($this->user)) === false){
				$this->error = "insert into database error".implode(', ', $this->db->errorInfo());
				return false;
			}
		}
		$stmt = $this->db->prepare ( "INSERT INTO profentry (name, email, sex, available, exp, trainer_description, photo)
			VALUES (:name, :email, :sex, :available, :exp, :trainer_description, :photo_dir)");
		if($stmt->execute($returnVals) === false){
			$this->error = "insert into database error".implode(', ', $this->db->errorInfo());
			return false;
		}
		
		
		return $returnVals;
	}
	
	public function validate($vals, $file){
		if (!is_array($vals) or !is_array($file)) {
			$this->error = "Input argument not an array";
			return 0;
		}
		
		//check if all the feilds are available
		if (!array_key_exists('name', $vals) or !array_key_exists('email', $vals) or !array_key_exists('sex', $vals)
		or !array_key_exists('available', $vals) or !array_key_exists('exp', $vals) or !array_key_exists('trainer_description', $vals)){
			$this->error = "Missing field(s) on \"add_form\" form, please check your submission<br>";
			return 0;
		}
		if(!array_key_exists('name', $file['photo'])){
			$this->error = "Missing photo name in \"add_form\"";
			return 0;
		}
			
		if(!($email_eval = trim(filter_var($vals['email'], FILTER_SANITIZE_EMAIL)))){
			$this->error = "Invalid Email<br>";
			return 0;
		}
		$name_eval = trim($vals['name']);
		if(empty($name_eval)){
			$this->error = "Name field is empty<br>";
			return 0;
		}
		
		$avail_data = implode(', ', $vals['available']);
		
		//being checking file
		$extentions = array("jpg", "jpeg");
		$ext = end(explode(".", $file['photo']['name']));
		
		if (!array_key_exists('name',$file['photo'])){
			$this->error = " missing name field on file[photo]<br>";
			return 0;
		}
		
		//check if uploaded
		if($file['photo']['size'] <= 0){
			$this->error = "Image upload error<br>";
			return 0;
		}
		//check max size
		if($file['photo']['size'] > 1048576){
			$this->error = "Image to large, must be 1mb or less<br>";
			return 0;
		}
		//check extention
		if(!(($file['photo']['type'] == "image/jpg" || $file['photo']['type'] == "image/jpeg")
				&& in_array($ext, $extentions))){
			$this->error = "Image is of wrong format (".$file['photo']['type'].")
							with extention ".$ext." != ".implode(', ',$extentions)." in (".$file['photo']['name']."), needs to be of type jpg or jpeg<br>";
			return 0;
		}
		//check if directory exist
		$dir = dirname(__FILE__).DIRECTORY_SEPARATOR."photos";
		if(!(file_exists($dir) && is_dir($dir))){
			mkdir($dir);
		}
		//check if photo already exist
		$dir .= DIRECTORY_SEPARATOR;
		if(file_exists("".$dir.$file['photo']['name'])){
			$this->error = "file name: ".$file['photo']['name']." already exist.<br>";
			return 0;
		}
		//move photo from temp file in php to "photos/"
		move_uploaded_file($file['photo']['tmp_name'], $dir.$file['photo']['name']);
		
		$newvals = array('name' => $name_eval,
				'email' => $email_eval,
				'sex' => $vals['sex'],
				'available' => $avail_data,
				'exp' => $vals['exp'],
				'trainer_description' => $vals['trainer_description'],
				'photo_dir' => $file['photo']['name']);
		return $newvals;
	}
}

?>