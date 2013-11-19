<?php 
  session_start();
?>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Profile Add Confirmation</title>
</head>
<body bgcolor="E6E6FA">
<header>
<div style="text-align:center;"><img src="./content/banner.jpg" alt="banner"></div>
</header>
<div id="navBar" style="background-color:#ADD6FF; height:40px; width=100%; float:left; text-align:left;">
<?php 
	echo "Hello <a href=\"./GetUserProfile.php?NAME=".$_SESSION['user']."\">".$_SESSION['user']."</a> | ";
	echo "<a href=\"./index.php\">Home</a> | ";
	echo "<a href=\"./Logout.php\">Logout</a>";
	echo "<br><br><br><br>";
?>
</div>
<br><br>
<?php 
if(!isset($_SESSION['user'])){ ?>
	It appears as if you are not logged in, please log in to add a new trainer profile.<br>
	<a href="./Login.php">Log in here </a>
<?php 
}else{
?>
	<h1>Trainer profile confirmation</h1>

<?php 
	require_once('UserModel.php');

	$model = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);
	$values = $model->editProfile($_POST, $_FILES);
	if(!$values){
		echo "error adding entry: ".$model->getError();
		exit();
	}

	extract($values);
	echo "<h2>Added or edited profile:</h2><br>";
	echo "name: ".$name."<br>
		  	email: ".$email."<br>
		  	sex: ".$sex."<br>
		  	experience: ".$exp."<br>
		  	available: ".$available."<br>
		  	trainer description: ".$trainer_description."<br>
			Photo: ".$values['photo_dir']."<br>";
	echo "<br>Profile picture:<br><img src=\"./photos/".$values['photo_dir']."\" alt=\"".$values['photo_dir']."\">";
}
?>
</body>
</html>