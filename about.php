<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "About Us";
include "includes/header.php";
include "includes/nav.php";
?>

<section class="pageTitle">
    <h1>About Highline College Computer Science Club</h1>
</section>

<div class="about">
    <article>
        <h2>About Computer Science Club</h2>
        <h3>Club Goal</h3>
        <p>The Highline Computer Science Club strives to be a club that will represent the computer science students of Highline College. Our goal is to provide the students the opportunity to advance the knowledge they have gained throughout their academic time, and apply it to real world problems that computer scientist's will face in the private or public workforce.</p>
        <h3>Our Plan</h3>
        <p>Our plan to provide opportunities to our members and fellow students by offering consistent interview building workshops, resume workshops, and by bringing in guest speakers so to give students the ability to grow their network by interacting with industry professionals.</p>
        <h3>Membership</h3>
        <p>Any Highline College student that is interested in learning about the field of Computer Science and is willing to make a commitment to the club and provide an appropriate environment is able to join.</p>
        <ul>
            <li>All members and officers of the CS club are expected to abide by the Student Life Policy.</li>
            <li>Members of the Highline CS Club will not discriminate on the basis of race, color, age, religion, sex, ethnicity, nationality, disability, sexual orientation, or veteran status.</li>
        </ul>
    </article>
</div>
    
<div class="club_Do_Offer"> 
    <section>
        <h2>What CS Club Does</h2>
        <ul>
            <?php 
            $about = new user();
            $about->getClubDo();
            ?>
        </ul>
    </section>
    <section>
        <h2>What CS Club Offers</h2>
        <ul>
            <?php 
            $about->getClubOffer();
            ?>
        </ul>
    </section>
</div>

<div class="csDepLinks">
<h3>Links to CS Departments: </h3>
<?php
$about->getLinksToCsDepartment();
?>
</div>







<?php 
include "includes/footer.php"; 
?>