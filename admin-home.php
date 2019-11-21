<?php
session_start();
include_once 'myPDO.php';
include_once 'user.php';

$title = "Admin Home";
include "includes/header.php";
include "includes/nav.php";
?>

<?php
//var_dump($_POST);
$adminLogin = new user();
$adminLogin->verification();

?>


<?php 
include "includes/footer.php"; 
?>