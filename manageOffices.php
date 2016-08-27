<?php require_once("includes/initialize.php"); ?>
<?php  
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');	

	$office_fields = Office::getOfficeFields();
	//print_r($office_fields);
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">		
	</head>
	<body>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>
		
		<?php getNavigation($user); ?>
		<div class="page-wrapper">
			<div id="officeLeft">
				<input id="searchOffice" type="text" placeholder="Search offices by" disabled="disabled"/>
				<select id="officeFields">
					<option value="all">all</option>
					<?php echo getOptions($office_fields); ?>		
				</select>
				<table id="officeContainer"></table>				
			</div>
			<div class="officeRight">			
				<table>
					<tr>
						<th colspan="2">ADD OFFICE</th>
					</tr>
					<tr>
						<td>Name: </td>
						<td><input id="name" type="text"/></td>
					</tr>
					<tr>
						<td>Address: </td>
						<td id="address">click the map for location</td>
					</tr>
					<tr>
						<td>Contact Person: </td>
						<td><input id="contact_person" type="text"/></td>
					</tr>
					<tr>
						<td>Contact No: </td>
						<td><input id="contact_no" type="text"/></td>
					</tr>
					<tr>
						<td>Click the map to plot Office Location</td>
						<td><input id="navSearchBox" type="text" placeholder="search location/address"/></td>
					</tr>
				</table>
				<div id="mapContainer"><div id="map" class="main-window"></div></div>
				<div class="actionBtnContainer">
					<input id="btnCancel" type="submit" value="CANCEL"/>
					<input id="btnAdd" type="submit" value="CREATE"/>
					<input id="btnSave" type="submit" value="SAVE" style="display:none;"/>	
				</div>
			</div>
		</div>
		
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>
		<script type="text/javascript">
			processRequest("backendprocess3.php?getOffices=true&getType=all");

			var currentOption = "all";

			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);

					if (jsonObj.Offices) {
						setupOfficeTable(jsonObj.Offices);
					}
				}
			}

			function setupOfficeTable(jsonOfficesObj) {
				var tblHeaders = ['ID', 'NAME', 'ADDRESS', 'CONTACT PERSON', 'CONTACT NO', 'LAT', 'LNG'];
				var tblRows = "<tr>";
				tblRows += getTableHeader(tblHeaders);
				tblRows += '<th colspan="2">OPTIONS</th>'
				tblRows += "</tr>";
				tblRows += tableJSON('#officeContainer', jsonOfficesObj, {edit:"VIEW AND EDIT", delete:"DELETE"});
				$('#officeContainer').append('<tbody>' + tblRows + '</tbody>');
			}

			$('#officeFields').on('change', function() {
				var selectedOption = $('#officeFields option:selected').val();
				var searchStr = $('#searchOffice').val();
				currentOption = selectedOption;
				if (selectedOption == "all") {
					$('#searchOffice').prop('disabled', true);
					processRequest("backendprocess3.php?getOffices=true&getType=all");
				} else {
					$('#searchOffice').prop('disabled', false);
					processRequest("backendprocess3.php?getOffices=true&getType=" + selectedOption + "&searchStr=" + searchStr);
				}
			});

			$('#searchOffice').on('input', function() {
				var searchVal = $('#searchOffice').val();
				processRequest("backendprocess3.php?getOffices=true&getType=" + currentOption + "&searchStr=" + searchVal);
			});

			$(document).on('click', '.optDelete', function() {
				var searchStr = $('#searchOffice').val();
				var selected_office_id = $(this).attr('data-internalid');
				var confirm_msg = "Are you sure you want to delete this office?";
				console.log(selected_office_id);
				var actionPerformed = function() {
					processPOSTRequest("backendprocess3.php", "deleteOffice=true&office_id=" + selected_office_id + "&getType=" + currentOption + "&searchStr=" + searchStr);
					$('#dialog').dialog('close');
				}
				confirm_action(confirm_msg, actionPerformed);
				return false;
			});			
		</script>		
	</body>
</html>