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
	</head>
	<body>
		<header>
			
		</header>
		<form action="login.php" method="POST">
			<p><input type="text" name="username" placeholder="Username" /></p>
			<p><input type="password" name="password" placeholder="Password" /></p>
			<p><?php echo $prompt_to_user; ?></p>
			<input type="submit" name="submit" value="SIGN IN"/>			
		</form>

	</body>
</html>