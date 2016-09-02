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
		$threatObj = json_decode($_POST['threatObj']);
		$new_threat = new Threat();
		$new_threat->address = $threatObj->address;
		$new_threat->municipality = $threatObj->municipality;
		$new_threat->province = $threatObj->province;
		$new_threat->country = $threatObj->country;
		$new_threat->description = $threatObj->description;
		$new_threat->lat = $threatObj->lat;
		$new_threat->lng = $threatObj->lng;
		$new_threat->create();

		$output .= '"newThreat":{' . $new_threat->toJSON() . '}';
	} else if (isset($_GET['getThreats'])) {
		$all_threats = Threat::find_all();
		$output .= createJSONEntity("Threats", $all_threats);
	} else if (isset($_POST['deleteThreat'])) {
		Threat::delete_by_id($_POST['threat_id']);
	} else if (isset($_POST['updateThreat'])) {
		$threatObj = json_decode($_POST['threatObj']);
		$threat = Threat::find_by_id($threatObj->id);
		$threat->address = $threatObj->address;
		$threat->municipality = $threatObj->municipality;
		$threat->province = $threatObj->province;
		$threat->country = $threatObj->country;
		$threat->lat = $threatObj->lat;
		$threat->lng = $threatObj->lng;
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