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
	function createJSONEntity($holder, $objArr, $customized=false) {
		$otString = '"' . $holder . '":[';
		
		if (empty($objArr)) {
			return $otString .= ']';
		}
		
		$otArray = array();

		foreach ($objArr as $key => $eachObj) {
			$otArray[] = !$customized ? $eachObj->toJSON() : $eachObj->toJSON($customized);
		}

		$otString .= "{" . join("},{", $otArray) . "}";
		$otString .= "]";

		return $otString;
	}	


	function getNavigation($user, $hasSearchBox=false) {
		global $user;
		global $nav_has_search_box;
		$nav_has_search_box = $hasSearchBox;
		require_once(LIB_PATH . DS . "navigation.php");
	}
?>