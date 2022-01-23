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
		$stmt->execute();
                $stmt->close();
	}
        
        public function getEventStart(){
            $results = $this->DB->query("SELECT DATE_FORMAT(MIN(eventTime),'%Y-%m-%d') AS startTime FROM eventLog;");
            return $results->fetch_assoc()['startTime'];
        }
        public function getEventEnd(){
            $results = $this->DB->query("SELECT DATE_FORMAT(MAX(eventTime),'%Y-%m-%d') AS endTime FROM eventLog;");
            return $results->fetch_assoc()['endTime'];
        }
        
        
	public function getEventCount($start=null,$end=null,$filter='%'){
            error_log('Start: '.$start);
            error_log('End: '.$end);
            error_log('Filter: '.$filter);
            $start=$start ?? $this->getEventStart();
            $end=$end ?? $this->getEventEnd();
            $stmt = $this->DB->prepare("SELECT COUNT(*) AS `Total` FROM eventLog WHERE eventTime <= ? AND eventTime >= ? AND eventMessage like ?;");
            $stmt->bind_param('sss', $end, $start, $filter);
            $stmt->execute();
            $results = $stmt->get_result();
            return $results->fetch_assoc()['Total'];            
        }
	public function getEvents($limit=10, $page=1, $start=null, $end=null, $filter='%') {
                error_log('Limit: '.$limit);
                error_log('Page: '.$page);
                error_log('Start: '.$start);
                error_log('End: '.$end);
                error_log('Filter: '.$filter);
                
                $start=$start ?? $this->getEventStart();
                $end=$end ?? $this->getEventEnd();
		$stmt = $this->DB->prepare("SELECT * FROM eventLog WHERE eventTime <= ? AND eventTime >= ? AND eventMessage like ? ORDER BY eventTime DESC LIMIT ?, ?;");
                $stmt->bind_param('sssii', $end, $start, $filter, $page, $limit);
		$stmt->execute();
                $results = $stmt->get_result();
		while($row = $results->fetch_assoc()){
			$log[]=$row;		
		}
		return $log;	
	}
}
?>