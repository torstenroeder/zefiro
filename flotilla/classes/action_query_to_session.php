<?php

class Action_Query_to_Session extends Action {

	protected $querystring;

	protected function __construct (Form $Creator,$querystring,$namespace = NULL) {
		$args = func_get_args();
		/**
		 *
		 * @var Form $Creator
		 */
		$this->Creator = $Creator;
		$this->querystring = $querystring;
		$this->namespace = $namespace;
		$this->Creator->debuglog->Write(DEBUG_INFO,'. QUERY TO SESSION ACTION created');
	}

	static public function create () {
		// create ( Creator )
		$args = func_get_args();
		switch (func_num_args()) {
			case 2: return new Action_Query_to_Session ($args[0],$args[1]);
			case 3: return new Action_Query_to_Session ($args[0],$args[1],$args[2]);
			default: $this->Creator->debuglog->Write(DEBUG_WARNING,'. QUERY TO SESSION ACTION - invalid number of arguments');
		}
	}

	function Query () {
		$this->querystring = preg_replace_callback('/(\{(\w*)\})/', function ($matches) {
		    return $this->Creator->Fields[$matches[2]]->user_value;
		}, $this->querystring);
		$result = $this->Creator->Connection->link->query($this->querystring);
		if (!$result) $this->Creator->debuglog->Write(DEBUG_ERROR,$this->querystring);
		else $this->Creator->debuglog->Write(DEBUG_INFO,$this->querystring);
		return $result->fetch_array();
	}

	function onSubmit () {
		$this->Creator->debuglog->Write(DEBUG_INFO,'. QUERY TO SESSION ACTION - writing to session');
		if (!isset($_SESSION[$this->Creator->name])) {
			$_SESSION[$this->namespace][$this->Creator->name] = array();
		}
		//print_r($this->Query());
		$_SESSION[$this->namespace][$this->Creator->name] = $this->Query();
	}

}
