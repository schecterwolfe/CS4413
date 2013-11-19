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

	<h1>Trainer profile for <?php echo $_GET['NAME'];?></h1>

	<section>
	<?php 
	require_once 'UserModel.php';

	$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);
	$profile = $user->getUser($_GET['NAME']);
	if(!$profile){
		echo "Profile for ".$_GET['NAME'] ." does not exist<br>";
		exit(1);
	}
	
	echo "<div><hr>";
	echo "<img src=\"./photos/".$profile['photo']."\" alt=\"".$profile['photo']."\"><br>";
	echo "email: ".$profile['email']."<br>Gender: ".$profile['sex']."<br>";
	echo "availability: ".$profile['available']."<br>Experience: ".$profile['exp']."<br>";
	echo "trainer description:<br>".$profile['trainer_description']."<br>";
	echo"</div>";
}
?>
<hr>
<div>
<h3>Comments</h3>
Recent comments:<br>
<?php 
require_once 'CommentModel.php';

$commentObj = new CommentModel("localhost", "krobbins", "abc123", "tj_data");
if(($comment = $commentObj->getCommentsForUser($_GET['NAME'])) != false){
	echo "comment from ".$comment['owner'].":<br>";
	echo $comment['comment']."<br><br>";
}
if(($comment = $commentObj->getCommentsForUser($_GET['NAME'])) != false){
	echo "comment from ".$comment['owner'].":<br>";
	echo $comment['comment']."<br><br>";
}
?>

<script type="text/javascript">
function validate(){
	var x = document.forms['new_comment']['comment'].value;		
	if(x == null ||x == ""){
		alert("Comment field is blank, it must be specified\n");
		return false;
	}
	return true;
}
</script>

<a href="./GetAllComments.php?USER=<?php echo $_GET['NAME']?>">Get all comments for this user</a><br><br>
Make a comment to <?php echo $_GET['NAME']?>:<br>
<form name="new_comment" method="post" action="MakeNewComment.php" onsubmit="return validate()">
<input type="hidden" name="user" value="<?php echo $_GET['NAME']?>">
<input type="hidden" name="owner" value="<?php echo $_SESSION['user']?>">
<textarea name="comment" rows="20" cols="80" ></textarea>
<button type="submit" value="Submit">Submit</button>
<button type="reset" value="Reset">Reset</button>
</form>
</div>
</section>
</body>
</html>