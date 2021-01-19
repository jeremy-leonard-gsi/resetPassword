		<div class="row mb-md-3 mb-1">
		<div class="col-md-12">		
		<div class="card">
			<div class="card-header">
				<form id="doResetPassword" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doResetPassword"></form>
				<form id="doLogout" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doLogout"></form>
				<form id="doEventlog" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doEventlog"></form>
				<div class="btn-group float-right">
					<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown" ><i class="fas fa-user"></i>&nbsp;<?php echo $auth->fullName;?></button>
					<div class="dropdown-menu">
						<a class="dropdown-item" href="#" onclick="document.getElementById('doResetPassword').submit();">Reset Passwords</a>			
						<a class="dropdown-item" href="#" onclick="document.getElementById('doEventlog').submit();">Event Log</a>			
						<a class="dropdown-item" href="#" onclick="document.getElementById('doLogout').submit();">Logout</a>			
					</div>		
				</div>
		</div>
		</div>
		</div>
		</div>
