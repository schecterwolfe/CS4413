<?php 
  session_start();
?>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Trainer comments</title>
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
	<a href="./login.php">Log in here</a>
<?php 
}else{
?>

	<h1>Comments for Trainer <?php echo $_GET['USER']?></h1>

	<section>
<?php
	require_once 'CommentModel.php';

	$commentObj = new CommentModel("localhost", "krobbins", "abc123", "tj_data");
	while(($comment = $commentObj->getCommentsForUser($_GET['USER'])) != false){
		echo "comment from ".$comment['owner'].":<br>";
		echo $comment['comment']."<br><br>";
	}
}
?>
</section>
</body>
</html>