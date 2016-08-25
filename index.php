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
	</head>
	<body>
		<?php //require_once('includes/navigation.php'); 
			getNavigation($user, true);
		?>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>
		
		<!-- <div id="indexDetailContainer"></div> -->
		<div id="map" class="main-window"></div>

		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>
		<script type="text/javascript">
			processRequest("backendprocess.php?getLoggedInClients=true");

			//setup handling of server response
			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);
					
					if (jsonObj.Clients) {
						console.log(jsonObj.Clients.length);
						if (jsonObj.Clients.length > 0) {
							plotClientsOnMap(jsonObj.Clients);
						}
					}
				}
			}

		</script>		
	</body>
</html>