<?php 
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "Admin Login";
include "includes/header.php";
include "includes/nav.php";


if(isset($_POST['adminLogin'])) {

    $_SESSION['username'] = $_POST['username'];
    $_SESSION['password'] = $_POST['password'];
    $_SESSION['rememberMe'] = $_POST['rememberMe'];
    $_SESSION['timer'] = time();

    header("location: admin-home.php");
}

if(isset($_SESSION['timer'])){
	
	if((time() - $_SESSION['timer'])> 60*60*24*7){
		session_destroy();
	}else{
		$_SESSION['timer'] = time();
	}
}
?>

<section class="pageTitle">
    <h1>Club Admin Login</h1>
</section>

<form class="adminLoginForm" method="POST" action="">
    
    <h2>Welcome 
    
    </h2>
    
    <div>
        <label for="username"><b>Username/Email</b></label>
        <input type="text" placeholder="Enter Username/Email" name="username" value="<?php if(isset($_SESSION['rememberMe'])) {echo $_SESSION['username'];}?>">

        <label for="password"><b>Password</b></label>
        <input type="password" placeholder="Enter Password" name="password" value="<?php if(isset($_SESSION['rememberMe'])) {echo $_SESSION['password'];}?>">

        <input type="checkbox" name="rememberMe" id="rememberMe" checked>
        <label for="rememberMe">Remember Me</label>
        <br>

        <input type="submit" name="adminLogin" value="Login">
    </div>

</form>



<?php 
include "includes/footer.php"; 
?>