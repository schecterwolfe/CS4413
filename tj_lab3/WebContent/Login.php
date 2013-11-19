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
<?php
if(!isset($_SESSION['user'])){
?>
	<script>
	function login(){
		var field = document.forms['add_form']['username'].value;
		if(field == null || field == ""){
			alert("Login name must be specified");
			return false;
		}
	}
	</script>
	<h2>Fill in your first and last name to login</h2>
	<form name="add_form"  method="get" action="LoginUser.php" onsubmit="return login()">
	User name <input type="text" name="username"><button type="submit" value="Submit">Submit</button>
	</form>
<?php 
}else{
?>
	<h4>You are already loggin in as <?php $_SESSION['user']?>, please <a href="./Logout.php">log off</a> if you wish to log in as a different user.</h4>
<?php 
}?>
</body>
</html>