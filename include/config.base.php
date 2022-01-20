<?php

global $_CONFIG;

$_CONFIG["requireSSL"]=false;

#$_CONFIG["LDAP_URI"]="Some LDAP URI";
#$_CONFIG["LDAP_BASE"]="Some Base";
#$_CONFIG["LDAP_DN"]="Some UPN or FDN";
#$_CONFIG["LDAP_SECRET"]='Some Secret';
$_CONFIG["LDAP_USERATTR"]="samaccountname";
$_CONFIG["LDAP_FILTER"]="(&(objectclass=user))";
$_CONFIG["LDAP_MEMBER_ATTR"]="member";
$_CONFIG["LDAP_AUTHORIZED"]=["Domain Admins"];
$_CONFIG["LDAP_FULLNAME_ATTR"]="displayname";
$_CONFIG["LDAP_DISPLAYED_ATTRS"]=[
	$_CONFIG["LDAP_FULLNAME_ATTR"]=>"Full Name",
	"givenname"=>"First Name",
	"initials"=>"Middle Initial",
	"sn"=>"Last Name",
	"samaccountname"=>"User Name",
	"tmclssid"=>"Student ID",
	"mail"=>"Email Address",
	"employeetype"=>"Account Type",
	"telephonenumber"=>"Telephone Number",
	"pwdlastset"=>"Password Last Reset",
	"description"=>"Grade Level",
	"employeenumber"=>"Student Number",
	"lastlogontimestamp"=>"Last Logon Time"
    ];
$_CONFIG["DB_HOST"]="localhost";
$_CONFIG["DB_NAME"]="resetPassworddb";
$_CONFIG["DB_USER"]="resetPassword";
$_CONFIG["DB_PASSWORD"]="nLOsdfljkuq5D799EY3";

$_CONFIG["DEBUG"]=false;
#$_CONFIG["DEBUG"]=true;

$_CONFIG["TITLE"]="Reset Password System";

$_CONFIG["forceUserReset"]=true;
