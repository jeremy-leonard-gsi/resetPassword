<?php
session_start();
$module="";

include("include/config.base.php");
include("config.php");
include_once("include/functions.php");
include_once("include/auth.class.php");
include_once("include/resetPassword.class.php");
require_once("include/log.class.php");
require_once("include/userPasswords.class.php");

$appLog = new applicationLog($_CONFIG["DB_HOST"],$_CONFIG["DB_USER"],$_CONFIG["DB_PASSWORD"],$_CONFIG["DB_NAME"]);

// Check for SSL.
if($_CONFIG["requireSSL"]){
	if (!(isset($_SERVER["HTTPS"])) || !($_SERVER["HTTPS"]=="on")){
		header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
	}
}

//Move submitted data from _POST to _SESSION

if(isset($_POST["username"])) {
	$_SESSION["username"] = $_POST["username"];
}

if(isset($_POST["password"])) {
	$_SESSION["password"] = $_POST["password"];
}

// Test the username and password.

if(isset($_SESSION["username"]) && isset($_SESSION["password"])) {
	$auth = new resetPassword($appLog,
        $_CONFIG["LDAP_URI"],
        $_CONFIG["LDAP_BASE"],
        $_CONFIG["LDAP_DN"],
        $_CONFIG["LDAP_SECRET"],
        $_CONFIG["LDAP_USERATTR"],
        $_CONFIG["LDAP_FILTER"],
        $_CONFIG["LDAP_MEMBER_ATTR"],
        $_CONFIG["LDAP_AUTHORIZED"],
        $_CONFIG["LDAP_FULLNAME_ATTR"]);
	$_SESSION["authenticated"] = $auth->doLogin($_SESSION["username"],$_SESSION["password"]);
	if($_SESSION["authenticated"]) {
            if($_SESSION["authorized"]=$auth->authorized()) {
                $module="resetPassword";
            }
	}
}else {
    $module = "auth";	
}

if(isset($_POST["action"]) && $_POST["action"]=="doLogout") {
    $auth->doLogout($_SESSION["username"]);
    unset($_SESSION["username"]);
    unset($_SESSION["password"]);
    unset($_SESSION["authenticated"]);
    unset($_SESSION["authorized"]);	
    $module="auth";
}

if(isset($_POST["action"]) && $_POST["action"]=="doResetPassword") {
    $module="resetPassword";
}

if(isset($_POST["action"]) && $_POST["action"]=="doEventlog") {
    $module="eventlog";
}

$title = $_CONFIG["TITLE"];			

switch($module) {
    case "eventlog":
        include("include/eventlog.php");		
        break;
    case "resetPassword":
        include("include/resetPassword.php");
        break;			
    case "auth":
    default:
        include("include/auth.php");
        break;
}
?>
