<?php

global $_CONFIG;

$_CONFIG["requireSSL"]=false;

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
	"lastlogontimestamp"=>"Last Logon Time",
    	"memberof"=>"Group Memberships",
        "useraccountcontrol"=>"User Account Control",
        "badpwdcount"=>"Bad Password Count",
        "badpasswordtime"=>"Bad Password Time",
    ];
$_CONFIG["DB_HOST"]="";
$_CONFIG["DB_NAME"]="";
$_CONFIG["DB_USER"]="";
$_CONFIG["DB_PASSWORD"]="";

$_CONFIG["TITLE"]="User Management System";

$_CONFIG["forceUserReset"]=true;

$_CONFIG['HISTORY']=false;

$_CONFIG['allowUACChange']=[
                                'Account Disable',
                                'Account Locked Out',
                                'Password Not Required',
                                'User Cannot Change Password',
                                'Normal Account',
                                'Password Never Expires',
                                'Password Has Expired',
                            ];
$_CONFIG["LOGGING"]=false;

$_CONFIG["ADDUSER"]=true;

$_CONFIG['addUserFields']=[
    'givenName'=>[
        'displayName'=>'First Name',
        'required'=>true,
        'type'=>'text'
    ],
    'sn'=>[
        'displayName'=>'Last Name',
        'required'=>true,
        'type'=>'text'
    ],
    'displayname'=>[
        'displayName'=>'Display Name',
        'required'=>true,
        'type'=>'aggregate',
        'separator'=>' ',
        'data'=>['givenname','sn'],
    ],
    'samaccountname'=>[
        'displayName'=>'User Name',
        'required'=>true,
        'type'=>'aggregate',
        'separator'=>' ',
        'data'=>['givenname','sn'],
    ],
    'title'=>[
        'displayName'=>'Job Title',
        'required'=>true,
        'type'=>'list',
        'data'=>[
            'Accounting Assistant',
            'Activities Director',
            'Adventure Club Program Director',
            'Assistant Principal',
            'Board of Education',
            'Bus Driver',
            'Child Caregiver',
            'Counselor',
            'Curriculum Director',
            'Custodian',
            'Data Management Specialist',
            'Director of Business and Finance',
            'Director of Maintenance',
            'Electrician',
            'Executive Assistant',
            'Food Service',
            'Food Service Director',
            'Grounds Maintenance',
            'Human Resource Specialist',
            'Mechanic',
            'Network Specialist',
            'Principal',
            'School Social Worker',
            'Secretary',
            'Superintendent',
            'Support Staff',
            'Teacher',
            'Technology Director',
            'Transportation Router'
            ]
    ],
    'physicaldeliveryofficename'=>[
        'displayName'=>'Office',
        'required'=>true,
        'type'=>'list',
        'data'=>[
            'Adventure Club',
            'Bus Garage',
            'Business Office',
            'District Office',
            'Eureka Elementary',
            'Gateway North Elementary',
            'Little Wings',
            'Maintenance',
            'Oakview South Elementary',
            'Riley Elementary',
            'St. Johns High School',
            'St. Johns Middle School',
            'STRIVE',
            'Wilson Center',
            ]
    ],
    'department'=>[
        'displayName'=>'Department',
        'required'=>true,
        'type'=>'list',
        'data'=>[
            'Adventure Club',
            'Board of Education',
            'Business Office',
            'Custodial',
            'District Office',
            'Elementary',
            'Food Service',
            'High School',
            'Little Wings',
            'Maintenance Office',
            'Middle School',
            'STRIVE',
            'Technology',
            'Transportation ',
            ]
    ],
    'telephoneNumber'=>[
        'displayName'=>'Telephone Number',
        'required'=>true,
        'type'=>'text',
    ],
    'facsimileTelephoneNumber'=>[
        'displayName'=>'FAX Number',
        'required'=>true,
        'type'=>'text',
    ],
    'mail'=>[
        'displayName'=>'E-mail Address',
        'required'=>true,
        'type'=>'text',
    ],
    'employeeid'=>[
        'displayName'=>'Employee ID',
        'required'=>true,
        'type'=>'text',
    ],
    'employeetype'=>[
        'displayName'=>'Employee Type',
        'required'=>true,
        'type'=>'list',
        'data'=>['Staff','Student']
    ],
    'info'=>[
        'displayName'=>'Notes',
        'required'=>true,
        'type'=>'textarea',
    ],
    'password'=>[
        'displayName'=>'Password',
        'required'=>true,
        'type'=>'password'
    ],
    'dn'=>[
        'displayName'=>'Location',
        'required'=>true,
        'type'=>'dn'
    ],
];

$_CONFIG["CONTAINERS"]=['domain','organizationalunit','container'];