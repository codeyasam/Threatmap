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
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>
		<?php getNavigation($user, true); ?>
		
		<div style="width: 100%; height: 100%;">
			<table id="notifContainer" style="width: 58%; float: left;"></table>
			<div class="mapContainer"><div id="map" class="main-window"></div></div>	
		</div>
		
		
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>		
		<script type="text/javascript">
			$('#notifPage').addClass('selectedPage');
			processRequest("backendprocess5.php?getNotifications=true&getType=all&loadPage=true");
			var currentOption = 'all';
			var allNotifsDetails;
			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);
					if (jsonObj.Notifs) {
						var latestNotifs = jsonObj.Notifs.reverse();
						setupNotifsTable(latestNotifs);
						if (jsonObj.PureNotifs) {
							console.log(jsonObj.PureNotifs);
							allNotifsDetails = jsonObj.PureNotifs;
							plotNotifsOnMap(jsonObj.PureNotifs);
						}
					}
				}
			}

			function plotNotifsOnMap(notifsJsonObj) {
				for (var key in notifsJsonObj) {
					if (notifsJsonObj.hasOwnProperty(key)) {
						console.log("here gumana");
						markerOptions.position = new google.maps.LatLng(notifsJsonObj[key].lat, notifsJsonObj[key].lng);
						var marker = new google.maps.Marker(markerOptions);
						marker.setMap(map);
					}
				}
			}

			function getNotifById(needleId, haystackObj) {
				for (var key in haystackObj) {
					if (haystackObj.hasOwnProperty(key)) {
						if (needleId == haystackObj[key].id)
							return haystackObj[key];
					}
				}
				return false;
			}			

			function setupNotifsTable(jsonNotifs) {
				var tblHeaders = ['ID', 'PICTURE', 'CLIENT NAME', 'ADDRESS', 'LAT', 'LNG', 'DATE'];
				var tblRows = "<tr>";
				tblRows += getTableHeader(tblHeaders);
				tblRows += '<th>VIEW</th>';
				tblRows += "</tr>";
				tblRows += tableJSON('#notifContainer', jsonNotifs, {edit:"VIEW ON MAP"});
				$('#notifContainer').append('<tbody>' + tblRows + '</tbody>');
				$('#notifContainer img').css({"width":"100", "height":"100"});
			}

			$(document).on('click', '.optEdit', function() {
				var notifId = $(this).attr('data-internalid');
				var notifObj = getNotifById(notifId, allNotifsDetails);
				map.setCenter(new google.maps.LatLng(notifObj.lat, notifObj.lng));
				map.setZoom(17);
				return false;
			});
		</script>
	</body>
</html>