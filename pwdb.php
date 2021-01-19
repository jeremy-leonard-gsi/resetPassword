<?php
// curl -d '{"username":"gracon", "password":"TempPass1"}' -H "Content-Type: application/json" -X POST http://localhost/~jeremyl/pwdb/index.php
include('config.php');
include('include/functions.php');
include('include/userPasswords.class.php');

$pwdb = new userPasswords($_CONFIG['PDO']);

if($_SERVER['REQUEST_METHOD']=='POST') {
	$json = file_get_contents('php://input');
	$data = json_decode($json);
	$data->password=pwvEncrypt(urldecode($data->password),$_CONFIG['AES']);	
	$pwdb->setUserPassword($data->username, $data->password);
/*		
	print_r($json);
	print_r($data);
	print_r($_CONFIG);
*/	
}
?>