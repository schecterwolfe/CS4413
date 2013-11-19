<?php 
  session_start();
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
$_SESSION = array();
session_destroy();
?>
<div style="text-allign:center;">
<h3>You have successfully logged out.</h3>
<img src="./content/bye.jpg" alt="bye"><br><br><br>
<a href="./index.php">Go back to main page</a>
</div>
</body>
</html>