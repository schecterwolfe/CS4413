<?php
	//redirect if the user is not logged in 
  session_start();
  if(!isset($_SESSION['user'])){
  	header("locations: ./Login.php");
  	exit();
  }
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="ISO-8859-1">
<title>Edit profile for <?php echo $_SESSION['user']?></title>
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

<div id="profile_view" style="text-alight:left; width=100%">
<?php 
	require_once 'UserModel.php';

	$user = new UserModel("localhost", "krobbins", "abc123", "tj_data", $_SESSION['user']);
	$profile = $user->getProfile();
	if(!$profile){
		echo "You do not have a profile yet! please fill out the edit profile to create yours.<br>";
	}else{
		echo "<h3>Trainer profile for ".$profile['name']."</h3>";
		echo "<img src=\"./photos/".$profile['photo']."\" alt=\"".$profile['photo']."\"><br>";
		echo "email: ".$profile['email']."<br>Gender: ".$profile['sex']."<br>";
		echo "availability: ".$profile['available']."<br>Experience: ".$profile['exp']."<br>";
		echo "trainer description:<br>".$profile['trainer_description']."<br>";
	}
?>
</div>

<script type="text/javascript">
	function profileEditValidate(){
		var x = document.forms['add_form']['name'].value;
		var errorMsg = "";
		
		if(x == null ||x == ""){
			errorMsg = errorMsg.concat("Name field is blank, it must be specified\n");
		}


		x = document.forms['add_form']['photo'].value;
		var ext = x.substring(x.lastIndexOf('.')+1);
		if(ext.toUpperCase() != "JPG" && ext.toUpperCase() != "JPEG"){
			errorMsg = errorMsg.concat("Invaid picture format, must be of type jpg or jpeg\n");
		}

		x = document.forms['add_form']['email'].value;
		var atpos = x.indexOf("@");
		var dotpos = x.lastIndexOf(".");
		if(atpos<1 || dotpos<atpos+2 || dotpos+2>=x.length){
			errorMsg = errorMsg.concat("Not a valid E-mail address\n");
		}
		
		x = document.getElementsByName('sex');
		if(!x[0].checked && !x[1].checked){
			errorMsg = errorMsg.concat("please select a gender\n");
		}
				
		x = document.getElementsByName("available[]");
		if(!x[0].checked && !x[1].checked && !x[2].checked && !x[3].checked && !x[4].checked && !x[5].checked && !x[6].checked){
			errorMsg = errorMsg.concat("please select at least one day available\n");
		}

		x = document.forms['add_form']['exp'].value;
		if(x == null || x == ""){
			errorMsg = errorMsg.concat("exp field must be specified\n");
		}
		
		x = document.forms['add_form']['trainer_description'].value;
		if(x == null || x == ""){
			errorMsg = errorMsg.concat("Must have a description\n");
		}

		if(errorMsg != ""){
			alert(errorMsg);
			return false;
		}
	}
</script>

<br>
<br>
<br>
<div>
<h3>Edit profile</h3>
<form name="add_form"  method="post" action="AddNewProfileModel.php" enctype="multipart/form-data" onsubmit="return profileEditValidate(this)">
Trainer name <input type="text" name="name" value="<?php echo $profile['name']?>"><br>
(must select an profile picture every time you edit or create your profile)
Add Trainer Photo <input type="file" name="photo" id="photo"><br>
Trainer email <input type="email" name="email" value="<?php echo $profile['email']?>"><br>
Gender <input type="radio" name="sex" value="male">Male      <input type="radio" name="sex" value="female">Female<br>
Days Available<br>
<input type="checkbox" name="available[]" value="mon">Monday     
<input type="checkbox" name="available[]" value="tue">Tuesday     
<input type="checkbox" name="available[]" value="wed">Wednesday     
<input type="checkbox" name="available[]" value="thr">Thursday     
<input type="checkbox" name="available[]" value="fri">Friday     
<input type="checkbox" name="available[]" value="sat">Saturday     
<input type="checkbox" name="available[]" value="sun">Sunday<br>
Experience<br>
<select name="exp">
<option value="0-.5">0 - 6 months</option>
<option value=".5-1">6 months - 1 year</option>
<option value="1-3">1 - 3 years</option>
<option value="3-7">3 - 7 years</option>
<option value="7+">7+  years</option>
</select><br>
Additional Information<br>
<textarea name="trainer_description" rows="20" cols="80" ><?php echo $profile['trainer_description']?></textarea><br>
<button type="submit" value="Submit">Submit</button>
<button type="reset" value="Reset">Reset</button>
</form>
</div>

</body>
</html>