<?php require_once('includes/initialize.php'); ?>
<?php
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;

	if (!$user) redirect_to('login.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css">
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">
	</head>
	<body>
		<?php getNavigation($user, true); ?>

		<h3>MANAGE THREATS</h3>
		<div class="actionBtnContainer">
			<button title="Add Tool" id="addThreatBtn" type="button"></button>
			<button title="Delete Tool" id="delThreatBtn" type="button"></button>
			<button title="Drag Tool" id="dragThreatBtn" type="button"></button>
			<!-- <button title="Select Tool" id="selectBranchBtn" type="button"></button> -->
		</div>
		<div id="threatFormDialog" style="display:none;">
			<table>
				<tr>
					<td><input id="description" type="text" placeholder="enter threat description"/></td>
				</tr>
			</table>
		</div>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>
		
		<!-- <div id="indexDetailContainer"></div> -->
		<div id="map" class="main-window"></div>		
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>
		<script type="text/javascript">
			markerOptions.animation = google.maps.Animation.DROP;
			markerOptions.draggable = true;

			var markers = [];
			var selectedIndex = -1;
			var toAdd = true;
			var toDelete = false;
			var toDrag = false;

			processRequest("backendprocess2.php?getThreats=true");

			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);
					
					if (jsonObj.Threats) {
						plotThreatsOnMap(jsonObj.Threats);
					} else if (jsonObj.newThreat) {
						console.log(markerOptions);
						markerOptions.position = new google.maps.LatLng(jsonObj.newThreat.lat, jsonObj.newThreat.lng);
						var marker = setMarkerValues(markerOptions, jsonObj.newThreat);
						eventCallback(marker);
					} else if (jsonObj.updatedDescription) {
						markers[selectedIndex].description = jsonObj.updatedDescription.description;
					}
				}
			}

			function plotThreatsOnMap(jsonThreats) {
				for (var key in jsonThreats) {
					if (jsonThreats.hasOwnProperty(key)) {
						markerOptions.position = new google.maps.LatLng(jsonThreats[key].lat, jsonThreats[key].lng);
						var marker = setMarkerValues(markerOptions, jsonThreats[key]);
						eventCallback(marker);
					}
				}
			}

			$('#addThreatBtn').on('click', function() {
				unSelectAllActionBtn();
				toAdd = true;
				toDelete = false;
				toDrag = false;
				console.log("add option clicked");
			});

			$('#delThreatBtn').on('click', function() {
				unSelectAllActionBtn();
				toDelete = true;
				toAdd = false;
				console.log("del option clicked");
			});

			$('dragThreatBtn').on('click', function() {
				unSelectAllActionBtn();
				toDelete = false;
				toDrag = true;
				toAdd = false;
				console.log("drag option clicked");
			});


			function unSelectAllActionBtn() {

			}

			function getReverseGeocodingData(e, operation) {
				var latLng = new google.maps.LatLng(e.latLng.lat(), e.latLng.lng());

				var geocoder = new google.maps.Geocoder();
				geocoder.geocode({'latLng': latLng}, function(results, status) {
					if (status !== google.maps.GeocoderStatus.OK) {
						alert(status);
					}
					//checking to see if the geocode status is ok before proceeding
					if (status == google.maps.GeocoderStatus.OK) {
						var address = (results[0].formatted_address);

						if (operation == "UPDATE") {
							processPOSTRequest("backendprocess2.php", "updateThreat=true&address=" + address + "&lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng() + "&threat_id=" + markers[selectedIndex].id);
						} else if (operation == "CREATE") {
							var action_performed = function() {
								var description = $('#description').val();
								processPOSTRequest("backendprocess2.php", "createThreat=true&address=" + address + "&lat=" + e.latLng.lat() + "&lng=" + e.latLng.lng() + "&description=" + description);
								$('#threatFormDialog').dialog('close');								
							}
							setupThreatForm(operation, action_performed);
							$('#threatFormDialog').dialog('open');
						}
					}
				});

			}
			//addMarker
			google.maps.event.addListener(map, 'click', function(e) {
				if (toDelete == false && toDrag == false) {	
					console.log("map to add");
					getReverseGeocodingData(e, "CREATE");
				}
			});

			function setMarkerValues(markerOptions, jsonObject) {
				var marker = new google.maps.Marker(markerOptions);
				marker.setMap(map);
				marker.id = jsonObject.id;
				marker.address = jsonObject.address;
				marker.description = jsonObject.description;
				markers.push(marker);
				console.log("set marker valeus");
				return marker;
			}

			function deleteMarker(marker, markers) {
				var threatID = marker.id;
				processPOSTRequest("backendprocess2.php", "deleteThreat=true&threat_id=" + threatID);
				var index = markers.indexOf(marker);
				marker.setMap(null);
				marker = null;
				markers.splice(index, 1);
			}

			function selectMarker(marker, markers) {
				selectedIndex = markers.indexOf(marker);
			}

			function eventCallback(marker) {
				(function(marker) {
					google.maps.event.addListener(marker, 'click', function() {
						console.log("marker clicked " + toDelete);
						if (toDelete == true) {
							var action_performed = function() {
								deleteMarker(marker, markers);
								$('#dialog').dialog('close');
							}
							confirm_action("Are you sure you want to delete this threat?", action_performed);
						} else {
							selectMarker(marker, markers);
							var action_performed = function() {
								var description = $('#description').val();
								processPOSTRequest("backendprocess2.php", "updateThreatDescription=true&description=" + description + "&threat_id=" + markers[selectedIndex].id);
								$('#threatFormDialog').dialog('close');					
							}
							setupThreatForm("UPDATE", action_performed, markers[selectedIndex].description);
							$('#threatFormDialog').dialog('open');							
						}
					});

					google.maps.event.addListener(marker, "dragend", function(e) {
						selectedIndex = markers.indexOf(marker);
						getReverseGeocodingData(e, "UPDATE");
					});
				})(marker);
			}

			function setupThreatForm(opt_type, action_performed, description=false) {
				$('#threatFormDialog').dialog({
					autoOpen: false,
					modal: true,
					buttons : [{
						text: "Cancel",
					    click : function() {
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
					}					
				});
				var btnToHide = (opt_type == "CREATE") ? "#btnSave" : "#btnCreate";
				$(btnToHide).hide();

				if (description) {
					$('#description').val(description);
				} else {
					$('#description').val('');
				}				
			}

		</script>				
	</body>	
</html>