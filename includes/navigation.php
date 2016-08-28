<a href="index.php">LOGO</a>
<?php if ($nav_has_search_box) {  ?>
	<input type="text" id="navSearchBox" placeholder="search address/places"/>
<?php } ?>
<nav>
	<ul>
		<li><img src="<?php echo htmlentities($user->display_picture); ?>"/><?php echo htmlentities($user->display_name()); ?></li>
		<li><a href="index.php">HOME</a></li>
		<li><a href="manageUsers.php">USERS</a></li>
		<li><a href="manageThreats.php">THREATS</a></li>
		<li><a href="manageOffices.php">OFFICES</a></li>
		<li><a href="manageClients.php">CLIENTS</a></li>
		<li><a href="manageNotifications.php">NOTIFICATIONS</a></li>
		<li><a href="logout.php">LOGOUT</a></li>
	</ul>
</nav>