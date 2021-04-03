<?php
if($sphinx->check_connection()){
	include_once 'app/'. $sphinx->default();

}else{
//include_once 'app/'. $sphinx->default();
if (isset($_POST['submit'])) {
$written="<?php 
include 'sphinx.php';"; 
$written.='

$dbhost="'.$_POST['host'].'";
$dbuser="'.$_POST['username'].'";
$dbpass="'.$_POST['password'].'";
$db="'.$_POST['database'].'";';

$written.='
$config = array(
	"connect" 		=> array($dbhost,$dbuser,$dbpass,$db),
	"header"  		=> "/", 
	"footer"  		=> "/", 
	"default_file"	=> "/",
	"file_include"	=> true
);
$sphinx = new sphinx($config);

$sphinx->file();
	
';
//$sphinx->create_table();
	$file='system/config.php';
	// Check the existence of file
	if(file_exists($file)){
	    // Open the file for reading
	    $handle = fopen($file, "w") or die("ERROR: Cannot open the file.");
	    fwrite($handle, $written);
	    /* Some code to be executed */
	        
	    // Closing the file handle
	    fclose($handle);
	} else{
	    echo "ERROR: File does not exist.";
	}
	redirect($_SERVER['REQUEST_URI'],'refresh');
//header('Location: '.$_SERVER['REQUEST_URI']);
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Setup</title>
</head>
<body>
	<div class="text-center" style="padding:50px 0">
	<div class="logo">Wellcome To Sphinx</div>
	<!-- Main Form -->
	<div class="login-form-1">
		<form id="login-form" class="text-left" method="post" action="#">
			<div class="login-form-main-message"></div>
			<div class="main-login-form">
				<div class="login-group">
					<div class="form-group">
						<label for="" class="sr-only">host</label>
						<input type="text" class="form-control" id="host" name="host" placeholder="Host Name" value="localhost">
					</div>
					<div class="form-group">
						<label for="" class="sr-only">user</label>
						<input type="text" class="form-control" id="username" name="username" placeholder="Username" value="root">
					</div>
					<div class="form-group">
						<label for="" class="sr-only">Password</label>
						<input type="password" class="form-control" id="password" name="password" placeholder="Password">
					</div>
					<div class="form-group">
						<label for="" class="sr-only">database</label>
						<input type="text" class="form-control" id="database" name="database" placeholder="Database name">
					</div>
					<div class="form-group">
						
					</div>

				</div>
				<button type="submit" name="submit" class="login-button"><i class="fa fa-chevron-right"></i></button>
			</div>
			
		</form>
	</div>
	<!-- end:Main Form -->
</div>
</body>
</html>
<?php } ?>