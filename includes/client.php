<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>

<?php  
	class ClientUser extends DatabaseObject {
		protected static $table_name = "CLIENT_TB";
		protected static $db_fields = array('id', 'display_picture', 'first_name', 'last_name', 'middle_name', 'address', 'lat', 'lng', 'contact_no', 'office_id', 'department', 'rank', 'username', 'password', 'person_to_notify', 'relationship', 'identification_number');

		public $id;
		public $display_picture;
		public $first_name;
		public $last_name;
		public $middle_name;
		public $address;
		public $lat;
		public $lng;
		public $contact_no;
		public $office_id;
		public $department;
		public $rank;
		public $username;
		public $password;
		public $person_to_notify;
		public $relationship;
		public $identification_number;

		public static function authenticate($username, $password) {
			global $database;
	
			$username = $database->escape_value($username);
			$password = $database->escape_value($password);

			$password = md5($password);
			$sql  = "SELECT * FROM " . self::$table_name . " ";
			$sql .= "WHERE BINARY username = '{$username}' ";
			$sql .= "AND BINARY password = '{$password}' ";
			$sql .= "LIMIT 1 ";

			$result_array = self::find_by_sql($sql);
			return !empty($result_array) ? array_shift($result_array) : false;			
		}

		public function full_name() {
			return $this->last_name . ", " . $this->first_name . " " . $this->middle_name;
		}

		public function display_name() {
			if (isset($this->first_name) && isset($this->last_name)) {
				return $this->first_name . " " . $this->last_name;
			} else {
				return "";
			}
		}		

		public static function getClientFields() {
			return array('id'=>'ID', 'full_name'=>'Full Name', 'first_name'=>'First Name', 'middle_name'=>'Middle Name', 'last_name'=>'Last Name', 'address'=>'Address', 'contact_no'=>'Contact No.', 'person_to_notify'=>'Person to Notify', 'identification_number'=>'Identification Number');			
		}		

		public function getCustomFields() {
			return array('id', 'display_picture', 'full_name', 'address', 'contact_no', 'person_to_notify', 'relationship', 'identification_number');			
		}

		public function toJSON($customized=false) {
			if ($customized) {
				$fValueArr = array();
				foreach($this->getCustomFields() as $key => $eachField) {
					if ($eachField == "display_picture") {
						$fValueArr[] = '"' . $eachField . '":"' . '<img src=\"' . htmlentities($this->$eachField) . '?dummy=' . time() . '\"/>"';
					} else if ($eachField == "full_name") {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($this->full_name()) . '"';	
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