<?php $unread_notifs = Notification::find_all(array('key'=>'status', 'value'=>0, 'isNumeric'=>true)); ?>
<nav class="navigation">

	<ul style="float:left;">
		<li><a id="pageLogo" href="index.php">THREAT MAP</a></li>
		<?php if ($nav_has_search_box) {  ?>
			<li><input type="text" id="navSearchBox" placeholder="search address/places"/></li>
		<?php } ?>		
	</ul>	
	
	<ul>
		<li><?php echo htmlentities($user->display_name()); ?>  <span style="color: #999999;">&nbsp|</span></li>
		<li><a id="homePage" href="index.php">HOME</a></li>
		<li><a id="userPage" href="manageUsers.php">USERS</a></li>
		<li><a id="threatPage" href="manageThreats.php">THREATS</a></li>
		<li><a id="officePage" href="manageOffices.php">OFFICES</a></li>
		<li><a id="clientPage" href="manageClients.php">CLIENTS</a></li>
		<li><a id="notifPage" href="manageNotifications.php">NOTIFICATIONS 
			<?php if (count($unread_notifs) > 0) { ?>
				<div class="notifCircle"><?php echo count($unread_notifs); ?></div>
		    <?php } ?>
		</a></li>
		<li><a href="logout.php">LOGOUT</a></li>
	</ul>
	<img src="<?php echo htmlentities($user->display_picture); ?>"/>	
</nav>
<p style="clear:both;"></p>