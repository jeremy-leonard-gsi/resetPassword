<?php

class resetPassword extends authentication{
	
	public function getAllUsers() {
		$results = ldap_search($this->DS,$this->ldap_base,$this->ldap_filter);
		return ldap_get_entries($this->DS,$results);
	}
	public function getFilteredUsers($token) {
		$filter = "(&".$this->ldap_filter."(".$this->ldap_fullname_attr."=*$token*))";
		$results = ldap_search($this->DS,$this->ldap_base,$filter);
		return ldap_get_entries($this->DS,$results);
	}
	
	public function resetUserPassword($userDN,$password,$forceUserChange=false) {
		$userPassword = array("unicodePwd"=>encodeADPassword($password));
		ldap_mod_replace($this->DS, $userDN, $userPassword);
		$return["number"]=ldap_errno($this->DS);
		$return["message"]=ldap_error($this->DS);
		switch($return["number"]) {
			case 0:
				$this->log->logEvent("Reset Password Successful",$_SESSION["username"]." Reset the password for $userDN. Status: ".$return["message"]);				
				if($forceUserChange){
					ldap_mod_replace($this->DS, $userDN, array("pwdLastSet"=>"0"));
					$this->log->logEvent("User Force Change",$_SESSION["username"]." Set $userDN force change on next logon");				
				}
			break;
			default:
				$this->log->logEvent("Reset Password Failed",$_SESSION["username"]." Attempted to reset the password for $userDN. Status: ".$return["message"]);						
		}
		return json_encode($return);
	}
}

?>
