<?php
include_once("../config.php");

$stylesheet = file_get_contents($_GET["stylesheet"]);

foreach($_CONFIG["colors"] as $token => $color){
	$stylesheet = str_replace("%%$token%%", $color, $stylesheet);
}
header("Content-type: text/css", true);
echo $stylesheet;
?>