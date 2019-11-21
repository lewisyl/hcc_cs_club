<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "FAQs";
include "includes/header.php";
include "includes/nav.php";
?>

<section class="pageTitle">
    <h1>Frequently Asked Questions</h1>
</section>

<section class="toggleData">
    <?php
    $faq = new user();
    $faq->getFAQ();
    ?>
</section>









<?php 
include "includes/footer.php"; 
?>