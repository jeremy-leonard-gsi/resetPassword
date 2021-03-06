<?php

class resetPassword extends authentication{
	
	public function getAllUsers() {
		$results = ldap_search($this->DS,$this->ldap_base,$this->ldap_filter);
		return ldap_get_entries($this->DS,$results);
	}
	public function getFilteredUsers($token) {
		$filter = "(&".$this->ldap_filter."(|(".$this->ldap_fullname_attr."=*$token*)(".$this->ldap_userattr."=*$token*)))";
		if($this->CONFIG['DEBUG']){
			error_log($filter);
		}
		$results = ldap_search($this->DS,$this->ldap_base,$filter, [], 0, 50, 60);
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
	public function updateUAC($userDN,$uac) {

            ldap_mod_replace($this->DS, $userDN, ['useraccountcontrol'=>$uac]);
		$return["number"]=ldap_errno($this->DS);
		$return["message"]=ldap_error($this->DS);
		switch($return["number"]) {
			case 0:
				$this->log->logEvent("Updated userAccountControl Successfully",$_SESSION["username"]." updated userAccountControl for $userDN to $uac. Status: ".$return["message"]);				
			break;
			default:
				$this->log->logEvent("Update userAccountControl Failed",$_SESSION["username"]." Attempted to update userAccountControl for $userDN to $uac. Status: ".$return["message"]);						
		}
		return json_encode($return);
	}
        public function resetBadPwdCount($userDN){
            ldap_mod_replace($this->DS, $userDN, ['lockouttime'=>0]);
		$return["number"]=ldap_errno($this->DS);
		$return["message"]=ldap_error($this->DS);
		switch($return["number"]) {
			case 0:
				$this->log->logEvent("Cleared Lockout Successfully",$_SESSION["username"]." Cleared lockout for $userDN. Status: ".$return["message"]);				
			break;
			default:
				$this->log->logEvent("Clear Lockout Failed",$_SESSION["username"]." Attempted to clear lockout for $userDN. Status: ".$return["message"]);						
		}
		return json_encode($return);
        }
        public function updatePhoto($userDN, $photoPath){
            ldap_mod_replace($this->DS, $userDN, ['photo'=>$photoPath]);
		$return["number"]=ldap_errno($this->DS);
		$return["message"]=ldap_error($this->DS);
		switch($return["number"]) {
			case 0:
				$this->log->logEvent("Set Photo Path Success",$_SESSION["username"]." Set Photo Path for $userDN. Status: ".$return["message"]);				
			break;
			default:
				$this->log->logEvent("Set Photo Failed",$_SESSION["username"]." Attempted to set photo path for $userDN. Status: ".$return["message"]);						
		}
		return json_encode($return);            
        }
}

?>
