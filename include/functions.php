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
   	$newPassw .= "{$newpassword[$i]}\000";
   }
   return $newPassw;
}

function convertCammelToSpace($str){
    return ucwords(preg_replace('/([A-Z])/', " $0", $str)," \t\r\n\f\v><");
}

function buildTree($tree, $level = 1){
    $output = '<div class="accordion" id="tree'.$level.'">';
    $item = 0;
    foreach($tree as $name => $branch){
       $item++;
       $output .= '<div class="accordion-item border-0">';
       $output .= '<h2 class="accordion_button m-0" id="level-'.$level.'-item-'.$item.'">';
       $output .= '<button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#child-level-'.$level.'-item-'.$item.'" aria-expanded="true" aria-controls="child-level-'.$level.'-item-'.$item.'">';
       $output .= '<input class="me-2" type="radio" name="selectedOU" value="'.$branch['dn'].'">';
       $output .= $name;
       $output .= '</button>';
       $output .= '</h2>';
       $output .= '<div id="child-level-'.$level.'-item-'.$item.'" class="accordion-collapse collapse" aria-labelledby="level-'.$level.'-item-'.$item.'" data-bs-parent="#tree'.$level.'">';
       $output .= '<div class="accordion-body m-0 p-0 ps-4">';
       if(is_array($branch['children'])){
           $output .= buildTree($branch['children'],$level+1);
       }
       $output .= '</div>';
       $output .= '</div>';
       $output .= '</div>';
    }
   $output .= '</div>';
    return $output;
}
?>