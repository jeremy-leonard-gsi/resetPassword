<?php
class UserPasswords{
	private $DB,$username,$password;
	
	public function __construct($PDO) {
		$this->DB = new PDO($PDO['dsn'],$PDO['user'],$PDO['password']);
	}
	
	public function setUserPassword($username,$password) {
		$salt = random_bytes(8);
		$query = 'INSERT INTO passwords (`username`,`password`) VALUES (:USERNAME, :PASSWORD);';
		$stmt = $this->DB->prepare($query);
		$stmt->bindValue(':USERNAME',strtolower($username));
		$stmt->bindValue(':PASSWORD',pwvEncrypt($salt.$password,hash('sha256',strtolower($username))));
		$stmt->execute();
	}	
	
	public function getPasswords($username) {
		
		$query = "SELECT * FROM passwords WHERE username = :USERNAME ORDER BY timestamp DESC;";
		$stmt = $this->DB->prepare($query);
		$stmt->bindValue(':USERNAME',strtolower($username));
		$stmt->execute();
		
		$users = array();
		foreach($stmt->fetchAll(PDO::FETCH_ASSOC) as $id => $user){
			$users[$id]['id']=$user['id'];	
			$users[$id]['username']=$user['username'];	
			$users[$id]['password']=substr(pwvDecrypt($user['password'],hash('sha256',strtolower($user['username']))),8);	
			$users[$id]['timestamp']=$user['timestamp'];	
		}
		return $users;
	}
}
?>