<?php require_once(LIB_PATH . DS . "database.php"); ?>
<?php require_once(LIB_PATH . DS . "databaseObject.php"); ?>

<?php 
	class Office extends DatabaseObject {

		protected static $table_name = "OFFICE_TB";
		protected static $db_fields = array('id', 'name', 'address', 'municipality', 'province', 'country', 'contact_person', 'contact_no', 'lat', 'lng');

		public $id;
		public $name;
		public $address;
		public $municipality;
		public $province;
		public $country;
		public $contact_person;
		public $contact_no;
		public $lat;
		public $lng;

		public static function getOfficeFields() {
			return array('id'=>'ID', 'name'=>'Name', 'address'=>'Address', 'municipality'=>'Municipality', 'province'=>'Province', 'contact_person'=>'Contact Person', 'contact_no'=>'Contact No.');
		}

		public function getCustomFields() {
			return array('id', 'name', 'address', 'municipality', 'province', 'contact_person', 'contact_no', 'lat', 'lng'); 
		}

		public function toJSON($customized=false) {
			if ($customized) {
				$fValueArr = array();
				foreach ($this->getCustomFields() as $key => $eachField) {
					$fValueArr[] = '"' . $eachField . '":"' . htmlentities($this->$eachField) . '"';		
				}
				return join(", ", $fValueArr);
			}
			return parent::toJSON();
		}
	}
?>