<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand"><?=$_CONFIG['TITLE']?></a>
        <form id="doResetPassword"><input type="hidden" name="module" value="doManageUsers"></form>
        <form id="doLogout"><input type="hidden" name="module" value="doLogout"></form>
        <form id="doEventLog"><input type="hidden" name="module" value="doEventLog"></form>
        <div class="btn-group float-end">
                <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" ><i class="fas fa-user"></i>&nbsp;<?php echo $auth->fullName;?></button>
                <div class="dropdown-menu">
                        <a class="dropdown-item" href="#" onclick="document.getElementById('doManageUsers').submit(); return false;">Manage Users</a>			
                        <?php if($_CONFIG['LOGGING']){ ?>
                            <a class="dropdown-item" href="#" onclick="document.getElementById('doEventLog').submit(); return false;">Event Log</a>
                        <?php }?>
                        <a class="dropdown-item" href="#" onclick="document.getElementById('doLogout').submit(); return false;">Logout</a>			
                </div>		
        </div>
    </div>
</nav>
