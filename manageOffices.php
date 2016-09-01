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
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">	
		<link rel="stylesheet" type="text/css" href="css/main.css"/>	
	</head>
	<body>
		<div class="page-wrapper">
			<?php getNavigation($user); ?>
			<div id="officeWrapper">
				<div id="officeLeft">
					<div class="officeUpper">
						<input id="searchOffice" type="text" placeholder="Search offices by" disabled="disabled"/>
						<select id="officeFields">
							<option value="all">all</option>
							<?php echo getOptions($office_fields); ?>		
						</select>
					</div>
					<table id="officeContainer"></table>				
				</div>
				<div class="officeRight">			
					<table>
						<tr>
							<th colspan="2" class="officeUpper">ADD OFFICE</th>
						</tr>
						<tr>
							<td>Name: </td>
							<td><input id="name" type="text"/></td>
						</tr>
						<tr>
							<td>Address: </td>
							<td id="address">click the map for location assignment</td>
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
					<div class="mapContainer"><div id="map" class="main-window"></div></div>
					<hr/>
					<div class="actionBtnContainer">
						<input id="btnCancel" type="submit" value="CANCEL" style="display:none;"/>
						<input id="btnAdd" type="submit" value="CREATE"/>
						<input id="btnSave" type="submit" value="SAVE" style="display:none;"/>	
					</div>
				</div>
			</div>
		</div>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>		
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>
		<script type="text/javascript">
			$('#officePage').addClass('selectedPage');
			markerOptions.animation = google.maps.Animation.DROP;
			markerOptions.draggable = true;

			var marker;		
			processRequest("backendprocess3.php?getOffices=true&getType=all");

			var currentOption = "all";
			var officeObj = {id:"",name:"",contact_person:"",contact_no:"",address:"",municipality:"",lat:"",lng:""};

			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);

					if (jsonObj.Offices) {
						setupOfficeTable(jsonObj.Offices);
					} 

					if (jsonObj.createdOffice) {
						$('#searchOffice').val('');
						$('#searchOffice').prop('disabled', true);
						$('#officeFields').val('all');
						custom_alert_dialog("Successfully created an office.");
						officeObj = {id:"",name:"",contact_person:"",contact_no:"",address:"",municipality:"",lat:"",lng:""};
						setOfficeDetails(officeObj);						
					} else if (jsonObj.selectedOffice) {
						setOfficeDetails(jsonObj.selectedOffice);
					} else if (jsonObj.updatedOffice) {
						$('#searchOffice').val('');
						$('#searchOffice').prop('disabled', true);
						$('#officeFields').val('all');
						$('#btnCancel').hide();
						$('#btnSave').hide();
						$('#btnAdd').show();						
						custom_alert_dialog("Successfully updated an office.");
						officeObj = {id:"",name:"",contact_person:"",contact_no:"",address:"",municipality:"",lat:"",lng:""};
						setOfficeDetails(officeObj);
					}
				}
			}

			function setupOfficeTable(jsonOfficesObj) {
				var tblHeaders = ['ID', 'NAME', 'ADDRESS', 'CONTACT PERSON', 'CONTACT NO', 'LAT', 'LNG'];
				var tblRows = "<tr>";
				tblRows += getTableHeader(tblHeaders);
				tblRows += '<th colspan="2">OPTIONS</th>'
				tblRows += "</tr>";
				tblRows += tableJSON('#officeContainer', jsonOfficesObj, {edit:"EDIT", delete:"DELETE"});
				$('#officeContainer').append('<tbody>' + tblRows + '</tbody>');
			}

			function setOfficeDetails(jsonSelectedOffice) {
				officeObj = jsonSelectedOffice;
				console.log(officeObj + " my local");
				$('#name').val(officeObj.name);
				$('#contact_person').val(officeObj.contact_person);
				$('#contact_no').val(officeObj.contact_no);
				$('#navSearchBox').val('');
				if (officeObj.address == "") {
					$('#address').text("click the map for location assignment");
				} else {
					$('#address').text(officeObj.address);	
				}
				
				if (officeObj.lat == "" || officeObj.lng == "") {
					marker.setMap(null);
					marker = null;
					map.setCenter(new google.maps.LatLng(12.8797, 121.7740));
    				map.setZoom(6);
				} else {
					markerOptions.position = new google.maps.LatLng(officeObj.lat, officeObj.lng);
					addMarkerOnce(markerOptions);
					map.setCenter(markerOptions.position);
    				map.setZoom(12);
				}
			}

			$('#officeFields').on('change', function() {
				var selectedOption = $('#officeFields option:selected').val();
				var searchStr = $('#searchOffice').val();
				currentOption = selectedOption;
				console.log(currentOption);
				if (selectedOption == "all") {
					$('#searchOffice').val('');
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

			$('#btnAdd').on('click', function() {
				officeObj.name = $('#name').val();
				officeObj.contact_person = $('#contact_person').val();
				officeObj.contact_no = $('#contact_no').val();		
				var searchStr = $('#searchOffice').val();
				
				if (officeObj.name == "" || officeObj.contact_person == "" || officeObj.contact_no == "" || officeObj.municipality == "" || officeObj.lat == "" || officeObj.lng == "") {
					custom_alert_dialog("Fill all required fields");
					return;
				} else if (officeObj.address == "") {
					custom_alert_dialog("set the office address by plotting it on the map.");
					return;
				}

				processPOSTRequest("backendprocess3.php", "createOffice=true&jsonOffice=" + JSON.stringify(officeObj)+"&getType="+currentOption+"&searchStr="+searchStr);
			});

			$('#btnCancel').on('click', function() {
				$('#btnCancel').hide();
				$('#btnSave').hide();
				$('#btnAdd').show();
				officeObj = {id:"",name:"",contact_person:"",contact_no:"",address:"",municipality:"",lat:"",lng:""};
				setOfficeDetails(officeObj);
			});	

			$('#btnSave').on('click', function() {
				officeObj.name = $('#name').val();
				officeObj.contact_person = $('#contact_person').val();
				officeObj.contact_no = $('#contact_no').val();		
				var searchStr = $('#searchOffice').val();
				
				if (officeObj.name == "" || officeObj.contact_person == "" || officeObj.contact_no == "" || officeObj.municipality == "" || officeObj.lat == "" || officeObj.lng == "") {
					custom_alert_dialog("Fill all required fields");
					return;
				} else if (officeObj.address == "") {
					custom_alert_dialog("set the office address by plotting it on the map.");
					return;
				}				

				processPOSTRequest('backendprocess3.php', "updateOffice=true&jsonOffice=" + JSON.stringify(officeObj));
			});

			$(document).on('click', '.optEdit', function () {
				$office_id = $(this).attr('data-internalid');
				processRequest("backendprocess3.php?selectOffice=true&office_id=" + $office_id);
				$('#btnAdd').hide();
				$('#btnCancel').show();
				$('#btnSave').show();
				return false;
			});

			//on map click
			google.maps.event.addListener(map, 'click', function(e) {
				getReverseGeocodingData(e);
			});

			function addMarkerOnce(customMarkerOptions) {
				if (marker) {	
					marker.setPosition(customMarkerOptions.position);
				} else {
					marker = new google.maps.Marker(customMarkerOptions);
					marker.setMap(map);
					eventCallBack(marker);
				}
			}

			function getReverseGeocodingData(e) {
				var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());
				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({'latLng':latLng}, function(results, status) {
					if (status !== google.maps.GeocoderStatus.OK) {
						//alert(status);
						custom_alert_dialog("Cant plot an office here. Location seems outside of the Philippines.");
					}

					if (status == google.maps.GeocoderStatus.OK) {
						console.log(results[0]);
						officeObj.address = results[0].formatted_address;
						if (results[0].address_components[1] == undefined) {
							custom_alert_dialog("Cant plot an office here. Location doesn't belong to any municipality");
							return;
						}
						officeObj.municipality = results[0].address_components[1].long_name;
						officeObj.lat = e.latLng.lat();
						officeObj.lng = e.latLng.lng();
						markerOptions.position = new google.maps.LatLng(officeObj.lat, officeObj.lng);
						$('#address').text(officeObj.address);
						addMarkerOnce(markerOptions);
					}
				});
			}

			function eventCallBack(marker) {
				(function(marker) {
					google.maps.event.addListener(marker, 'dragend', function(e) {
						getReverseGeocodingData(e);
					});
				})(marker);
			}
		</script>		
	</body>
</html>