<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>

<?php  
	class User extends DatabaseObject {

		protected static $table_name = "END_USER_TB";
		protected static $db_fields = array('id', 'display_picture','first_name', 
											'last_name', 'middle_name', 'address', 
											'contact_no', 'office_id', 'department', 
											'rank', 'username', 'password');

		public $id;
		public $display_picture;
		public $first_name;
		public $last_name;
		public $middle_name;
		public $address;
		public $contact_no;
		public $office_id;
		public $department;
		public $rank;
		public $username;
		public $password;

		public static function authenticate($username="", $password="") {
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
		
		public function display_name() {
			if (isset($this->first_name) && isset($this->last_name)) {
				return $this->first_name . " " . $this->last_name;
			} else {
				return "";
			}
		}

		public function full_name() {
			return $this->last_name . ", " . $this->first_name . " " . $this->middle_name;
		}

		//Override
		public function getCustomFields() {
			return array('id', 'display_picture','full_name', 'address', 
			             'contact_no', 'office_id', 'department', 
			             'rank', 'username', 'password');			
		}

		//Override
		public function toJSON($customized=false) {
			//if for display then customized it then if false do the old thing
			if ($customized) {
				$fValueArr = array();
				foreach ($this->getCustomFields() as $key => $eachField) {
					if ($eachField == "display_picture") {
						$fValueArr[] = '"' . $eachField . '":"' . '<img src=\"' . htmlentities($this->$eachField) . '\"/>"';
					} else if ($eachField == "full_name") {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($this->full_name()) . '"';	
					} else if ($eachField == "office_id") {
						$officeObj = Office::find_by_id($this->$eachField);
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($officeObj->name) . '"';
					} else {
						$fValueArr[] = '"' . $eachField . '":"' . htmlentities($this->$eachField) . '"';		
					}
				}		
				return join("," ,$fValueArr);		
			}
			return parent::toJSON();
		}
	}
?>