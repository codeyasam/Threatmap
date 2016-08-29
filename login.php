<?php require_once('includes/initialize.php'); ?>
<?php  $session->is_logged_in() ? redirect_to("index.php") : null; ?>
<?php 
	$prompt_to_user = "";
	if (isset($_POST['submit'])) {
		$username = $_POST['username'];
		$password = $_POST['password'];
		$user = User::authenticate($username, $password);
		
		if ($user) {
			$session->login($user);
			redirect_to("index.php");
		}
		$prompt_to_user = "wrong username or password.";
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
	</head>
	<body>
		<div id="loginPage" class="page-wrapper">
			<header id="loginHeader">
				<h3>THREAT MAP</h3>
			</header>

			<div id="loginContainer">
			<h3>THREAT MAP ADMIN</h3>
			<form action="login.php" method="POST" >
				<p><input type="text" name="username" placeholder="Username" /></p>
				<p><input type="password" name="password" placeholder="Password" /></p>
				<p><?php echo $prompt_to_user; ?></p>
				<input type="submit" name="submit" value="LOGIN"/>			
			</form>				
			</div>
		</div>
		<footer id="loginFooter">
				
		</footer>
	</body>
</html>