<?php
class authentication{
	
	public $username,$password,$firstName,$lastName,$dn,$fullName,$error,$errno;
	
	protected $log,$ldap_uri,$ldap_base,$ldap_dn,$ldap_secret,$ldap_userattr,$ldap_filter,$ldap_member_attr,$ldap_authorized,$DS,$ldap_fullname_attr,$ldap_page_size;

	function __construct($log,$ldap_uri,$ldap_base,$ldap_dn,$ldap_secret,$ldap_userattr,$ldap_filter,$ldap_member_attr,$ldap_authorized,$ldap_fullname_attr,$ldap_page_size=100) {
		global $_CONFIG;
		$this->log = $log;
		$this->ldap_page_size=$ldap_page_size;	
		$this->ldap_uri=$ldap_uri;
		$this->ldap_base=$ldap_base;
		$this->ldap_dn=$ldap_dn;
		$this->ldap_secret=$ldap_secret;
		$this->ldap_userattr=$ldap_userattr;
		$this->ldap_filter=$ldap_filter;
		$this->ldap_member_attr=$ldap_member_attr;
		$this->ldap_authorized=$ldap_authorized;
		$this->ldap_fullname_attr=$ldap_fullname_attr;
		if($_CONFIG["DEBUG"]) {
			ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 7);
		}else{
			ldap_set_option(null, LDAP_OPT_DEBUG_LEVEL, 0);
		}
		$this->DS = ldap_connect($this->ldap_uri);
		ldap_set_option($this->DS, LDAP_OPT_REFERRALS, false);
		ldap_set_option($this->DS, LDAP_OPT_PROTOCOL_VERSION	, 3);
		return ldap_bind($this->DS, $this->ldap_dn,$this->ldap_secret);
	}
	function __destruct() {
		ldap_close($this->DS);
	}
	
	function getUserInfo($dn=null) {
		if($dn==null) {
			$dn=$this->dn;
		}
		$filter="(objectclass=*)";
		$results = ldap_read($this->DS, $dn, $filter);
		return ldap_get_entries($this->DS,$results);
	}
	
	function doLogin($username,$password) {
		global $_CONFIG;
		$this->username = $username;
		$this->password = $password;
		$filter = "(&".$this->ldap_filter."(".$this->ldap_userattr."=$username))";
		if($_CONFIG["DEBUG"]) {
			error_log($filter);
		}
		$ldapresult = ldap_search($this->DS,$this->ldap_base,$filter);
		$user = ldap_get_entries($this->DS, $ldapresult);
		$this->fullName = $user[0][$this->ldap_fullname_attr][0];
		$dn = $user[0]["dn"];
		$this->dn=$dn;
		$status = ldap_bind($this->DS, $dn,$password);
		$this->error = ldap_error($this->DS);
		$this->errno = ldap_errno($this->DS);
		// logging
		if($this->errno==0 AND !isset($_SESSION["authenticated"])){
			$this->log->logEvent("Login Successful","User $username successfully logged in.");
		}else if  (!isset($_SESSION["authenticated"])){
			$this->log->logEvent("Login Failed","User $username failed to log in.");		
		}
		return $status;
	}
	
	function authorized() {
		$groups='';
		$authorized = false;
		$filter = "(".$this->ldap_member_attr."=".addslashes($this->dn).")";
		$results = ldap_search($this->DS, $this->ldap_base, $filter);
		foreach(ldap_get_entries($this->DS,$results) as $group){
			if(in_array($group["cn"][0], $this->ldap_authorized)) {
				$groups.=$group["cn"][0].",";
				$authorized = true;
			}else{
			}		
		}
		if(!($authorized)) {
				$this->error = "You aren't authorized to use this site.";
				$this->errno = 99;
				$this->log->logEvent("Authorization Failed",$this->username." is not authorized to access this site.");			
			}else{
				$this->log->logEvent("Authorization Success",$this->username." is authorized to access this site.");			
			}
		return $authorized;
	}	
	
	function doLogout($username) {
			$this->log->logEvent("Log out","User $username logged out.");
	}
}
?>