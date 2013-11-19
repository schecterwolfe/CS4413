<?php 
  session_start();
  if(!isset($_SESSION['user'])){
  	//header("locations: index.php");
  	//exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Login in page</title>
</head>
<body bgcolor="E6E6FA">
<header>
<div style="text-align:center;"><img src="./content/banner.jpg" alt="banner"></div>
</header>
<div style="text-align:center;">
<?php
if(!isset($_SESSION['user'])){
	if(!empty($_GET['username'])){
		$_SESSION['user'] = $_GET['username'];
		echo "<h3>You are now logged in as ".$_SESSION['user']."</h3>";
		echo "<img src=\"./content/wink.png\" alt=\"wink\"><br><br><br>";
		
		require_once 'UserModel.php';
		
		$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);
		$profile = $user->getProfile();
		if(!$profile){		
			echo "<h3>You are a new user, please <a href=\"EditProfile.php\">click here</a> to sign-up your profile if you are a trainer</h3>";
		}
	}else{
		echo "ERROR: username field is empty<br>please try to <a href=\"./Login.php\">login</a> again.<br>";
	} 
}else{
	echo "you are already logged in as ".$_SESSION['user']." please <a href=\"./Logout.php\">log out</a>.<br>";
}?>
<a href="./index.php">Main page</a>
</div>
</body>
</html>