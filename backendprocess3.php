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
		Office::delete_by_id(trim($_POST['office_id']));
		$offices = getOffices(true);
		$output .= createJSONEntity("Offices", $offices, true);
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