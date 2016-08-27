<?php require_once(LIB_PATH . DS . 'database.php'); ?>
<?php require_once(LIB_PATH . DS . 'databaseObject.php'); ?>
<?php 
	class Threat extends DatabaseObject {

		protected static $table_name = "THREAT_TB";
		protected static $db_fields  = array("id", "description", "address", "lat", "lng");

		public $id;
		public $address;
		public $lat;
		public $lng;
		public $description;
		
	}
?>