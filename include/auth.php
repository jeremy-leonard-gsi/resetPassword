<?php
include("include/header.php");
?>
		<div id="login" class="shadow card mt-4">
			<div class="card-header">
				<div class="card-title">Login</div>			
			</div>
			<div class="card-body">
				<div class="card-content">
				<?php
					if(isset($_SESSION["authenticated"]) AND $_SESSION["authenticated"] == false) {
						echo "<div class=\"alert alert-danger\">Login Failure. ".$auth->error."</div>";
					}elseif(isset($_SESSION["authenticated"]) AND $_SESSION["authenticated"] AND isset($_SESSION["authorized"]) AND $_SESSION["authorized"] == false ) {
						echo "<div class=\"alert alert-danger\">You account is not authorized to access this site.</div>";
					}						
					?>
					<form id="frmLogin" action="<?php echo $_SERVER["REQUEST_URI"];?> " method="post" autocomplete="off">
						<input name="username" type="text" class="form-control" placeholder="Username" autofocus autocomplete="off">					
						<input name="password" type="password" class="form-control mt-md-2" placeholder="Password" >
						<button class="btn btn-primary form-control mt-md-2">Login</button>					
					</form>
				</div>			
			</div>		
		</div>
<?php
include("include/footer.php");
?>