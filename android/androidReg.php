<?php require_once('../includes/initialize.php'); ?>
<?php  

	if (isset($_POST['submit'])) {
		$client = new ClientUser();
		$client->first_name = trim($_POST['first_name']);
		$client->middle_name = trim($_POST['middle_name']);
		$client->last_name = trim($_POST['last_name']);
		$client->address = trim($_POST['address']);
		$client->lat = trim($_POST['lat']);
		$client->lng = trim($_POST['lng']);
		$client->contact_no = trim($_POST['contact_no']);
		$client->person_to_notify = trim($_POST['person_to_notify']);
		$client->relationship = trim($_POST['relationship']);
		$client->identification_number = trim($_POST['identification_number']);
		$client->username = trim($_POST['username']);
		$client->password = trim($_POST['password']);

		if ($client->create()) {
			if (isset($_POST['image'])) {
				$decodedImage = base64_decode($_POST['image']);
				file_put_contents("../DISPLAY_PICTURES/profile_pic".$client->id, $decodedImage);
				$client->display_picture = "DISPLAY_PICTURES/profile_pic".$client->id;
			} else {
				$client->display_picture = $_POST['display_picture'];
			}
			$client->update();
			echo '{"success":"true", "Client":{' . $client->toJSON() . '}}';
		}
	} else {
		echo '{"success":"false"}';
	}
	
	// $client = ClientUser::find_by_id(5);
	// echo '{"success":"true", "Client":{' . $client->toJSON() . '}}';

	// if (isset($_GET['submit'])) {
	// 	$client = new ClientUser();
	// 	$client->first_name = "asdf";
	// 	$client->middle_name = "asdf";
	// 	$client->last_name = "asdf";
	// 	$client->address = "asdf";
	// 	$client->lat = "asdf";
	// 	$client->lng = "asdf";
	// 	$client->contact_no = "asdf";
	// 	$client->person_to_notify = "asdf";
	// 	$client->relationship = "asdf";
	// 	$client->identification_number = "asdf";
	// 	$client->username = "asdf";
	// 	$client->password = "asdf";

	// 	if ($client->create()) {
	// 		echo '{"success":"true", "Client":' . $client->toJSON() . '}';
	// 	}
	// } else {
	// 	echo '{"success":"false"}';
	// }
?>