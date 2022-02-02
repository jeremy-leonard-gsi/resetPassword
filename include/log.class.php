<?php
class applicationLog {

	private $dbhost,$dbuser,$dbpassword,$dbname,$DB,$enabled;
	
	public function __construct($dbhost,$dbuser,$dbpassword,$dbname,$enabled=true) {
            if($enabled){
		$this->dbhost=$dbhost;
		$this->dbuser=$dbuser;
		$this->dbpassword=$dbpassword;
		$this->dbname=$dbname;
		$this->DB = new mysqli($this->dbhost,$this->dbuser,$this->dbpassword,$this->dbname);
		if ($this->DB->connect_error) {
    		die('Connect Error (' . $this->DB->connect_errno . ') '
         	   . $this->DB->connect_error);
		}
                $this->enabled=true;
            }else{
                $this->enabled=false;
            }
	}
	public function logEvent($eventType,$eventMessage){
            if($this->enabled){
		$stmt = $this->DB->prepare("INSERT INTO `eventLog` (`ipAddress`,`eventType`,`eventMessage`) VALUES (?,?,?);");
		$stmt->bind_param("sss",$_SERVER['REMOTE_ADDR'],$eventType,$eventMessage);
		$stmt->execute();
                $stmt->close();
            }
	}
        
        public function getEventStart(){
            if($this->enabled){
                $results = $this->DB->query("SELECT DATE_FORMAT(MIN(eventTime),'%Y-%m-%d') AS startTime FROM eventLog;");
                return $results->fetch_assoc()['startTime'];
            }else{
                return null;
            }
        }
        public function getEventEnd(){
            if($this->enabled){
                $results = $this->DB->query("SELECT DATE_FORMAT(MAX(eventTime),'%Y-%m-%d') AS endTime FROM eventLog;");
                return $results->fetch_assoc()['endTime'];
            }else{
                return null;
            }
        }
                
	public function getEventCount($start=null,$end=null,$filter='%'){
            if($this->enabled){
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
            }else{
                return null;
            }
        }
	public function getEvents($limit=10, $page=1, $start=null, $end=null, $filter='%') {
            if($this->enabled){
                error_log('Limit: '.$limit);
                error_log('Page: '.$page);
                error_log('Start: '.$start);
                error_log('End: '.$end);
                error_log('Filter: '.$filter);
                
                $page=($page-1)*$limit;
                
                $start=$start ?? $this->getEventStart();
                $start .= '00:00:00';
                $end=$end ?? $this->getEventEnd();
                $end .= ' 23:59:59';
		$stmt = $this->DB->prepare("SELECT * FROM eventLog WHERE eventTime <= ? AND eventTime >= ? AND eventMessage like ? ORDER BY id DESC LIMIT ?, ?;");
                $stmt->bind_param('sssii', $end, $start, $filter, $page, $limit);
		$stmt->execute();
                $results = $stmt->get_result();
		while($row = $results->fetch_assoc()){
			$log[]=$row;		
		}
		return $log;	
            }else{
                return null;
            }
	}
}
?>