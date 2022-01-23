<?php
session_start();

error_log('Including Scripts.');
include("include/config.base.php");
include("config.php");
include_once("include/functions.php");
include_once("include/auth.class.php");
include_once("include/resetPassword.class.php");
require_once("include/log.class.php");
require_once("include/userPasswords.class.php");
require_once('include/request.class.php');

$request = new request;
$module = $request->module ?? 'auth';

error_log('Creating appLog');

$appLog = new applicationLog($_CONFIG["DB_HOST"],$_CONFIG["DB_USER"],$_CONFIG["DB_PASSWORD"],$_CONFIG["DB_NAME"]);

error_log('Checking for SSL requirements.');

// Check for SSL.
if($_CONFIG["requireSSL"]){
	if (!(isset($_SERVER["HTTPS"])) || !($_SERVER["HTTPS"]=="on")){
		header("Location: https://".$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]);
	}
}

//Move submitted data from _POST to _SESSION
error_log('Moving _POST to _SESSION');
if(isset($_POST["username"])) {
	$_SESSION["username"] = $_POST["username"];
}

if(isset($_POST["password"])) {
	$_SESSION["password"] = $_POST["password"];
}

// Test the username and password.

error_log('Testing for authentication');

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
	if($_SESSION["authenticated"]){
            if(($_SESSION["authorized"]=$auth->authorized())!=true) {
            $module="auth";
            error_log('Not Authenticated 1');
            }
	}else{
            $module="auth";
        }
}else {
    $module = "auth";	
    error_log('Not Authenticated 2');
}
error_log('Setting Title');

$title = $_CONFIG["TITLE"];

error_log($module);

switch($module){
    case 'doResetPassword':
        include("include/resetPassword.php");
        break;
    case 'doEventLog';
        include("include/eventlog.php");
        break;
    case 'doLogout':
        $auth->doLogout($_SESSION["username"]);
        unset($_SESSION["username"]);
        unset($_SESSION["password"]);
        unset($_SESSION["authenticated"]);
        unset($_SESSION["authorized"]);
        header('Location: '.$_SERVER['SCRIPT_NAME']);
    default:
        include("include/auth.php");
}