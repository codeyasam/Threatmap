<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>

<?php  
	class User extends DatabaseObject {

		protected static $table_name = "END_USER_TB";
		protected static $db_fields = array('id', 'display_picture','first_name', 
											'last_name', 'middle_name', 'address', 
											'contact_no', 'office_id', 'department', 
											'rank', 'username', 'password', 'user_type');

		public $id;
		public $display_picture = "DISPLAY_PICTURES/default_avatar.png";
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
		public $user_type = "";

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

		public static function getUserFields() {
			return array('id'=>'ID', 'full_name'=>'Full Name', 'first_name'=>'First Name', 
					'last_name'=>'Last Name', 'middle_name'=>'Middle Name', 'address'=>'Address', 
					'contact_no'=>'Contact No.', 'office_id'=>'Office Name', 'department'=>'Department', 
					'rank'=>'Rank', 'username'=>'Username');			
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
						$fValueArr[] = '"' . $eachField . '":"' . '<img src=\"' . htmlentities($this->$eachField) . '?dummy=' . time() . '\"/>"';
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

		public static function hasExisting($entry, $columnName) {
			global $database;

			$entry = $database->escape_value($entry);
			$columnName = $database->escape_value($columnName);

			$sql  = "SELECT * FROM " . self::$table_name . " ";
			$sql .= "WHERE " . $columnName . " = '{$entry}' ";

			$result_array = self::find_by_sql($sql);
			return !empty($result_array) ? array_shift($result_array) : false;
		}		

		public static function check_existing($str, $columnName, $msg) {
			global $errors;
			$result = static::hasExisting($str, $columnName);
			if ($result) $errors[] = $msg; 
			return $result;
		}		

		public static function search_user_by_column_array($str, $columnArray=array()) {
			global $database;

			$str = $database->escape_value($str);

			$sql  = "SELECT * FROM " . self::$table_name . " ";
			$sql .= "WHERE (";
			$sqlArray = array();
			foreach ($columnArray as $key => $value) {
				$sqlArray[] = $value . " LIKE '%" . $str . "%'";
			}
			$sql .= join(' OR ', $sqlArray);
			$sql .= ") AND user_type = '' ";
			return self::find_by_sql($sql);
		}

		public static function search_user_by_full_name($full_name) {
			global $database;
			$full_name = $database->escape_value($full_name);
			$sql = "SELECT * FROM " . self::$table_name . " ";
			$sql .= "WHERE (CONCAT(last_name, ' ', first_name, ' ', middle_name) LIKE ";
			$sql .= "'%" . $full_name . "%' " . "OR ";
			$sql .= "CONCAT(first_name, ' ', middle_name, ' ', last_name) LIKE ";
			$sql .= "'%" . $full_name . "%' " . "OR ";
			$sql .= "CONCAT(first_name, ' ', last_name, ' ', middle_name) LIKE ";
			$sql .= "'%" . $full_name . "%' ) ";
			$sql .= "AND user_type = '' ";
			return self::find_by_sql($sql);
		}

		public static function search_by_office_Ids($ids=array()) {
			global $database;

			if (count($ids) < 1) return array();

			$sql  = "SELECT * FROM " . self::$table_name . " ";
			$sql .= "WHERE ";
			$sqlArray = array();
			foreach ($ids as $key => $value) {
				$sqlArray[] = "office_id = " . $database->escape_value($value);
			}
			$sql .= join(' OR ', $sqlArray);
			return self::find_by_sql($sql);
		}
	}
?>