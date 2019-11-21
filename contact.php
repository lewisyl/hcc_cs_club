<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "Contact Us";
include "includes/header.php";
include "includes/nav.php";
?>

<section class="pageTitle">
    <h1>Contact Us</h1>
</section>

<section class="leaders">
    <h2>CS Club Leaders</h2>
    <ul class="clubLeaders">
        <?php 
        $contactPage = new user();
        $contactPage->getClubLeaders();
        ?>
    </ul>
</section>

<hr>

<section class ="faculty">
    <h2>Faculty</h2>
    <ul class="clubFaculty">
        <li class="facultyInfo">
            <img src="https://directory.highline.edu/images/viewimage.php?username=snizami" alt="Syeda Nizami">
            <p>Syeda Nizami</p>
            <a href="https://directory.highline.edu/?person=snizami" target="_blank">Faculty Directory</a>
        </li>
</ul>
</section>
<hr>
<section class="contactForm">
    <h3>Contact Form</h3>
    <form method="POST">
        <label for="to">To: </label>
        <input type="email" id="to" name="leaderFacultyEmail" required>
        <br>
        <label for="subject">Subject: </label>
 		<input type="text" id="subject" name="subject" placeholder="Subject" required>
        <br>
    	<label for="contactEmail">Your E-mail Address: </label>
    	<input type="email" id="contactEmail" name="contactEmail" placeholder="Your E-mail Address" required>
        <br>
        <label for="message">Text Message: </label>
    	<textarea name="message" id="message" rows="5" placeholder="Please Type Your Message Here..." required></textarea>
        <br>
        <input type="submit" value="Submit" name="submit">
    </form>
</section>

<?php

if (isset($_POST['submit'])) {
    $to = $_POST['leaderFacultyEmail'];
    $from = $_POST['contectEmail'];
    $subject = $_POST['subject'];
    $message = $_POST['message'];
    mail($to, $subject, $message, $from);
    echo "<script>alert('Your message has been successfully sent. We will get back to you shortly.')</script>";
    echo "<script>location.href ='contact.php'</script>";
    //echo "<p class='messageSent'>Your message has been successfully sent. We will get back to you shortly.</p></div>";
}

?>


<?php 
include "includes/footer.php"; 
?>