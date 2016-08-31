<?php require_once('includes/initialize.php'); ?>
<?php  
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
?>
<?php  
	//Main process
	$output = '{';
	if (isset($_GET['getNotifications'])) {
		$notifications = new Notification();
		if ($_GET['getType'] == 'all') {
			$notifications = Notification::find_all();
		}
		$output .= createJSONEntity("Notifs", $notifications, true);
		if (isset($_GET['loadPage'])) {
			$output .= ", " . createJSONEntity("PureNotifs", $notifications);
		}
		Notification::allStatusRead();
	}

	$output .= '}';
	echo $output;
?>
