<?php 
  session_start();
?>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Trainer profile</title>
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
	It appears as if you are not logged in, please log in to see a profile<br>
	<a href="./Login.php">Log in here</a>
<?php 
}else{
?>
<section>
<h1>Viewing all trainer profiles</h1>
<br>
<?php 
require_once 'UserModel.php';
$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);

while($current = $user->nextProfile()){
	echo "<div><hr>";
	echo "<a href=\"./GetUserProfile.php?NAME=".$current['name']."\">
		<img src=\"./photos/".$current['photo']."\" style=\"height:50; width:50;\"> , ".$current['name']."
		</a>";
	echo ", experience".$current['exp']." years<br>";
	echo"</div>";
}

}?>
</section>
</body>
</html>