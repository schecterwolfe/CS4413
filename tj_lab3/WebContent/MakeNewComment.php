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
	<a href="./login.php">Log in here</a>
<?php 
}else{
?>

	<h1>Comment confirmation for <?php echo $_POST['user'];?></h1>
	<section>
	<?php 
	require_once 'CommentModel.php';

	$user = new CommentModel("localhost", "krobbins", "abc123", "tj_data");
	$result = $user->makeCommentForUser($_POST);
	if(!$result){
		echo $user->getError()."<br>";
		exit;
	}
	
	echo "<div><hr>";
	echo "comment: <br>";
	echo $result."<br>";
	echo "<a href=\"./GetUserProfile.php?NAME=".$_POST['user']."\">Go back to ".$_POST['user']."'s profile</a>";
	echo"</div>";
}
?>
</section>
</body>
</html>