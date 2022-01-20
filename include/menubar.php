<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand">Password Manager</a>
        <form id="doResetPassword" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doResetPassword"></form>
        <form id="doLogout" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doLogout"></form>
        <form id="doEventlog" method="post" action="<?php echo $_SERVER["REQUEST_URI"];?>"><input type="hidden" name="action" value="doEventlog"></form>
        <div class="btn-group float-end">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" ><i class="fas fa-user"></i>&nbsp;<?php echo $auth->fullName;?></button>
                <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="document.getElementById('doResetPassword').submit();">Reset Passwords</a>			
                        <a class="dropdown-item" href="#" onclick="document.getElementById('doEventlog').submit();">Event Log</a>			
                        <a class="dropdown-item" href="#" onclick="document.getElementById('doLogout').submit();">Logout</a>			
                </div>		
        </div>
    </div>
</nav>
