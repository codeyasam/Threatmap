<?php require_once('includes/initialize.php'); ?>
<?php  
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
	$client_fields = ClientUser::getClientFields();
?>
<!DOCTYPE html>
<html>
	<head>
		<title></title>
		<link rel="stylesheet" type="text/css" href="css/main.css"/>
		<link rel="stylesheet" type="text/css" href="js/jquery-ui.css">		
	</head>
	<body>
		
		<div class="page-wrapper">
			<?php getNavigation($user); ?>
			<div id="clientWrapper">
				<div id="clientHeader">
					<input id="searchClient" type="text" placeholder="search clients by " disabled="disabled" />
					<select id="clientFields">
						<option value='all'>all</option>
						<?php echo getOptions($client_fields); ?>
					</select>				
				</div>
				<table id="clientContainer" style="width: 58%; float: left;"></table>			
				<div class="mapContainer"><div id="map" class="main-window"></div></div>		
			</div>
		</div>
		<script src="https://maps.googleapis.com/maps/api/js?sensor=false&key=AIzaSyDDpPDWu9z820FMYyOVsAphuy0ryz4kt2o&libraries=places&sensor=false"></script>		
		<script type="text/javascript" src="js/jquery-1.11.3.min.js"></script>
		<script type="text/javascript" src="js/jquery-ui.min.js"></script>
		<script type="text/javascript" src="js/functions.js"></script>
		<script type="text/javascript" src="js/myGmapsInit.js"></script>
		<script type="text/javascript">
			$('#clientPage').addClass('selectedPage');
			processRequest("backendprocess4.php?getClients=true&getType=all&loadPage=true");

			var currentOption = 'all';
			var allClientsDetail;
			function handleServerResponse() {
				if (objReq.readyState == 4 && objReq.status == 200) {
					console.log(objReq.responseText);
					var jsonObj = JSON.parse(objReq.responseText);
					if (jsonObj.Clients) {
						setupTableClient(jsonObj.Clients);
						if (jsonObj.ClientsFullDetail) {
							allClientsDetail = jsonObj.ClientsFullDetail;
							plotClientsOnMap(jsonObj.ClientsFullDetail);
						}
					} 
				}
			}

			function setupTableClient(jsonClients) {
				var tblHeaders = ['ID', 'PICTURE', 'FULL NAME', 'ADDRESS', 'CONTACT NO', 'PERSON TO NOTIFY', 'RELATION', 'ID NO.'];
				var tblRows = "<tr>";
				tblRows += getTableHeader(tblHeaders);
				tblRows += '<th>VIEW</th>';
				tblRows += "</tr>";
				tblRows += tableJSON('#clientContainer', jsonClients, {edit : 'ZOOM ON MAP'});
				$('#clientContainer').append('<tbody>' + tblRows + '</tbody>');
				$('#clientContainer img').css({"width":"114","height":"114"});
			}

			function plotClientsOnMap(clientsJsonObj) {
				for (var key in clientsJsonObj) {
					if (clientsJsonObj.hasOwnProperty(key)) {
						markerOptions.position = new google.maps.LatLng(clientsJsonObj[key].lat, clientsJsonObj[key].lng);
						var marker = new google.maps.Marker(markerOptions);
						marker.setMap(map);
					}
				}
			}

			function getClientDetailById(needleID, haystackObj) {
				for (var key in haystackObj) {
					if (haystackObj.hasOwnProperty(key)) {
						if (haystackObj[key].id == needleID)
							return haystackObj[key];
					}
				}
				return false;
			}

			$('#clientFields').on('change', function () {
				currentOption = $('#clientFields option:selected').val();
				var searchStr = $('#searchClient').val();
				if (currentOption == 'all') {
					$('#searchClient').val('');
					$('#searchClient').prop('disabled', true);
				} else {
					$('#searchClient').prop('disabled', false);
				}
				processRequest('backendprocess4.php?getClients=true&getType=' + currentOption + "&searchStr=" + searchStr);
			});

			$('#searchClient').on('input', function() {
				var searchStr = $('#searchClient').val();
				processRequest('backendprocess4.php?getClients=true&getType=' + currentOption + "&searchStr=" + searchStr);
			});

			$(document).on('click', '.optEdit', function() {
				var clientID = $(this).attr('data-internalid');
				var clientDetail = getClientDetailById(clientID, allClientsDetail);
				map.setCenter(new google.maps.LatLng(clientDetail.lat, clientDetail.lng));
				map.setZoom(17);
				return false;
			});
		</script>
	</body>
</html>