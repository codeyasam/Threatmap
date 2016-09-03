<?php require_once("includes/initialize.php"); ?>
<?php 
	$user = $session->is_logged_in() ? User::find_by_id($session->user_id) : false;
	if (!$user) redirect_to('login.php');
?>

<?php  
	//Main processes
	$output = "{";
	if (isset($_GET['getOffices'])) {
		$offices = getOffices();
		$output .= createJSONEntity("Offices", $offices, true);
	} else if (isset($_POST['deleteOffice'])) {
		$has_affected_rows = Office::delete_by_id(trim($_POST['office_id']));
		$offices = getOffices(true);
		$output .= $has_affected_rows ? createJSONEntity("Offices", $offices, true) : '"hasDeleteError":"true"';   
		
	} else if (isset($_POST['createOffice'])) {
		$jsonOffice = json_decode($_POST['jsonOffice']);
		$office = new Office();
		$office->name = $jsonOffice->name;
		$office->address = $jsonOffice->address;
		$office->contact_person = $jsonOffice->contact_person;
		$office->contact_no = $jsonOffice->contact_no;
		$office->municipality = $jsonOffice->municipality;
		$office->province = $jsonOffice->province;
		$office->country = $jsonOffice->country;
		$office->lat = $jsonOffice->lat;
		$office->lng = $jsonOffice->lng;
		$office->create();
		$offices = Office::find_all();
		$output .= createJSONEntity("Offices", $offices, true) . ", ";
		$output .= '"createdOffice":"true"';
	} else if (isset($_GET['selectOffice'])) {
		$office = Office::find_by_id($_GET['office_id']);
		$output .= '"selectedOffice":{' . $office->toJSON() . '}'; 
	} else if (isset($_POST['updateOffice'])) {
		$jsonOffice = json_decode($_POST['jsonOffice']);
		$office = Office::find_by_id($jsonOffice->id);
		$office->name = $jsonOffice->name;
		$office->address = $jsonOffice->address;
		$office->contact_person = $jsonOffice->contact_person;
		$office->contact_no = $jsonOffice->contact_no;
		$office->municipality = $jsonOffice->municipality;
		$office->province = $jsonOffice->province;
		$office->country = $jsonOffice->country;		
		$office->lat = $jsonOffice->lat;
		$office->lng = $jsonOffice->lng;
		$office->update();
		$offices = Office::find_all();
		$output .= createJSONEntity("Offices", $offices, true) . ", ";
		$output .= '"updatedOffice":"true"';
	}

	$output .= "}";
	echo $output;

	function getOffices($isPOST=false) {
		$offices   = new Office();
		$getType   = !$isPOST ? $_GET['getType']   : $_POST['getType']; 
		if ($getType == "all") {
			$offices = Office::find_all();
		} else {
			$searchStr = !$isPOST ? $_GET['searchStr'] : $_POST['searchStr'];
			$offices = Office::search_by_column_array(trim($searchStr), array(trim($getType)));
		}
		return $offices;		
	}
?>