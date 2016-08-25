<?php require_once('includes/initialize.php'); ?>
<?php
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;

	if (!$user) redirect_to('login.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">
	</head>
	<body>
		<?php getNavigation($user); ?>
		<input type="hidden" id="ACCOUNTID" value="<?php echo htmlentities($user->id); ?>">
		<table id="userContainer">	
		</table>

		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>

		<script type="text/javascript">
			processRequest("backendprocess.php?getUsers=true");

			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);
					if (jsonObj.Users) {
						setupUserTable(jsonObj.Users);
					}

					if (jsonObj.forcedLogout) {
						window.location = "logout.php";
					}
				}
			}

			function setupUserTable (jsonUsersObj) {
				var tblRows = "<tr>";
				tblRows += "<th>ID</th>";
				tblRows += "<th>PICTURE</th>";
				tblRows += "<th>FULL NAME</th>";	
				tblRows += "<th>ADDRESS</th>";
				tblRows += "<th>CONTACT NO</th>";
				tblRows += "<th>OFFICE NAME</th>";
				tblRows += "<th>DEPARTMENT</th>";
				tblRows += "<th>RANK</th>";
				tblRows += "<th>USERNAME</th>";
				tblRows += "<th>PASSWORD</th>";
				tblRows += '<th colspan="2">OPTIONS</th></tr>';
				tblRows += tableJSON('#userContainer', jsonUsersObj);
				$('#userContainer').append('<tbody>' + tblRows + '</tbody>');				
			}

			$(document).on('click', '.optDelete', function() {
				console.log("id: " + $(this).attr("data-internalId"));
				var user_id = $('#ACCOUNTID').val();
				var selected_user_id = $(this).attr("data-internalId");
				var confirm_msg = user_id == selected_user_id ? "WARNING: you're about to delete your own account, are you sure? (Due to deletion of your account, you'll be logged out immediately and won't be able to log in again)" : "Are you sure you want to delete this account?";
				var redirect_to_logout = user_id == selected_user_id ? true : false;
				var action_performed = function() {
					$('#dialog').dialog('close');
					processPOSTRequest("backendprocess.php", "deleteAccount=true&user_id=" + selected_user_id + "&logout=" + redirect_to_logout);
				}

				confirm_action(confirm_msg, action_performed);
				return false;
			});
		</script>
	</body>
</html>