<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>
<?php  
	class Notification extends DatabaseObject {
		protected static $table_name = "NOTIFICATION_TB";
		protected static $db_fields = array('id', 'client_id', 'address', 'municipality', 'lat', 'lng', 'submit_dtime');

		public $id;
		public $client_id;
		public $address;
		public $municipality;
		public $lat;
		public $lng;
		public $submit_dtime;

		public function create() {
			$this->submit_dtime = get_mysql_datetime(time());
			parent::create();
		}
		
		public function getCustomFields() {
			return array('id', 'display_picture', 'client_name', 'address', 'lat', 'lng', 'submit_dtime');
		}

		public function toJSON($customized=false) {
			if ($customized) {
				$fValueArr = array();
				foreach ($this->getCustomFields() as $key => $eachField) {
					$clientObj = ClientUser::find_by_id($this->client_id);
					if ($eachField == 'display_picture') {
						$fValueArr[] = '"' . $eachField . '":"' . '<img src=\"' . htmlentities($clientObj->display_picture) . '?dummy=' . time() . '\"/>"';
					} else if ($eachField == 'client_name') {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($clientObj->display_name()) . '"';
					} else if ($eachField == "submit_dtime") {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities(format_date($this->$eachField)) . '"';
					} else {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($this->$eachField) . '"';		
					}
				}
				return join(", ", $fValueArr);
			}
			return parent::toJSON();
		}
	}
?>