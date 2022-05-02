<?php
$request->filter=FILTER_SANITIZE_STRING;
$selectUser=$request->selectedUser;

if(isset($_POST["method"])) {	
	switch($_POST["method"]) {
                case "updateUAC":
                        $checked = filter_input(INPUT_POST, 'checked', FILTER_VALIDATE_BOOLEAN);
                        $userdn = urldecode(filter_input(INPUT_POST, 'userdn', FILTER_SANITIZE_STRING));
                        $uac = filter_input(INPUT_POST, 'uac', FILTER_SANITIZE_NUMBER_INT);
                        $value = filter_input(INPUT_POST, 'value', FILTER_SANITIZE_NUMBER_INT);
                        if($checked){
                            $newUAC = intval($uac) | intval($value);
                        }else{
                            $newUAC = intval($uac) & ~intval($value);
                        }
                        $results = $auth->updateUAC($userdn, $newUAC);
                        header("Content-Type: application/json");
                        echo $results;
                        exit();
                    break;
                case 'resetBadPwdCount':
                    error_log('resetPwdCount, resetPassword');
                    $userdn = urldecode(filter_input(INPUT_POST, 'userdn', FILTER_SANITIZE_STRING));
                    $results = $auth->resetBadPwdCount($userdn);
                    header("Content-Type: application/json");
                    echo $results;
                    exit();                    
                    break;
		case "setUserPassword":
			if(isset($_POST["userdn"]) AND isset($_POST["userPassword1"])) {
				$userDN = urldecode($_POST["userdn"]);
				$password = $_POST["userPassword1"];
				if(isset($_POST["forceReset"]) AND (
					strtolower($_POST["forceReset"])=='true' 
					OR strtolower($_POST["forceReset"])=='yes' 
					OR $_POST["forceReset"]=='1')
					) {
					$forceUserChange=true;
				}else{
					$forceUserChange=false;
				}
				$results = $auth->resetUserPassword($userDN,$password,$forceUserChange);				
				header("Content-Type: application/json");
				echo $results;
			}
			exit();
			break;
		case "getFilteredUsers":
				 $users = $auth->getFilteredUsers($_POST["filter"]);
				 for($u=0;$u<$users["count"];$u++) {
					$userList[$users[$u]["dn"]]=$users[$u][$_CONFIG["LDAP_FULLNAME_ATTR"]][0];
				}
				asort($userList);
				$output="";
				foreach($userList as $dn => $name){
					$output .= "<li class=\"nav-item m-md-1 systemNavItem\">\n";
					$output .= "<a class=\"nav-link";
					$output .= "\"  style=\"display: inline;\" href=\"#\" onclick=\"selectUser('".urlencode($dn)."');\">$name</a>\n";
					$output .= "</li>\n";
				 }
				header('Content-Type: text/html');
				echo $output;
				exit();
			break;
		case "getUserDetails":
			$users = $auth->getUserInfo(urldecode($_POST["userid"]));
			$user = $users[0];
                        $userGroups = '';
                        $output ='<div class="card-header">';
			$output .= "<div class=\"navbar navbar-expand-lg navbar-light bg-light float-end\">";
			$output .= "<span class=\"navbar-brand\">".$user[$_CONFIG["LDAP_FULLNAME_ATTR"]][0]."</span>";
			$output .= "<ul class=\"nav\">";
			$output .= "<li class=\"nav-item dropdown\">";
			$output .= "<a class=\"nav-link dropdown-toggle\" id=\"menuTitle\" href=\"#\" role=\"button\" data-bs-toggle=\"dropdown\">General Information</a>";
			$output .= "<div class=\"dropdown-menu\">";
			$output .= "<a class=\"dropdown-item\" data-bs-toggle=\"collapse\" data-bs-target=\".userinfo\" href=\"#\">General Information</a>";
			$output .= "<a class=\"dropdown-item\" data-bs-toggle=\"collapse\" data-bs-target=\".userinfo\" href=\"#\" onclick=\"$( '#menuTitle' ).text('Group Membership');\">Group Memberships</a>";
			$output .= "</div>";
			$output .= "</li>";
			$output .= "</ul>";
			$output .= "</div>\n";
			$output .= "</div>\n";
			$output .= "<div class=\"card-body p-0\">\n";
			$output .= "<div id=\"userAlert\" class=\"alert fade show\" style=\"display: none;\" role=\"alert\"></div>\n";
			$output .= "<div class=\"card border-0\">";
			$output .= "<div class=\"card-body collapse show userinfo\" id=\"generalInfo\">";		
			$output .="<table class=\"table\">";
			foreach($_CONFIG["LDAP_DISPLAYED_ATTRS"] as $key => $label) {
				if(isset($user[$key]) AND is_array($user[$key])) {
					if($key!="memberof") {
						$output .="<tr><th data-value=".$user[$key][0].">$label</th><td>";
					}
					for($a=0;$a<$user[$key]["count"];$a++) {
						switch($key) {
							case "lastlogontimestamp":
                                                        case "badpasswordtime":
							case "lastlogon":
								$output .= "<span>".convertADTime($user[$key][$a])."</span>";
								break;
							case "pwdlastset":
								$output .= "<span>".convertADTime($user[$key][$a])."</span>";
								if($_CONFIG['HISTORY']) $output .= "<button class=\"btn btn-primary ml-2\" data-toggle=\"modal\" data-target=\"#userPasswordsModal\" onclick=\"getUserPasswords('".$user['samaccountname'][0]."')\">History</button>";
								break;
							case "useraccountcontrol":
							case "ms-ds-user-account-control-computed":
								$useraccoutncontrol["Account Disable"]['value']=$user[$key][$a] & 2;
								$useraccoutncontrol["Account Disable"]['flag']= 2;
								$useraccoutncontrol["Password Not Required"]['value']=$user[$key][$a] & 32;
								$useraccoutncontrol["Password Not Required"]['flag']= 32;
//								$useraccoutncontrol["User Cannot Change Password"]['value']=$user[$key][$a] & 64;
//								$useraccoutncontrol["User Cannot Change Password"]['flag']= 64;
//								$useraccoutncontrol["Normal Account"]['value']=$user[$key][$a] & 512;
//								$useraccoutncontrol["Normal Account"]['flag']= 512;
								$useraccoutncontrol["Password Never Expires"]['value']=$user[$key][$a] & 65536;
								$useraccoutncontrol["Password Never Expires"]['flag']= 65536;
//								$useraccoutncontrol["Password Has Expired"]['value']=$user[$key][$a] & 8388608;
//								$useraccoutncontrol["Password Has Expired"]['flag']= 8388608;
								foreach($useraccoutncontrol as $uacLabel => $uacValue){
                                                                    $output .= '<div class="form-check form-switch">'
                                                                           . '<label class="form-check-label" for="id'.$uacLabel.'">'.$uacLabel.'</label>'
                                                                           . '<input class="form-check-input" type="checkbox" role="switch" id="id'.$uacLabel.'" name="'.$uacLabel.'"';
                                                                    if($uacValue['value'] !== 0){
                                                                        $output .= ' checked ';
                                                                    }
                                                                    if(in_array($uacLabel,$_CONFIG['allowUACChange'])!==true){
                                                                        $output .= ' onclick="return false;" ';
                                                                    }else{
                                                                        $output .= ' onclick="updateUAC(event,\''.$_POST["userid"].'\',\''.$user[$key][$a].'\',\''.$uacValue['flag'].'\');" ';
                                                                    }
                                                                    $output .= '" data-state="'.$uacValue['value'].'">'
                                                                        . '</div>';
								}								
								break;
							case "memberof":
									$userGroups .= "<span>".$user[$key][$a]."</span><br>";								
								break;
                                                        case "badpwdcount":
                                                            $output .= "<span>".$user[$key][$a].'</span><button class="btn btn-primary ms-3" onclick="resetBadPwdCount(event,\''.$_POST["userid"].'\' )">Reset to Zero</button>';
                                                            break;
                                                        case "mobile":
                                                        case "telephonenumber":
                                                            $output .= '<div><a href="tel:'.$user[$key][$a].'">'.$user[$key][$a].'</a></div>';
                                                            break;
                                                        case "mail":
                                                            $output .= '<div><a href="mailto:'.$user[$key][$a].'">'.$user[$key][$a].'</a></div>';
                                                            break;
							default:							
							$output .= "<div>".$user[$key][$a]."</div>";
						}			
					}
					$output .= "</td></tr>";	
				}elseif(isset($user[$key])) {
					$output .="<tr><th>$label</th><td><span>".$user[$key]."</span></td></tr>";
				}
			}
			$output .= "<tr><th>Reset Password</th><td><button class=\"btn btn-primary\" data-bs-toggle=\"modal\" data-bs-target=\"#resetPassword\" onclick=\"setUserId('".$_POST["userid"]."');\">Reset Password</button></td></tr>\n";
			$output .= "</table>\n</div>";
			$output .= "<div class=\"card-body collapse userinfo\" id=\"userGroups\">";
			$output .= $userGroups;
			$output .= "</div>";
			$output .= "\n</div>";
			header('Content-Type: text/html');
			echo $output;
			exit();
			break;
			case 'getUserPasswords':
				if(isset($_POST['userid'])) {
					$pwdb = new userPasswords($_CONFIG['PDO']);
					$passwords = $pwdb->getPasswords($_POST['userid']);
					header('Content-Type: text/html');
					echo "<table>\n";					
					foreach($passwords as $key => $user){
						echo "<tr><td>".pwvDecrypt($user['password'],$_CONFIG['AES'])."</td><td>".$user["timestamp"]."</td></tr>\n";
					}
					echo "<table>\n";
				}
				exit();
			break;			
	}	
}

include("include/header.php");
include("include/menubar.php");
?>
                <div class="row mt-md-3 mx-2">	
                    <div class="col-md-3">
			<div class="card">
				<h5 class="card-header">Users</h5>
				<div class="card-body">
                    <form autocomplete="off">                                        
						<div class="input-group">
                                    <input  
                                           id="filterUsers" name="filterUsers" 
                                           class="form-control mr-md-1" 
                                           type="search" placeholder="Search" 
                                           aria-label="Filter" 
                                           autocomplete="off">
									<button class="btn btn-outline" type="button" onclick="getFilteredUsers();return false;">Search</button>
						</div>
                    </form>
					<ul id="navUsers" class="nav flex-column nav-pills">
					</ul>				
				</div>			
			</div>
			</div>
			<div class="col-md-9">
				<div id="userDetails" class="card">
					<div class="card-header"> 
					<h5 class="card-title">User Details</h5>
					</div>
					<div class="card-body">
					</div>
				</div>
		<div class="modal fade" id="resetPassword">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Reset Password</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal"></button>
					</div>
					<div class="modal-body">
					<div id="passwordAlert" class="alert fade show" style="display: none;" role="alert"></div>
						<input id="user-userid" name="userid" type="hidden">
                                                <input id="user-password1" onblur="validatePassword(document.getElementById('user-password1').value);" name="user-password1" class="form-control mt-1" placeholder="Password" type="password" autocomplete="new-password">				
                                                <input id="user-password2" onkeyup="validatePassword2(document.getElementById('user-password2').value);" name="user-password2" class="form-control mt-1" placeholder="Retype Password" type="password" autocomplete="new-password">
						<?php if(isset($_CONFIG["forceUserReset"]) AND $_CONFIG["forceUserReset"]) {?>
								<input id="user-forceResetId" name="forceReset" type="checkbox" class="d-none" checked="checked">
						<?php
						}else{
						?>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" name="forceReset" type="checkbox" role="switch" id="user-forceResetId">
                                                  <label class="form-check-label" for="user-forceResetId">Force user to reset their password on next logon?</label>
                                                </div>
					<?php
					}
					?>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
						<button id="btnSaveNewPassword" type="submit" class="btn btn-primary" disabled="disabled" data-bs-dismiss="modal" onclick="updatePassword();">Save</button></div>
				</div>
			</div>
		</div>  	
		<div class="modal fade" id="userPasswordsModal">
			<div class="modal-dialog">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title">Password History</h5>
						<button type="button" class="close" data-dismiss="modal">×</button>
					</div>
					<div class="modal-body" id="userPasswordData">
					</div>
					<div class="modal-footer">
						<button id="btnClosePasswords" type="submit" class="btn btn-primary" data-dismiss="modal">Close</button></div>
				</div>
			</div>
		</div>  	
	</div>
</div>
<?php
include("include/footer.php");
?>
