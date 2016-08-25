<?php require_once('includes/initialize.php'); ?>
<?php 
	//HANDLES UNATHORIZED ACCESS
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
?>
<?php  
	//MAIN BACKEND PROCESSES
	$output = "{";
	if (isset($_GET['getLoggedInClients'])) {
		//temporary-- get all the clients just for now;
		$allClients = ClientUser::find_all();
		$output .= createJSONEntity("Clients", $allClients);
		
	} else if (isset($_GET['getUsers'])) {
		$allUsers = User::find_all();
		$output .= createJSONEntity("Users", $allUsers, true);
	} else if (isset($_POST['deleteAccount'])) {
		$user_id = $database->escape_value($_POST['user_id']);
		User::delete_by_id($user_id);
		$allUsers = User::find_all();
		$output .= createJSONEntity("Users", $allUsers, true) . ", ";
		if ($_POST['logout'] == 'true') {
			$output .= '"forcedLogout":"true"';
		}
	}

	$output .= "}";
	echo $output;
?>