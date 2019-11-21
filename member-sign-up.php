<?php 
error_reporting(E_ALL);
ini_set('display_errors', 1);

include_once 'myPDO.php';
include_once 'user.php';

$title = "Member Sign Up";
include "includes/header.php";
include "includes/nav.php";
?>

<section class="pageTitle">
    <h1>CS Club Member Sign Up</h1>
</section>

<section class="signupForm">
    <form method="POST">
        <h2>Club Member Sign Up</h2>
        <div>
            <label for="memberFirstName">First Name: </label>
            <input type="text" name="memberFirstName" id="memberFirstName" placeholder="Enter Your First Name" required>
            <br>

            <label for="memberLastName">Last Name: </label>
            <input type="text" name="memberLastName" id="memberLastName" placeholder="Enter Your Last Name" required>
            <br>

            <label for="memberEmail">Email Address: </label>
            <input type="email" name="memberEmail" id="memberEmail" placeholder="Enter Your Email" required>
            <br>

            <label>Availability: </label>
            <ul>
                <li><input type="checkbox" name="date[]" id="mon" value="Monday"><label for="mon">Monday</label> </li>
                <li><input type="checkbox" name="date[]" id="tue" value="Tuesday"><label for="tue">Tuesday</label></li>
                <li><input type="checkbox" name="date[]" id="wed" value="Wednesday"><label for="wed">Wednesday</label></li> 
                <li><input type="checkbox" name="date[]" id="thu" value="Thursday"><label for="thu">Thursday</label></li>
                <li><input type="checkbox" name="date[]" id="fri" value="Friday"><label for="fri">Friday</label></li> 
                <li><input type="checkbox" name="date[]" id="sat" value="Saturday"><label for="sat">Saturday</label></li>
                <li><input type="checkbox" name="date[]" id="sun" value="Sunday"><label for="sun">Sunday</label></li>
            </ul>
            <br>

            <input type="checkbox" name="receiveEmail" id="receiveEmail" value="Yes" checked>
            <label for="receiveEmail">I would like to receive email updates from Highline CS Club</label>
            <br>
            <input type="checkbox" name="clubLeader" id="clubLeader" value="Yes">
            <label for="clubLeader">Please check if you would like to become club leader in the future</label>
            <br>
            
            <input type="submit" name ="submit" value="Submit">
        </div>
    </form>
</section>

<?php

$receiveEmail = "No";
$becomeLeader = "No";
//var_dump($_POST);
//var_dump(PDOStatement::errorInfo());
if(isset($_POST['submit'])) {
    $firstName = $_POST['memberFirstName'];
    $lastName = $_POST['memberLastName'];
    $email = $_POST['memberEmail'];
    $dates = $_POST['date'];
    $availability = implode(' ', $dates);
    if($_POST['receiveEmail']) {
        $receiveEmail = $_POST['receiveEmail'];
    }
    if($_POST['clubLeader']) {
        $becomeLeader = $_POST['clubLeader'];
    }

    $memberSignUp = new user();
    $memberSignUp->insertMemberInfo($firstName, $lastName, $email, $availability, $receiveEmail, $becomeLeader);

    //header("location: member-sign-up.php");
    echo "<script>alert('Thank you! You are now one of us!')</script>";
	echo "<script>location.href ='member-sign-up.php'</script>";
}

?>







<?php 
include "includes/footer.php"; 
?>