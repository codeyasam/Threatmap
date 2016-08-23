<?php  
	// loads automatically upon requiring
	function __autoload($class_name) {
		$class_name = strtolower($class_name);
		$path = LIB_PATH.DS."{$class_name}.php";
		if (file_exists($path)) {
			require_once($path);
		} else {
			die("The file {$class_name}.php could not be found. ");
		}
	}

	function redirect_to($new_location) {
		header("Location: {$new_location}");
		exit();
	}	

	//handles array of object of the models
	function createJSONEntity($holder, $objArr) {
		$otString = '"' . $holder . '":[';
		$otArray = array();

		foreach ($objArr as $key => $eachObj) {
			$otArray[] = $eachObj->toJSON();
		}

		$otString .= "{" . join("},{", $otArray) . "}";
		$otString .= "]";

		return $otString;
	}	


?>