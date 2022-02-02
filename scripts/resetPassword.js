function setUserId(userid) {
	document.getElementById('user-userid').value=userid;
}

function resetBadPwdCount($event, $userdn){
    $user = $.post( "index.php", { module: 'doResetPassword', method: "resetBadPwdCount", userdn: $userdn } );
    $user.done(function ( data ){
        selectUser($userdn, data);
    });    
}

function updateUAC($event, $userdn, $uac, $value) {
    $user = $.post( "index.php", { module: 'doResetPassword', method: "updateUAC", userdn: $userdn, uac: $uac, value: $value, checked: $event.srcElement.checked } );
    $user.done(function ( data ){
        selectUser($userdn, data);
    });
}

function updatePassword() {
	$userdn = document.getElementById('user-userid').value;
	$password = document.getElementById('user-password1').value;
	$forceUserReset = document.getElementById('user-forceResetId').checked;
	$user = $.post( "index.php" , { module: 'doResetPassword', method: "setUserPassword", userdn: $userdn, userPassword1: $password, forceReset: $forceUserReset} );
	$user.done(function ( data ){
		
		$userdn = document.getElementById('user-userid').value;
			
		selectUser($userdn, data);
			
		document.getElementById('user-password1').value='';
		document.getElementById('user-password2').value='';
		}
	);
}
function selectUser($userid,result) {
	result = (typeof result !== 'undefined') ?  result : null;
	$users = $.post( "index.php" , { module: 'doResetPassword', method: "getUserDetails", userid: $userid } );
	$users.done(function ( data ) {		
		$( '#userDetails' ).empty().append( data );
		
		if (result != null) {
		switch(result['number']) {
			case 0:
				$( '#userAlert' ).addClass('alert-success');
			break;			
			default:
				$( '#userAlert' ).addClass('alert-danger');			
		}		
		$( '#userAlert' ).html(result['message']);
		$( '#userAlert' ).show();
		}
				
	});
}
function getUserPasswords($username,result) {
	result = (typeof result !== 'undefined') ?  result : null;
	$passwords = $.post( "index.php" , { module: 'doResetPassword', method: "getUserPasswords", userid: $username } );
	$passwords.done(function ( data ) {		
		$( '#userPasswordData' ).empty().append( data );
		console.log(data);

		if (result != null) {
		switch(result['number']) {
			case 0:
				$( '#userAlert' ).addClass('alert-success');
			break;			
			default:
				$( '#userAlert' ).addClass('alert-danger');			
		}		
		$( '#userAlert' ).html(result['message']);
		$( '#userAlert' ).show();
		}
				
	});
}

function getFilteredUsers(event) {
	$filter = document.getElementById('filterUsers').value;
	$users = $.post( "index.php" , { module: "doResetPassword", method: "getFilteredUsers", filter: $filter } );
	$users.done(function ( data ) {		
		$( '#navUsers' ).empty().append( data );		
	});
}
function validatePassword($password) {
	$complexity = 0;
	$pat = new RegExp('[0-9]');
	if ($pat.test($password)) {
		$complexity++;
	}
	$pat = new RegExp('[a-z]');
	if ($pat.test($password)) {
		$complexity++;
	}
	$pat = new RegExp('[A-Z]');	
	if ($pat.test($password)) {
		$complexity++;
	}
	$pat = new RegExp('[~`!@#$%^&*()\-_=+[{\]}\\|;:\'",<.>/?]');
	if ($pat.test($password)) {
		$complexity++;
	}
	if ($complexity >= 3 && $password.length>=8){
		$( "#passwordAlert" ).hide();
		return true;	
	}else{
		document.getElementById('passwordAlert').innerHTML="Password entered does not meet complexity requirements!";
		$( '#passwordAlert' ).addClass('alert-danger');
		$( "#passwordAlert" ).show();
		return false;	
	}
}
function validatePassword2($password) {
	if (validatePassword(document.getElementById('user-password1').value) && $password == document.getElementById('user-password1').value) {
		$( "#passwordAlert" ).hide();
		$( "#btnSaveNewPassword" ).prop('disabled', false);
		return true;
	}else if (validatePassword(document.getElementById('user-password1').value)){
		$( "#btnSaveNewPassword" ).prop('disabled', 'disabled');
		document.getElementById('passwordAlert').innerHTML="Password do not match!";
		$( '#passwordAlert' ).addClass('alert-danger');
		$( "#passwordAlert" ).show();
		return false;
	}	
}