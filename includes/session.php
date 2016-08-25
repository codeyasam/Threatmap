<?php require_once(LIB_PATH.DS."user.php"); ?>

<?php  
	
	class Session {

		private $logged_in = false;
		public $user_id;
		public $message;
		public $active_branch_id;

		function __construct() {
			session_start();
			$this->check_message();
			$this->check_login();
			if ($this->logged_in) {
				// actions to take right away if user is logged in
			} else {
				// actions to take right away if user is not logged in
			}
		}

		// check login
		private function check_login() {
			// check if session of user id isset
			if (isset($_SESSION['user_id'])) {
				// if it is set user_id a value and
				// set login as true
				$this->user_id = $_SESSION['user_id'];
				$this->logged_in = true;
			} else {
				//unset the user id
				// and set login as false
				unset($this->user_id);
				$this->logged_in =false;
			}
		}

		private function check_message() {
			if(isset($_SESSION['message'])) {
				$this->message = $_SESSION['message'];
				unset($_SESSION['message']);
			} else {
				$this->message="";
			}
		}		

		//object as an argument to be passed in
		public function login($user) {	
			if ($user) {
				$_SESSION['user_id'] = $this->user_id = $user->id;
				$this->logged_in = true;
				//User::page_redirect($this->user_id);
			}
		}

		public function is_logged_in() {			
			return $this->logged_in;
		}

		public function message($msg="") {
			if (!empty($msg)) {
				$_SESSION['message'] = $msg;
			} else {
				return $this->message;
			}	
		}


		public function logout() {
			if (isset($_SESSION['user_id'])) {
				unset($_SESSION['user_id']);
				unset($this->user_id);
				$this->logged_in = false;				
			} 

		}						
	}

	$session = new Session();
	$message = $session->message();
?>