<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "Member Opportunities";
include "includes/header.php";
include "includes/nav.php";
?>

<section class="pageTitle">
    <h1>Opportunities for CS Club Members</h1>
</section>

<section class="toggleData">
    <?php 
    $memberOpp = new user();
    $memberOpp->getOpportunities();
    ?>
</section>








<?php 
include "includes/footer.php"; 
?>