<?php require_once('includes/initialize.php'); ?>
<?php
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
	$user_fields = User::getUserFields();
	$offices = Office::find_by_sql("SELECT id, name FROM OFFICE_TB");
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">		
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
	</head>
	<body>
		<div id="userWrapper" class="page-wrapper">
		<?php getNavigation($user); ?>
			<div style="padding: 20px;">
				<input type="hidden" id="ACCOUNTID" value="<?php echo htmlentities($user->id); ?>">

				<input id="searchUser" type="text" name="" placeholder="search user by " disabled="disabled"/>
				<select id="userFields">
					<option value="all">all</option>
					<?php echo getOptions($user_fields); ?>	
				</select>
				<a id="addUser" href="" style="float:right;">+ADD NEW USER</a>			
			</div>

			<div style="padding-left: 20px; padding-right:20px;">
				<table id="userContainer">	
				</table>
			</div>

			<div id="userFormDialog" style="display: none;" title="CREATE A USER">
				<table>
				<tr>
					<td><img id="output" src="DISPLAY_PICTURES/default_avatar.png"/></td>
					<td id="picInputContainer"><input id="pic" type="file" name="img_upload" accept="image/*" onchange="loadFile(event)" /></td>
				</tr>

				<tr>
					<td>First name: </td>
					<td><input id="first_name" type="text" name="first_name"/></td>
				</tr>
				
				<tr>
					<td>Middle name: </td>
					<td><input id="middle_name" type="text" name="middle_name"/></td>
				</tr>
				<tr>
					<td>Last name: </td>
					<td><input id="last_name" type="text" name="last_name"/></td>
				</tr>
				<tr>
					<td>Address: </td>
					<td><input id="address" type="text" name="address"/></td>
				</tr>
				<tr>
					<td>Contact no: </td>	
					<td><input id="contact_no" type="text" name="contact_no"/></td>
				</tr>
				<tr>
					<td>Office: </td>
					<td><select id="office_id" name="office_id">
						<?php foreach ($offices as $key => $eachOffice): ?>
							<option value="<?php echo htmlentities($eachOffice->id); ?>"><?php echo htmlentities($eachOffice->name); ?></option>
						<?php endforeach; ?>
					</select></td>
				</tr>
				<tr>
					<td>Department: </td>
					<td><input id="department" type="text" name="department"/></td>
				</tr>
				<tr>
					<td>Rank: </td>
					<td><input id="rank" type="text" name="rank"/></td>
				</tr>
				<tr>
					<td>Username: </td>
					<td><input id="username" type="text" name="username"/></td>
				</tr>
				<tr>
					<td></td>
					<td id="usernameNotice"></td>
				</tr>			
				<tr>
					<td>Password: </td>
					<td><input id="password" type="password" name="password"/></td>
				</tr>
				<tr>
					<td>Confirm Pass: </td>
					<td><input id="confPass" type="password" name="confPass">
					</td>
				</tr>
				<tr>
					<td></td>
					<td id="passNotice"></td>
				</tr>
				</table>
			</div>

		</div>

		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>

		<script type="text/javascript">
			$('#userPage').addClass('selectedPage');
			processRequest("backendprocess.php?getUsers=true&getType=all");
			var currentOption = 'all'
			var hasUsername = false;

			var loadFile = function(event) {
			   	var output = document.getElementById('output');
			   	output.src = URL.createObjectURL(event.target.files[0]);
			   	$('#urlContent').attr('value', "");
			};

			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					//console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);

					if (jsonObj.Offices) {
						setupOfficesOption(jsonObj.Offices);
					}

					if (jsonObj.Users) {
						setupUserTable(jsonObj.Users);
					} else if (jsonObj.hasUsername) {
						if (jsonObj.hasUsername == "true") {
							hasUsername = true;
							$('#usernameNotice').text("username already exists.");
						} else {
							hasUsername = false;
							$('#usernameNotice').text("");
						}
					} else if (jsonObj.selectedUser) {
						var action_performed = function() {
							getBtnAction("EDIT", jsonObj.selectedUser.id);	
						}
						setupUserForm("EDIT", action_performed, jsonObj.selectedUser);
						$('#password').val('');
						$('#confPass').val('');
						$('#userFormDialog').dialog('open');
					}

					if (jsonObj.forcedLogout) {
						custom_alert_dialog("WARNING: You can't delete your own account.");
						//window.location = "logout.php";
					}
				}
			}

			function handleSuccessResponse(response) {
				//console.log(response);
				var jsonObj = JSON.parse(response);
				if (jsonObj.Users) {
					setupUserTable(jsonObj.Users);
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
				$('#userContainer img').css({"width":"114","height":"114"});			
			}

			$(document).on('click', '.optDelete', function() {
				//console.log("id: " + $(this).attr("data-internalId"));
				var user_id = $('#ACCOUNTID').val();
				var selected_user_id = $(this).attr("data-internalId");
				var confirm_msg = user_id == selected_user_id ? "WARNING: you can't delete your own account." : "Are you sure you want to delete this account";
				//"WARNING: you're about to delete your own account, are you sure? (Due to deletion of your account, you'll be logged out immediately and won't be able to log in again)" : "Are you sure you want to delete this account?";
				var redirect_to_logout = user_id == selected_user_id ? true : false;
				if (redirect_to_logout) { 
					custom_alert_dialog("WARNING: You can't delete your own account.");
					return false;
				}
				
				var action_performed = function() {
					$('#dialog').dialog('close');
					processPOSTRequest("backendprocess.php", "deleteAccount=true&user_id=" + selected_user_id + "&logout=" + redirect_to_logout);
				}

				confirm_action(confirm_msg, action_performed);
				return false;
			});

			$(document).on('click', '.optEdit', function() {
				var user_id = $(this).attr("data-internalid");
				$('#btnSave').attr('data-internalId', user_id);
				processRequest("backendprocess.php?selectUser=true&user_id=" +user_id);
				return false;
			});

			function setupUserForm(opt_type, action_performed, jsonUser=false) {
				$("#userFormDialog").dialog({
					width: 480,
					height: 710,
					autoOpen: false,
					modal: true,
					buttons : [{
						text: "Cancel",
					    click : function() {
					    	setupUserFormValues();
					    	$(this).dialog("close");
					    },  
					  }, {
					  	text: "CREATE",
					  	"id": "btnCreate",
					  	click: action_performed,
					  }, {
					  	text: "SAVE",
					  	"id": "btnSave",
					  	click: action_performed,
					}],
					close: function() {
						setupUserFormValues();
					}
				});

				var btnToHide = (opt_type == "CREATE") ? "#btnSave" : "#btnCreate";
				$(btnToHide).hide();

				if (jsonUser) {
					$('#userFormDialog').dialog('option', 'title', 'EDIT USER');
					setupUserFormValues(jsonUser);
				}  else {
					$('#userFormDialog').dialog('option', 'title', 'CREATE A USER');
					$('#username').attr('data-internalid', "");
					$('#password').val('');
					$('#confPass').val('');
				}
				$('#picInputContainer').html('<input id="pic" type="file" name="img_upload" accept="image/*" onchange="loadFile(event)" />');				
			}


			$('#addUser').on('click', function() {
				var action_performed = function() {
					getBtnAction("CREATE");
				}
				//processRequest("backendprocess.php?getOfficeIds=true");
				$('#office_id').prop('selectedIndex', 0);
				setupUserForm("CREATE", action_performed);
				$('#userFormDialog').dialog('open');
				return false;
			});

			$('#password').on('blur', password_comparison);

			$('#confPass').on('blur', password_comparison);

			$('#username').on('blur', function() {
				var user_id = $('#username').attr('data-internalid');
				//console.log("USER ID: " + user_id);
				if (user_id == "")
					processRequest("backendprocess.php?hasExisting=true&hasUsername=" + $('#username').val());		
				else 
					processRequest("backendprocess.php?hasExisting=true&hasUsername=" + $('#username').val() + "&user_id=" + user_id);		
			});

			function getFormData(opt_type="CREATE", user_id=false) {
				var first_name = $('#first_name').val();
				var middle_name = $('#middle_name').val();
				var last_name = $('#last_name').val();
				var address = $('#address').val();
				var contact_no = $('#contact_no').val();
				var office_id = $('#office_id option:selected').val();
				var department = $('#department').val();
				var rank = $('#rank').val();
				var username = $('#username').val();
				var password = $('#password').val();
				var confPass = $('#confPass').val();

				if (first_name == "" || middle_name == "" || last_name == "" ||
					last_name == "" || address == "" || contact_no == "" || 
					office_id == "" || department == "" || rank == "" ||
					username == "" || password == "" || confPass == "") {

					custom_alert_dialog("Fill all required fields");
					return false;
				} else if (password_comparison() === false) {
					custom_alert_dialog("Passwords don't match!");
					return false;
				} else if (hasUsername == true) {
					custom_alert_dialog("username already exists! create a new one.");
					return false;
				}	

				var display_picture = document.getElementById('pic').files[0];
				var formData = new FormData();
				if (opt_type == "CREATE") {
					formData.append('createUser', 'true');	
				} else {
					formData.append('editUser', 'true');
					formData.append('user_id', user_id)
				}
				
				formData.append('display_picture', display_picture);
				formData.append('first_name', first_name);
				formData.append('middle_name', middle_name);
				formData.append('last_name', last_name);
				formData.append('address', address);
				formData.append('contact_no', contact_no);
				formData.append('office_id', office_id);
				formData.append('department', department);
				formData.append('rank', rank);
				formData.append('username', username);
				formData.append('password', password);
				return formData;				
			}

			function getBtnAction(opt_type, user_id=false) {
				var formData = getFormData(opt_type, user_id);
				if (!formData) return;
				$.ajax({
					url: 'backendprocess.php',
					type: 'POST',
					data: formData,
					success: function(response) {
						$('#userFormDialog').dialog('close');
						handleSuccessResponse(response);
					},
					cache: false,
					contentType: false,
					processData: false
				});				
			}

			function setupOfficesOption(jsonOffices) {
				var officesOption = "";
				for (var key in jsonOffices) {
					if (jsonOffices.hasOwnProperty(key)) {
						officesOption += '<option value="' + jsonOffices[key].id + '">';
						officesOption += jsonOffices[key].name + '</option>';
					}
				}
				$('#office_id').html("");
				$('#office_id').append(officesOption);
			}

		    function password_comparison() {
		    	if ($('#confPass').val() != "" && $('#password').val() != "") {
		    		var password = $('#password').val();
		    		var confPass = $('#confPass').val();
		    		if (password != confPass) {
		    			//console.log("passwords dont match");
		    			$('#passNotice').text("passwords don't match");
		    			return false;
		    		}
		    		$('#passNotice').text("");
		    		return true;
		    	}
		    	return null;
		    }

		    function setupUserFormValues(jsonUser=false) {
				var d = new Date();
				var n = d.getTime();
		    	
		    	var pic_path = jsonUser ? jsonUser.display_picture : 'DISPLAY_PICTURES/default_avatar.png';
		    	$('#output').attr('src', pic_path + "?dummy=" + n);
		    	$('#first_name').val(jsonUser.first_name);
		    	$('#middle_name').val(jsonUser.middle_name);
		    	$('#last_name').val(jsonUser.last_name);
		    	$('#address').val(jsonUser.address);
		    	$('#contact_no').val(jsonUser.contact_no);
		    	$('#office_id').val(jsonUser.office_id);
		    	$('#department').val(jsonUser.department);
		    	$('#rank').val(jsonUser.rank);
		    	$('#username').val(jsonUser.username);

		    	$('#username').attr('data-internalid', jsonUser.id);
		    }

		    $('#searchUser').on('input', function() {
		    	//console.log('changes');
		    	var searchVal = $('#searchUser').val();
		    	processRequest("backendprocess.php?getUsers=true&searchValue=" + searchVal + "&getType=" + currentOption);
		    });

		    $('#userFields').on('change', function() {
		    	currentOption = $('#userFields option:selected').val();
		    	var searchVal = $('#searchUser').val();
		    	if (currentOption == 'all') {
		    		$('#searchUser').prop('disabled', true);
		    		$('#searchUser').val('');
		    	} else {
		    		$('#searchUser').prop('disabled', false);
		    	}
		    	processRequest('backendprocess.php?getUsers=true&searchValue=' + searchVal + "&getType=" + currentOption);
		    });

		</script>
	</body>
</html>