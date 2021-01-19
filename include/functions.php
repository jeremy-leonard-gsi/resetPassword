<?php

function pwvEncrypt($text,$secret,$method="aes-256-cbc") {

	$key = hash("sha256",$secret);

	// IV must be exact 16 chars (128 bit)
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);

	return base64_encode(openssl_encrypt($text, $method, $key, OPENSSL_RAW_DATA, $iv));
}

function pwvDecrypt($text,$secret,$method="aes-256-cbc") {
	
	$key = hash("sha256",$secret);
	
	// IV must be exact 16 chars (128 bit)
	$iv = chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0) . chr(0x0);
	
	return openssl_decrypt(base64_decode($text), $method, $key, OPENSSL_RAW_DATA, $iv);
}

function convertADTime($adTimeStamp) {
	$ADtime = round($adTimeStamp/10000000-11644473600);
	$dateTime = new DateTime("@$ADtime");
	$dateTime->setTimezone( new DateTimeZone("America/Detroit"));
	return $dateTime->format("m-d-Y g:i:s a");
}

function encodeADPassword($password) {
	$newpassword = "\"" . $password . "\"";
	$len = strlen($newpassword);
	$newPassw = "";
	for($i=0;$i<$len;$i++){
   	$newPassw .= "{$newpassword{$i}}\000";
   }
   return $newPassw;
}
?>