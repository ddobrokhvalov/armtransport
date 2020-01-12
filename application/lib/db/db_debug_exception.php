<?
	class DBDebugException extends Exception {
		
		private $debug_message;
		
		function __construct ($message, $debug_message, $code=0) {
			parent::__construct($message, $code);
			$this->debug_message = $debug_message;
		}
		
		public function getDebugMessage() {
			return $this->debug_message;
		}
	}