<?php
class applicationLog {

	private $dbhost,$dbuser,$dbpassword,$dbname,$DB;
	
	public function __construct($dbhost,$dbuser,$dbpassword,$dbname) {
		$this->dbhost=$dbhost;
		$this->dbuser=$dbuser;
		$this->dbpassword=$dbpassword;
		$this->dbname=$dbname;
		$this->DB = new mysqli($this->dbhost,$this->dbuser,$this->dbpassword,$this->dbname);
		if ($this->DB->connect_error) {
    		die('Connect Error (' . $this->DB->connect_errno . ') '
         	   . $this->DB->connect_error);
		}
	}
	public function logEvent($eventType,$eventMessage){
		$stmt = $this->DB->prepare("INSERT INTO `eventLog` (`ipAddress`,`eventType`,`eventMessage`) VALUES (?,?,?);");
		$stmt->bind_param("sss",$_SERVER['REMOTE_ADDR'],$eventType,$eventMessage);
		$results = $stmt->execute();
	}
	
	public function getEvents($limit=25) {
		$stmt = $this->DB->prepare("SELECT * FROM eventLog LIMIT=?;");
		//$stmt->bind_param("i",$limit);
		$results = $stmt->execute();
		while($row = $results->fetch_assoc()){
			$log[]=$row;		
		}
		return $log;	
	}
}
?>