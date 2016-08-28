<a href="index.php">LOGO</a>
<?php if ($nav_has_search_box) {  ?>
	<input type="text" id="navSearchBox" placeholder="search address/places"/>
<?php } ?>
<nav>
	<ul>
		<li><img src="<?php echo htmlentities($user->display_picture); ?>"/><?php echo htmlentities($user->display_name()); ?></li>
		<li><a href="index.php">MAIN SCREEN</a></li>
		<li><a href="manageUsers.php">MANAGE USERS</a></li>
		<li><a href="manageThreats.php">MANAGE THREATS</a></li>
		<li><a href="manageOffices.php">MANAGE OFFICES</a></li>
		<li><a href="manageClients.php">VIEW CLIENT DETAILS</a></li>
		<li><a href="logout.php">LOGOUT</a></li>
	</ul>
</nav>