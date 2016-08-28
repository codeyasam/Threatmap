<?php require_once('includes/initialize.php'); ?>
<?php
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
?>
<?php  
	$output = '{';

	if (isset($_GET['getClients'])) {
		$clients = new ClientUser();
		if ($_GET['getType'] == 'all') {
			$clients = ClientUser::find_all();
		} else if ($_GET['getType'] == 'full_name') {
			$searchStr = trim($_GET['searchStr']);
			$clients = ClientUser::search_by_column_array($searchStr, array('first_name', 'last_name', 'middle_name'));
		} else {
			$searchStr = trim($_GET['searchStr']);
			$clients = ClientUser::search_by_column_array($searchStr, array(trim($_GET['getType'])));
		}
		$output .= createJSONEntity("Clients", $clients, true);

		if (isset($_GET['loadPage']))
			$output .= ", " . createJSONEntity("ClientsFullDetail", $clients);
	}

	$output .= '}';
	echo $output;
?>