<?php
	session_start(); 
?>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Add new trainer profile</title>
</head> 
<body bgcolor="E6E6FA">
<header>
<div style="text-align:center;"><img src="./content/banner.jpg" alt="banner"></div>
</header>
<table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ADD6FF"class="top-menu">
	<tr>
		<td class="header_menu">
			<ul class="navLinks">
				<?php require_once 'UserModel.php'; 
				if(isset($_SESSION['user'])){
					echo "Hello <a href=\"./GetUserProfile.php?NAME=".$_SESSION['user']."\">".$_SESSION['user']."</a> | ";
					echo "<a href=\"./EditProfile.php\">Edit profile</a> | ";
					echo "<a href=\"./Logout.php\">Logout</a>";
					
					$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);
				}else{
					echo "Hello Guest! <a href=\"./Login.php\">Please login</a>";
					$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", "");
				}?>
			</ul>
		</td>
	</tr>	
</table>

<div id="PannelLeft" style="background-color:#CCE6FF; height:300px; width:100px; float:left;">
	<b>Profiles</b><br>
	<?php 
	if(isset($_SESSION['user'])){
		echo $_SESSION['user'].":<br>";
		$userProfile = $user->getProfile();
		echo "<img src=\"./photos/".$userProfile['photo']."\" style=\"height:50; width:50;\"><br>";
	}else{
		echo "Guest<br>";
	}
	?> 
	<a href="GetAllProfiles.php">browse all profiles</a><br><br>
	<form name="find_profile" method="get" action="GetUserProfile.php" onsubmit="return validate()">
		Get profile for<input type="text" name="NAME" size="1"><button type="submit" value="Submit">Submit</button>
	</form>
	<script type="text/javascript">
		function validate(){
			var x = document.forms['find_profile']['NAME'].value;		
			if(x == null ||x == ""){
				alert("user name field is blank, it must be specified\n");
				return false;
			}
			return true;
		}
	</script>
</div>

<div id="MainContent" style="background-color:#CCE0FF; height:200px; width:100%; float:left;">
	<h1>Welcome to Canine Solutions!</h1></b>
	Here at CS we provide a medium through wich dog owners can seek quality canine training and etiquette from our
	community of canine trainers. Browse our database of registered canine trainers for one that suits your needs.
	Trainers of all types and locations are encouraged to register as will in order to further your clientele reach.
</div>

<div id="RecentTrainers">
<h3>Recently added trainers</h3>
<?php 
if(($recentUser = $user->nextProfile()) != false){
	echo "<a href=\"./GetUserProfile.php?NAME=".$recentUser['name']."\">
		<img src=\"./photos/".$recentUser['photo']."\" style=\"height:50; width:50;\">".$recentUser['name']."
		</a><br> ";
}
if(($recentUser = $user->nextProfile()) != false){
	echo "<a href=\"./GetUserProfile.php?NAME=".$recentUser['name']."\">
		<img src=\"./photos/".$recentUser['photo']."\" style=\"height:50; width:50;\">".$recentUser['name']."
		</a><br>";
}
if(($recentUser = $user->nextProfile()) != false){
	echo "<a href=\"./GetUserProfile.php?NAME=".$recentUser['name']."\">
		<img src=\"./photos/".$recentUser['photo']."\" style=\"height:50; width:50;\">".$recentUser['name']."
		</a><br>";
}
?>
</div>
</body>
</html>