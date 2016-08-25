<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>

<?php 
	class Office extends DatabaseObject {

		protected static $table_name = "OFFICE_TB";
		protected static $db_fields = array('id', 'name', 'address', 'contact_person', 'contact_no', 'lat', 'lng');

		public $id;
		public $name;
		public $address;
		public $contact_person;
		public $contact_no;
		public $lat;
		public $lng;
	}
?>