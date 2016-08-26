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
		if ($_POST['logout'] == 'true') {
			$output .= '"forcedLogout":"true"'  . ", ";
		}
		$allUsers = User::find_all();
		$output .= createJSONEntity("Users", $allUsers, true);
	} else if (isset($_GET['getOfficeIds'])) {
		$allOffices = Office::find_all();
		$output .= createJSONEntity("Offices", $allOffices);
	} else if (isset($_GET['hasExisting'])) {
		$result = User::check_existing($_GET['hasUsername'], "username", "username already exists");
		if (isset($_GET['user_id']) && $result) {
			$result = $_GET['user_id'] == $result->id ? false : true; 
		}
		$output .= '"hasUsername":';
		$output .= $result ? '"true"' : '"false"';
	} else if (isset($_POST['createUser'])) {
		$new_user = new User();
		$new_user->first_name = trim($_POST['first_name']);
		$new_user->middle_name = trim($_POST['middle_name']);
		$new_user->last_name = trim($_POST['last_name']);
		$new_user->address = trim($_POST['address']);
		$new_user->contact_no = trim($_POST['contact_no']);
		$new_user->office_id = trim($_POST['office_id']);
		$new_user->department = trim($_POST['department']);
		$new_user->rank = trim($_POST['rank']);
		$new_user->username = trim($_POST['username']);
		$new_user->password = md5($_POST['password']);
		$new_user->create();

		if (isset($_FILES['display_picture'])) {
			move_uploaded_file($_FILES['display_picture']['tmp_name'], "DISPLAY_PICTURES/display_picture" . $new_user->id);
			$new_user->display_picture = "DISPLAY_PICTURES/display_picture" . $new_user->id;
			$new_user->update();
		}

		$allUsers = User::find_all();
		$output .= createJSONEntity("Users", $allUsers, true);
	} else if (isset($_GET['selectUser'])) {
		$selected_user = User::find_by_id($_GET['user_id']);
		$output .= '"selectedUser" : {' . $selected_user->toJSON() . '},';
		$allOffices = Office::find_all();
		$output .= createJSONEntity("Offices", $allOffices);
	} else if (isset($_POST['editUser'])) {
		$current_user = User::find_by_id($_POST['user_id']);
		$current_user->first_name = trim($_POST['first_name']);
		$current_user->middle_name = trim($_POST['middle_name']);
		$current_user->last_name = trim($_POST['last_name']);
		$current_user->address = trim($_POST['address']);
		$current_user->contact_no = trim($_POST['contact_no']);
		$current_user->office_id = trim($_POST['office_id']);
		$current_user->department = trim($_POST['department']);
		$current_user->rank = trim($_POST['rank']);
		$current_user->username = trim($_POST['username']);
		$current_user->password = md5($_POST['password']);		
		$current_user->update();

		if (isset($_FILES['display_picture'])) {
			move_uploaded_file($_FILES['display_picture']['tmp_name'], "DISPLAY_PICTURES/display_picture" . $current_user->id);
			$current_user->display_picture = "DISPLAY_PICTURES/display_picture" . $current_user->id;
			$current_user->update();
		}

		$allUsers = User::find_all();
		$output .= createJSONEntity("Users", $allUsers, true);
	} else if (isset($_GET['searchUser'])) {
		$found_users = User::search_user_by_column_array($_GET['searchValue'], array('first_name', 'last_name', 'middle_name'));
		$output .= createJSONEntity("Users", $found_users, true);
	}

	$output .= "}";
	echo $output;
?>