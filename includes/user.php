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

			return $sql;
			//$result_array = self::find_by_sql($sql);
			//return !empty($result_array) ? array_shift($result_array) : false;
		}


	}
?>