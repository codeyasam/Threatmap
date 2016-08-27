<?php require_once('includes/initialize.php'); ?>
<?php 
	//HANDLES UNATHORIZED ACCESS
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
?>

<?php 
	//main processes
	$output = "{";

	if (isset($_POST['createThreat'])) {
		$new_threat = new Threat();
		$new_threat->address = trim($_POST['address']);
		$new_threat->description = trim($_POST['description']);
		$new_threat->lat = trim($_POST['lat']);
		$new_threat->lng = trim($_POST['lng']);
		$new_threat->create();

		$output .= '"newThreat":{' . $new_threat->toJSON() . '}';
	} else if (isset($_GET['getThreats'])) {
		$all_threats = Threat::find_all();
		$output .= createJSONEntity("Threats", $all_threats);
	} else if (isset($_POST['deleteThreat'])) {
		Threat::delete_by_id($_POST['threat_id']);
	} else if (isset($_POST['updateThreat'])) {
		$threat = Threat::find_by_id($_POST['threat_id']);
		$threat->address = trim($_POST['address']);
		$threat->lat = trim($_POST['lat']);
		$threat->lng = trim($_POST['lng']);
		$threat->update();
	} else if (isset($_POST['updateThreatDescription'])) {
		$threat = Threat::find_by_id($_POST['threat_id']);
		$threat->description = trim($_POST['description']);
		$threat->update();
		$output .= '"updatedDescription":{' . $threat->toJSON() . '}';
	}

	$output .= "}";
	echo $output;
?>