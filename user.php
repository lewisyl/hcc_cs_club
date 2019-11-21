<?php
class user extends myPDO {
    //--------------------------------------Home Page--------------------------------------
    public function getPastEvents() {
        $pastEvents = $this->connect()->query("SELECT * FROM events WHERE DATE(date) < DATE(CURDATE()) ORDER BY date DESC");
        while ($row = $pastEvents->fetch()) {
            echo 
            "<div class='pastSlides'><div class='pastSlide-title'><p>" . $row['title'] . "</p><p>" . $row['date'] . "</p></div><div class='pastSlide-content'><p>" . $row['detail'] . "</p></div></div>";
        }

    }

    public function getUpcomingEvents() {
        $upcomingEvents = $this->connect()->query("SELECT * FROM events WHERE DATE(date) > DATE(CURDATE())");
        while ($row = $upcomingEvents->fetch()) {
            echo 
            "<div class='upcomingSlides'><div class='pastSlide-title'><p>" . $row['title'] . "</p><p>" . $row['date'] . "</p></div><div class='pastSlide-content'><p>" . $row['detail'] . "</p></div></div>";
        }

    }

    public function getThisMonthEvent() {
        $eventForCurrentMonth = $this->connect()->query("SELECT * FROM events WHERE MONTH(date) = MONTH(CURDATE()) AND YEAR(date) = YEAR(CURDATE())");
        while ($row = $eventForCurrentMonth->fetch()) {
            echo
            "<li><p>" . $row['title'] . "</p><p>" . $row['date'] . "</p><p>" . $row['detail'] . "</p></li>";
        }
    }

    




    //--------------------------------------Member Sign Up--------------------------------------
    public function insertMemberInfo ($firstName, $lastName, $email, $availability, $receiveEmail, $becomeLeader) {
            $insertMember = $this->connect()->prepare("INSERT INTO club_members VALUES (null,?,?,?,?,?,?)");
            $insertMember->execute([$firstName, $lastName, $email, $availability, $receiveEmail, $becomeLeader]);
    }






    //--------------------------------------Member Opportunities--------------------------------------
    public function getOpportunities() {
        $memberOpportunities = $this->connect()->query("SELECT * FROM opportunity ORDER BY id DESC LIMIT 10");
        while ($row = $memberOpportunities->fetch()) {
            /*echo
            "<li>" . $row["title"] . "
                <div>
                    <p>". $row["detail"] ."</p>
                </div>
            </li>";*/

            echo "
            <section class='toggleData'>
                <input type='checkbox' class='toggle' id='".$row["title"]."'>
                <label for='".$row["title"]."' class='toggleLabel'>".$row["title"]."</label>
                <div class='dropDownContainer'>
                    <div class='dropDownContainer-inner'><pre>".$row["detail"]."</pre></div>
                </div>
            </section>";
        }
    }





    //--------------------------------------FAQ--------------------------------------
    public function getFAQ() {
        $FAQ = $this->connect()->query("SELECT * FROM faq");
        while ($row = $FAQ->fetch()) {
            echo "
            <section class='toggleData'>
                <input type='checkbox' class='toggle' id='".$row["question"]."'>
                <label for='".$row["question"]."' class='toggleLabel'>".$row["question"]."</label>
                <div class='dropDownContainer'>
                    <div class='dropDownContainer-inner'><pre>".$row["answer"]."</pre></div>
                </div>
            </section>";
        }
    }



    //--------------------------------------About--------------------------------------
    public function getClubDo() {
        $clubDo = $this->connect()->query("SELECT club_do FROM about");
        while ($row = $clubDo->fetch()) {
            echo
            "<li>". $row["club_do"] ."</li>";
        }
    }

    public function getClubOffer() {
        $clubOffer = $this->connect()->query("SELECT club_offer FROM about");
        while ($row = $clubOffer->fetch()) {
            echo
            "<li>". $row["club_offer"] ."</li>";
        }
    } 

    public function getLinksToCsDepartment() {
        $csDepartmentLinks = $this->connect()->query("SELECT * FROM department_links");
        while ($row = $csDepartmentLinks->fetch()) {
            echo
            "<button><a href='" . $row["link"] . "' target='_blank'>" . $row['department_name'] ."</a></button>";
        }
    }




    //--------------------------------------Contact Us--------------------------------------
    public function getClubLeaders() {
        $clubLeaders = $this->connect()->query("SELECT * FROM contact_club_leaders");
        while ($row = $clubLeaders->fetch()) {
            echo "
            <li class='leaderInfo'>
            <img src='" . $row['picture'] . "' alt='" . $row['name'] . "'><br>
            <p>" . $row['title'] . "</p>
            <p>" . $row['name'] . "</p>
            <p>" . $row['email'] . "</p>
        </li>";
        }
    }












    //--------------------------------------Admin Home--------------------------------------
//varifying admin username & password
    public function verification() {
        if(isset($_SESSION['username']) && isset($_SESSION['password'])) {
            $loginInfo = $this->connect()->query("SELECT * FROM admin");
            $queryLoginInfo = $loginInfo->fetch();
            
            if(strtolower(trim($_SESSION['username'])) == ($queryLoginInfo['username'] || $queryLoginInfo['email']) 
            && trim($_SESSION['password']) == ($queryLoginInfo['password'])) {
                echo "
                <div class='adminHome'>
                    <section class='adminHomeLeft'>"; 
                    $this->listEvents();
                    $this->manageEvents();

                    $this->listOpportunities();
                    $this->manageOpportunities();

                    $this->listClubMembers();
                    $this->manageClubMembers();

                    $this->listClubLeaders();
                    $this->manageLeaders();

                    $this->listFAQs(); 
                    $this->manageFAQs();

                    $this->listAbout();
                    $this->manageAbout();

                    $this->listDepartmentLinks();
                    $this->manageDepartmentLink();
                echo "
                    </section>
                    <aside class='adminHomeRight'>";
                    $this->sendGroupEmail();
                    $this->announcement();
                echo "
                    </aside>   
                </div>
                <script src='scripts.js'></script>";
            } else {
                unset($_SESSION['username']);
                unset($_SESSION['password']);
                unset($_SESSION['rememberMe']);
                echo "<p class='loginFailedMsg'>Login Failed.<br>Please Go Back to the <a href='admin-login.php'>Admin Login Page</a> and Try Again.</p>";
            }
        } else {
            unset($_SESSION['username']);
            unset($_SESSION['password']);
            echo "<p class='loginFailedMsg'>Login Failed.<br>Please Go Back to the <a href='admin-login.php'>Admin Login Page</a> and Try Again.</p>";
        }
    }
 




    //--------------------------------------Admin Home Functions--------------------------------------



//display club members data
    private function listClubMembers() {
        $memberList = $this->connect()->query("SELECT * FROM club_members");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>Club Members List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>Member ID</th>
                <th abbr='First Name' scope='col' title='First Name'>First Name</th>
                <th abbr='Last Name' scope='col' title='Last Name'>Last Name</th>
                <th abbr='Email' scope='col' title='Email'>Email</th>
                <th abbr='Availability' scope='col' title='Availability'>Availability</th>
                <th abbr='Receive Email' scope='col' title='Receive Email'>Receive Email</th>
                <th abbr='Become Leader' scope='col' title='Become Leader'>Become Leader</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $memberList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[first_name]</td>
                    <td>$row[last_name]</td>
                    <td>$row[email]</td>
                    <td>$row[availability]</td>
                    <td>$row[receive_email]</td>
                    <td>$row[become_leader]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";
    }

//display Club Leaders data
    private function listClubLeaders() {
        $leaderList = $this->connect()->query("SELECT * FROM contact_club_leaders");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>Club Leaders List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>Leader ID</th>
                <th abbr='Name' scope='col' title='Name'>Name</th>
                <th abbr='Title' scope='col' title='Title'>Title</th>
                <th abbr='Email' scope='col' title='Email'>Email</th>
                <th abbr='Picture' scope='col' title='Picture'>Picture</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $leaderList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[name]</td>
                    <td>$row[title]</td>
                    <td>$row[email]</td>
                    <td><img src='$row[picture]'></td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";
    }

//display Events
    private function listEvents() {
        $eventList = $this->connect()->query("SELECT * FROM events ORDER BY date DESC");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>Event List</strong>
            </caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>Event ID</th>
                <th abbr='Title' scope='col' title='Title'>Title</th>
                <th abbr='Date' scope='col' title='Date'>Date</th>
                <th abbr='Detail' scope='col' title='Detail'>Detail</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $eventList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[title]</td>
                    <td>$row[date]</td>
                    <td class='longDetail'>$row[detail]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";
    }

//display FAQs
    private function listFAQs() {
        $faqList = $this->connect()->query("SELECT * FROM faq");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>FAQs List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>FAQ ID</th>
                <th abbr='Question' scope='col' title='Question'>Question</th>
                <th abbr='Answer' scope='col' title='Answer'>Answer</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $faqList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[question]</td>
                    <td class='longDetail'>$row[answer]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";  
    }

//display FAQs
    private function listDepartmentLinks() {
        $departmentLinksList = $this->connect()->query("SELECT * FROM department_links");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>Department List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>ID</th>
                <th abbr='Department' scope='col' title='Department'>Department</th>
                <th abbr='Link' scope='col' title='Link'>Link</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $departmentLinksList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[department_name]</td>
                    <td>$row[link]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";  
    }


//display Opportunity
    private function listOpportunities() {
        $opportunityList = $this->connect()->query("SELECT * FROM opportunity");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>Opportunities List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>Opportunity ID</th>
                <th abbr='Title' scope='col' title='Title'>Title</th>
                <th abbr='Detail' scope='col' title='Detail'>Detail</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $opportunityList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td>$row[title]</td>
                    <td class='longDetail'>$row[detail]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";  
    }


//display About Club - Do & Offers
    private function listAbout() {
        $aboutList = $this->connect()->query("SELECT * FROM about");
        echo "
        <div class='dbContent'>
        <table class='dbList'>
            <caption><strong>About List</strong></caption>
            <thead>
                <tr>
                <th abbr='ID' scope='col' title='ID'>About ID</th>
                <th abbr='Title' scope='col' title='Title'>Club Does</th>
                <th abbr='Detail' scope='col' title='Detail'>Club Offers</th>
                </tr>
            </thead>
            <tbody>";
        while ($row = $aboutList->fetch()) {
            echo "
                <tr>
                    <td>$row[id]</td>
                    <td class='longDetail'>$row[club_do]</td>
                    <td class='longDetail'>$row[club_offer]</td>
                </tr>";
        }
        echo "
            </tbody>
        </table>
        </div>";
    }


//sending email to all members who signed up for receiving email.
    private function sendGroupEmail() {
        $receiveEmailMembers = $this->connect()->query("SELECT email FROM club_members WHERE receive_email='Yes'");
        $to = $receiveEmailMembers->fetch();
        $from = 'admin@csclub.highline.edu';

        echo "
        <div>
        <form method='POST' class='sendGroupEmail'>
        <h3>Send Group Email to Members</h3>
            <label for='groupMsgSubject'>Subject: </label>
            <input type='text' id='groupMsgSubject' name='groupMsgSubject' placeholder='Subject' required>
            <br>
            <label for='groupMsgDetail'>Text Message: </label>
            <textarea name='groupMsgDetail' id='groupMsgDetail' rows='5' placeholder='Please Type Your Message Here...' required></textarea>
            <br>
            <input type='submit' value='Submit' name='sendGroupEmail'>
        </form>
        </div>
        ";
        if (isset($_POST['sendGroupEmail'])) {
            $subject = $_POST['groupMsgSubject'];
            $message = $_POST['groupMsgDetail'];
            mail($to, $subject, $message, $from);
            echo "<script>alert('Your message has been successfully sent. We will get back to you shortly.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }
    }

//Making announcement
    private function announcement() {
        if(isset($_POST['submitAnnouncement'])) {
            $announcement = $_POST['announcement'];
            $_SESSION['announcement'] = $announcement;
            $_SESSION['announcementTimer'] = time();
        
            $insertAnnouncement = $this->connect()->prepare("INSERT INTO announcement VALUES (null,'$announcement')");
            $insertAnnouncement->execute([$announcement]);
        }

        if(isset($_POST['cleanAnnouncement'])) {
            unset($_SESSION['announcement']);
            unset($_SESSION['announcementTimer']);
        }

        echo "
        <form method='POST' class='makeAnnouncement'>
            <h3>Make an Announcement</h3>
            <label for='announcement'>Announcement text</label>
            <textarea id='announcement' name='announcement' rows='5' placeholder='Announcement Text Here'></textarea>
            <br>
            <input type='submit' value='Set' name='submitAnnouncement'>
            <input type='submit' value='Unset' name='cleanAnnouncement'>
        </form>";

        if(isset($_SESSION['announcementTimer'])) {
            if((time() - $_SESSION['announcementTimer'])> 60*60*24){
                unset($_SESSION['announcementTimer']);
            } else{
                $_SESSION['announcementTimer'] = time();
            }
        }
    }
//Display announcement session on the Home Page
    public function displayAnnouncement() {
        if(isset($_SESSION['announcement'])) {
            echo "<marquee behavior='scroll' direction='left' class='announcement'>".$_SESSION['announcement']."</marquee>";
        }
    }









// ---------------------Management App, including Buttons, Forms and JavaScript----------------------------
    private function manageEvents() {
        //Update Data
        if(isset($_POST['editEventSubmit'])){
            $id = $_POST['eventID'];
            $stm = $_POST;
            $removeID = array_shift($stm);
            var_dump($_POST);

            foreach($stm as $var => $value) {
                if(!empty($value)) {
                    $updateEvent = $this->connect()->prepare("UPDATE events SET $var='$value' WHERE id='$id'");
                    $updateEvent->execute();

                    echo "<script>alert('Event Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            }
        }

        //Inset Data
        if(isset($_POST['addEventSubmit'])){
            $eventTitle = $_POST['title'];
            $eventDate = $_POST['date'];
            $eventDetail = $_POST['detail'];

            $insertOpportunity = $this->connect()->prepare("INSERT INTO events VALUES (null, '$eventTitle', '$eventDate', '$eventDetail')");
            $insertOpportunity->execute();

            echo "<script>alert('New Event Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteEventSubmit'])){
            $id = $_POST['eventID'];

            $deleteEvent = $this->connect->prepare("DELETE FROM events WHERE id='$id'");
            $deleteEvent->execute();

            echo "<script>alert('Event Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        

        //Froms and Buttons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editEvent').style.display='block'\" style='width:auto;'>Edit Event</button>
            <button onclick=\"document.getElementById('addEvent').style.display='block'\" style='width:auto;'>Add Event</button>
            <button onclick=\"document.getElementById('deleteEvent').style.display='block'\" style='width:auto;'>Delete Event</button>
        </div>
        
	    <div id='editEvent' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='eventID'><strong>Event ID</strong></label>
                <input type='text' placeholder='Event ID is REQUIRED' id='eventID' name='eventID' required>
                <br>

                <label for='eventTitle'><strong>Event Title</strong></label>
                <input type='text' placeholder='Edit Title' id='eventTitle' name='title'>
                <br>

                <label for='eventDate'><strong>Event Date</strong></label>
                <input type='date' id='eventDate' name='date'>
                <br>
                
                <label for='eventDetail'><strong>Event Detail</strong></label>
                <textarea row='5' placeholder='Edit Title' id='eventDetail' name='detail'></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editEventSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editEvent').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addEvent' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='eventTitle'><strong>Event Title</strong></label>
                <input type='text' placeholder='Add Title' id='eventTitle' name='title' required>
                <br>

                <label for='eventDate'><strong>Event Date</strong></label>
                <input type='date' id='eventDate' name='date' required>
                <br>

                <label for='eventDetail'><strong>Event Detail</strong></label>
                <textarea row='5' placeholder='Add Detail' id='eventDetail' name='detail' required></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addEventSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addEvent').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteEvent' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='eventID'><strong>Event ID</strong></label>
                <input type='text' placeholder='Event ID is REQUIRED' id='eventID' name='eventID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteEventSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteEvent').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageOpportunities() {
        //Update Data
        if(isset($_POST['editOpportunitySubmit'])){
            $id = $_POST['opportunityID'];
            $stm = $_POST;
            $removeID = array_shift($stm);

            foreach($stm as $var => $value) {
                if(!empty($value)) {
                    $updateOpportunity = $this->connect()->prepare("UPDATE opportunity SET $var='$value' WHERE id='$id'");
                    $updateOpportunity->execute();

                    echo "<script>alert('Opportunity Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            }
        }

        //Inset Data
        if(isset($_POST['addOpportunitySubmit'])){
            $opportunityTitle = $_POST['title'];
            $opportunityDetail = $_POST['detail'];

            $insertOpportunity = $this->connect()->prepare("INSERT INTO opportunity VALUES (null, '$opportunityTitle', '$opportunityDetail')");
            $insertOpportunity->execute();

            echo "<script>alert('New Opportunity Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteOpportunitySubmit'])){
            $id = $_POST['opportunityID'];

            $deleteOpportunity = $this->connect->prepare("DELETE FROM opportunity WHERE id='$id'");
            $deleteOpportunity->execute();

            echo "<script>alert('Opportunity Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        

        //Froms and Buttons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editOpportunities').style.display='block'\" style='width:auto;'>Edit Opp.</button>
            <button onclick=\"document.getElementById('addOpportunities').style.display='block'\" style='width:auto;'>Add Opp.</button>
            <button onclick=\"document.getElementById('deleteOpportunities').style.display='block'\" style='width:auto;'>Delete Opp.</button>
        </div>
        
	    <div id='editOpportunities' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='opportunityID'><strong>Opportunity ID</strong></label>
                <input type='text' placeholder='Opportunity ID is REQUIRED' id='opportunityID' name='opportunityID' required>
                <br>

                <label for='opportunityTitle'><strong>Opportunity Title</strong></label>
                <input type='text' placeholder='Edit Title' id='opportunityTitle' name='title'>
                <br>

                <label for='opportunityDetail'><strong>Opportunity Detail</strong></label>
                <textarea row='5' placeholder='Edit Title' id='opportunityDetail' name='detail'></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editOpportunitySubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editOpportunities').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addOpportunities' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='opportunityTitle'><strong>Opportunity Title</strong></label>
                <input type='text' placeholder='Add Title' id='opportunityTitle' name='title' required>
                <br>

                <label for='opportunityDetail'><strong>Opportunity Detail</strong></label>
                <textarea row='5' placeholder='Add Title' id='opportunityDetail' name='detail' required></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addOpportunitySubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addOpportunities').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteOpportunities' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='opportunityID'><strong>Opportunity ID</strong></label>
                <input type='text' placeholder='Opportunity ID is REQUIRED' id='opportunityID' name='opportunityID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteOpportunitySubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteOpportunities').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageClubMembers() {
        //Update Data
        if(isset($_POST['editMemberSubmit'])){
            $id = $_POST['memberID'];
            $stm = $_POST;
            $removeID = array_shift($stm);
            //Make availability array to string
            //$arrayAvailability = $_POST['availability'];
            //$stringAvailability = implode(' ', $arrayAvailability);
            //replace the availability array in the $stm with string
            //$newStm = array_replace($stm, $stringAvailability);
            //var_dump($newStm);

            foreach($stm as $var => $value) {
                if(!empty($value)) {
                    //convert availability array to string that can be written into DB
                    if($var='availability') {
                        $stringValue = implode(' ', $value);

                        $updateMember = $this->connect()->prepare("UPDATE club_members SET $var='$stringValue' WHERE id='$id'");
                        $updateMember->execute();
                    } else {
                    $updateMember = $this->connect()->prepare("UPDATE club_members SET $var='$value' WHERE id='$id'");
                    $updateMember->execute();
                    }

                    echo "<script>alert('Member Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            }
        }

        //Inset Data
        if(isset($_POST['addMemberSubmit'])){
            $memberFirstName = $_POST['first_name'];
            $memberLastName = $_POST['last_name'];
            $memberEmail = $_POST['email'];
            $memberAvailability = implode(' ', $_POST['availability']);
            $memberReceiveEmail = $_POST['receive_email'];
            $memberBecomeLeader = $_POST['become_leader'];

            $insertMember = $this->connect()->prepare("INSERT INTO club_members VALUES (null, '$memberFirstName', '$memberLastName', '$memberEmail', '$memberAvailability', '$memberReceiveEmail', '$memberBecomeLeader')");
            $insertMember->execute();

            echo "<script>alert('New Member Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteMemberSubmit'])){
            $id = $_POST['memberID'];

            $deleteLeader = $this->connect->prepare("DELETE FROM club_members WHERE id='$id'");
            $deleteLeader->execute();

            echo "<script>alert('Member Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        

        //Froms and Buttons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editMember').style.display='block'\" style='width:auto;'>Edit Member Info</button>
            <button onclick=\"document.getElementById('addMember').style.display='block'\" style='width:auto;'>Add Member</button>
            <button onclick=\"document.getElementById('deleteMember').style.display='block'\" style='width:auto;'>Delete Member</button>
        </div>
        
	    <div id='editMember' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='memberID'><strong>Member ID</strong></label>
                <input type='text' placeholder='Member ID is REQUIRED' id='memberID' name='memberID' required>
                <br>

                <label for='memberFirstName'><strong>First Name</strong></label>
                <input type='text' placeholder='Edit First Name' id='memberFirstName' name='first_name'>
                <br>

                <label for='memberLastName'><strong>Last Name</strong></label>
                <input type='text' placeholder='Edit Last Name' id='memberLastName' name='last_name'>
                <br>

                <label for='memberEmail'><strong>Email</strong></label>
                <input type='email' placeholder='Edit Email Address' id='memberEmail' name='email'>
                <br>

                <label for='memberAvailability'><strong>Availability</strong></label>
                <ul>
                    <li><input type='checkbox' name='availability[]' id='mon' value='Monday'><label for='mon'>Monday</label> </li>
                    <li><input type='checkbox' name='availability[]' id='tue' value='Tuesday'><label for='tue'>Tuesday</label></li>
                    <li><input type='checkbox' name='availability[]' id='wed' value='Wednesday'><label for='wed'>Wednesday</label></li> 
                    <li><input type='checkbox' name='availability[]' id='thu' value='Thursday'><label for='thu'>Thursday</label></li>
                    <li><input type='checkbox' name='availability[]' id='fri' value='Friday'><label for='fri'>Friday</label></li> 
                    <li><input type='checkbox' name='availability[]' id='sat' value='Saturday'><label for='sat'>Saturday</label></li>
                    <li><input type='checkbox' name='availability[]' id='sun' value='Sunday'><label for='sun'>Sunday</label></li>
                </ul>
                <br>

                <label for='memberReceiveEmail'>Receive Email</label>
                <select name='receive_email' id='memberReceiveEmail'>
                    <option value=''>Select One</option>
                    <option value='Yes'>Yes</option>
                    <option value='No'>No</option>
                </select>
                <br>
                
                <label for='memberBecomeLeader'>Become Club Leader</label>
                <select name='become_leader' id='memberBecomeLeader'>
                    <option value=''>Select One</option>
                    <option value='Yes'>Yes</option>
                    <option value='No'>No</option>
                </select>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editMemberSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editMember').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addMember' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='memberFirstName'><strong>First Name</strong></label>
                <input type='text' placeholder='Enter First Name' id='memberFirstName' name='first_name' required>
                <br>

                <label for='memberLastName'><strong>Last Name</strong></label>
                <input type='text' placeholder='Enter Last Name' id='memberLastName' name='last_name' required>
                <br>

                <label for='memberEmail'><strong>Email</strong></label>
                <input type='email' placeholder='Enter Email Address' id='memberEmail' name='email' required>
                <br>

                <label for='memberAvailability'><strong>Availability</strong></label>
                <ul>
                    <li><input type='checkbox' name='availability[]' id='mon' value='Monday'><label for='mon'>Monday</label> </li>
                    <li><input type='checkbox' name='availability[]' id='tue' value='Tuesday'><label for='tue'>Tuesday</label></li>
                    <li><input type='checkbox' name='availability[]' id='wed' value='Wednesday'><label for='wed'>Wednesday</label></li> 
                    <li><input type='checkbox' name='availability[]' id='thu' value='Thursday'><label for='thu'>Thursday</label></li>
                    <li><input type='checkbox' name='availability[]' id='fri' value='Friday'><label for='fri'>Friday</label></li> 
                    <li><input type='checkbox' name='availability[]' id='sat' value='Saturday'><label for='sat'>Saturday</label></li>
                    <li><input type='checkbox' name='availability[]' id='sun' value='Sunday'><label for='sun'>Sunday</label></li>
                </ul>
                <br>

                <label for='memberReceiveEmail'>Receive Email</label>
                <select name='receive_email' id='memberReceiveEmail' required>
                    <option value=''>Select One</option>
                    <option value='Yes'>Yes</option>
                    <option value='No'>No</option>
                </select>
                <br>
                
                <label for='memberBecomeLeader'>Become Club Leader</label>
                <select name='become_leader' id='memberBecomeLeader' required>
                    <option value=''>Select One</option>
                    <option value='Yes'>Yes</option>
                    <option value='No'>No</option>
                </select>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addMemberSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addMember').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteMember' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='memberID'><strong>Member ID</strong></label>
                <input type='text' placeholder='Member ID is REQUIRED' id='memberID' name='memberID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteMemberSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteMember').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageLeaders() {
        //Update Data
        if(isset($_POST['editLeaderSubmit'])){
            $id = $_POST['leaderID'];
            $stm = $_POST;
            $removeID = array_shift($stm);

            foreach($stm as $var => $value) {
                if(!empty($value)) {
                    $updateLeader = $this->connect()->prepare("UPDATE contact_club_leaders SET $var='$value' WHERE id='$id'");
                    $updateLeader->execute();

                    echo "<script>alert('Leader Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            }
        }

        //Inset Data
        if(isset($_POST['addLeaderSubmit'])){
            $leaderName = $_POST['name'];
            $leaderTitle = $_POST['title'];
            $leaderEmail = $_POST['email'];
            $leaderPicture =$_POST['picture'];

            $insertLeader = $this->connect()->prepare("INSERT INTO contact_club_leaders VALUES (null,'$leaderName', '$leaderTitle', '$leaderEmail', '$leaderPicture')");
            $insertLeader->execute();

            echo "<script>alert('New Leader Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteLeaderSubmit'])){
            $id = $_POST['leaderID'];

            $deleteLeader = $this->connect->prepare("DELETE FROM contact_club_leaders WHERE id='$id'");
            $deleteLeader->execute();

            echo "<script>alert('Leader Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        
        //Froms and Buttons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editLeader').style.display='block'\" style='width:auto;'>Edit Leader</button>
            <button onclick=\"document.getElementById('addLeader').style.display='block'\" style='width:auto;'>Add Leader</button>
            <button onclick=\"document.getElementById('deleteLeader').style.display='block'\" style='width:auto;'>Delete Leader</button>
        </div>
        
	    <div id='editLeader' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='leaderID'><strong>Leader ID</strong></label>
                <input type='text' placeholder='Leader ID is REQUIRED' id='leaderID' name='leaderID' required>
                <br>

                <label for='leaderName'><strong>Leader's Name</strong></label>
                <input type='text' placeholder='Edit Full Name' id='leaderName' name='name'>
                <br>

                <label for='leaderTitle'><strong>Leader's Title</strong></label>
                <input type='text' placeholder='Edit Leader's Title' id='leaderTitle' name='title'>
                <br>

                <label for='leaderEmail'><strong>Email</strong></label>
                <input type='email' placeholder='Edit Email Address' id='leaderEmail' name='email'>
                <br>

                <label for='leaderPicture'><strong>Leader's Picture URL</strong></label>
                <input type='url' pattern='https://.*' placeholder='Picture URL' id='leaderPicture' name='picture'>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editLeaderSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editLeader').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addLeader' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='leaderName'><strong>Leader's Name</strong></label>
                <input type='text' placeholder='Add Full Name' id='leaderName' name='name' required>
                <br>

                <label for='leaderTitle'><strong>Leader's Title</strong></label>
                <input type='text' placeholder='Add Leader's Title' id='leaderTitle' name='title' required>
                <br>

                <label for='leaderEmail'><strong>Email</strong></label>
                <input type='email' placeholder='Add Email Address' id='leaderEmail' name='email' required>
                <br>

                <label for='leaderPicture'><strong>Leader's Picture URL</strong></label>
                <input type='url' pattern='https://.*' placeholder='Add Picture URL' id='leaderPicture' name='picture' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addLeaderSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addLeader').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteLeader' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='eventID'><strong>Opportunity ID</strong></label>
                <input type='text' placeholder='Leader ID is REQUIRED' id='eventID' name='eventID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteLeaderSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteLeader').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageFAQs() {
        //Update Data
        if(isset($_POST['editFAQSubmit'])){
            $id = $_POST['faqID'];
            $stm = $_POST;
            $removeID = array_shift($stm);

            foreach($stm as $var => $value) {
                if(!empty($value)) {
                    $updateFAQ = $this->connect()->prepare("UPDATE faq SET $var='$value' WHERE id='$id'");
                    $updateFAQ->execute();

                    echo "<script>alert('FAQ Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            }
        }

        //Inset Data
        if(isset($_POST['addFAQSubmit'])){
            $faqQuestion = $_POST['question'];
            $faqAnswer = $_POST['answer'];

            $insertFAQ = $this->connect()->prepare("INSERT INTO faq VALUES (null,'$faqQuestion', '$faqAnswer')");
            $insertFAQ->execute();

            echo "<script>alert('New FAQ Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteFAQSubmit'])){
            $id = $_POST['faqID'];

            $deleteFAQ = $this->connect->prepare("DELETE FROM faq WHERE id='$id'");
            $deleteFAQ->execute();

            echo "<script>alert('FAQ Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }


        //Froms and Buttons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editFAQ').style.display='block'\" style='width:auto;'>Edit FAQ</button>
            <button onclick=\"document.getElementById('addFAQ').style.display='block'\" style='width:auto;'>Add FAQ</button>
            <button onclick=\"document.getElementById('deleteFAQ').style.display='block'\" style='width:auto;'>Delete FAQ</button>
        </div>
        
	    <div id='editFAQ' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='faqID'><strong>FAQ ID</strong></label>
                <input type='text' placeholder='FAQ ID is REQUIRED' id='faqID' name='faqID' required>
                <br>

                <label for='faqQuestion'><strong>FAQ Question</strong></label>
                <textarea row='5' placeholder='Edit FAQ Question' id='faqQuestion' name='question'></textarea>
                <br>

                <label for='faqAnswer'><strong>FAQ Answer</strong></label>
                <textarea row='5' placeholder='Edit FAQ Answer' id='faqAnswer' name='answer'></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editFAQSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editFAQ').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addFAQ' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='faqQuestion'><strong>FAQ Question</strong></label>
                <textarea row='5' placeholder='Edit FAQ Question' id='faqQuestion' name='question' required></textarea>
                <br>

                <label for='faqAnswer'><strong>FAQ Answer</strong></label>
                <textarea row='5' placeholder='Edit FAQ Answer' id='faqAnswer' name='answer' required></textarea>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addFAQSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addFAQ').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteFAQ' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='faqID'><strong>FAQ ID</strong></label>
                <input type='text' placeholder='FAQ ID is REQUIRED' id='faqID' name='faqID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteFAQSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteFAQ').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageDepartmentLink() {
        //Update data
        if(isset($_POST['editDepartmentLinkSubmit'])) {
            $id=$_POST['departmentID'];
            $stm = $_POST;
            $removeID = array_shift($stm);
            //var_dump($stm);

            foreach($stm as $var => $value) {
                //echo "\$stm[$var] => $value.\n";
                if(!empty($value)) {
                    $updateDepartment = $this->connect()->prepare("UPDATE department_links SET $var='$value' WHERE id='$id'");
                    $updateDepartment->execute();

                    echo "<script>alert('Department Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            } 
        }

        //Insert Data
        if(isset($_POST['addDepartmentLinkSubmit'])) {
            $departmentName=$_POST['department_name'];
            $departmentURL=$_POST['link'];

            $insertDepartment = $this->connect()->prepare("INSERT INTO department_links VALUES (null, '$departmentName', '$departmentURL')");
            $insertDepartment->execute();

            echo "<script>alert('New Department Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteDepartmentLinkSubmit'])) {
            $id = $_POST['departmentID'];

            $deleteDepartment = $this->connect()->prepare("DELETE FROM department_links WHERE id='$id'");
            $deleteDepartment->execute();

            echo "<script>alert('Department Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }


        //Forms and Bottons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editDepartmentLink').style.display='block'\" style='width:auto;'>Edit Department</button>
            <button onclick=\"document.getElementById('addDepartmentLink').style.display='block'\" style='width:auto;'>Add Department</button>
            <button onclick=\"document.getElementById('deleteDepartmentLink').style.display='block'\" style='width:auto;'>Delete Department</button>
        </div>
        
	    <div id='editDepartmentLink' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='departmentID'><strong>Department ID</strong></label>
                <input type='text' placeholder='Department ID is REQUIRED' id='departmentID' name='departmentID' required>
                <br>

                <label for='departmentName'><strong>Department Name</strong></label>
                <input type='text' placeholder='Edit Department Name' id='departmentName' name='department_name'>
                <br>

                <label for='departmentURL'><strong>Department URL</strong></label>
                <input type='url' pattern='https://.*' placeholder='Edit Department URL' id='departmentURL' name='link'>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editDepartmentLinkSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editDepartmentLink').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addDepartmentLink' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='departmentName'><strong>Department Name</strong></label>
                <input type='text' placeholder='Enter Department Name' id='departmentName' name='department_name'>
                <br>

                <label for='departmentURL'><strong>Department URL</strong></label>
                <input type='url' pattern='https://.*' placeholder='Enter Department URL' id='departmentURL' name='link'>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addDepartmentLinkSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addDepartmentLink').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteDepartmentLink' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='departmentID'><strong>Department ID</strong></label>
                <input type='text' placeholder='Department ID is REQUIRED' id='departmentID' name='departmentID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteDepartmentLinkSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteDepartmentLink').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



    private function manageAbout() {
        //Update data
        if(isset($_POST['editAboutSubmit'])) {
            $id=$_POST['aboutID'];
            $stm = $_POST;
            $removeID = array_shift($stm);
            //var_dump($stm);

            foreach($stm as $var => $value) {
                //echo "\$stm[$var] => $value.\n";
                if(!empty($value)) {
                    $updateAbout = $this->connect()->prepare("UPDATE about SET $var='$value' WHERE id='$id'");
                    $updateAbout->execute();

                    echo "<script>alert('About Info Update Successed.')</script>";
                    echo "<script>location.href ='admin-home.php'</script>";
                }
            } 
        }

        //Insert Data
        if(isset($_POST['addAboutSubmit'])) {
            $clubDo=$_POST['club_do'];
            $clubOffer=$_POST['club_offer'];

            $insertAbout = $this->connect()->prepare("INSERT INTO about VALUES (null, '$clubDo', '$clubOffer')");
            $insertAbout->execute();

            echo "<script>alert('New About Successfully Added.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }

        //Delete Data
        if(isset($_POST['deleteAboutSubmit'])) {
            $id = $_POST['departmentID'];

            $deleteAbout = $this->connect()->prepare("DELETE FROM about WHERE id='$id'");
            $deleteAbout->execute();

            echo "<script>alert('About Deleted.')</script>";
            echo "<script>location.href ='admin-home.php'</script>";
        }


        //Forms and Bottons with JS
        echo "
        <div class='managingButtons'>
            <button onclick=\"document.getElementById('editAbout').style.display='block'\" style='width:auto;'>Edit About</button>
            <button onclick=\"document.getElementById('addAbout').style.display='block'\" style='width:auto;'>Add About</button>
            <button onclick=\"document.getElementById('deleteAbout').style.display='block'\" style='width:auto;'>Delete About</button>
        </div>
        
	    <div id='editAbout' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='aboutID'><strong>About ID</strong></label>
                <input type='text' placeholder='About ID is REQUIRED' id='aboutID' name='aboutID' required>
                <br>

                <label for='clubDo'><strong>What Does Club Do</strong></label>
                <input type='text' placeholder='Edit Club Does' id='clubDo' name='club_do'>
                <br>

                <label for='clubOffer'><strong>What Does Club Offer</strong></label>
                <input type='text' placeholder='Edit Club Offers' id='clubOffer' name='club_offer'>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='editAboutSubmit'>Submit Edit</button>
                    <button type='button' onclick=\"document.getElementById('editAbout').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
		    </form>
	    </div>

        <div id='addAbout' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='clubDo'><strong>What Does Club Do</strong></label>
                <input type='text' placeholder='Enter What Club Does' id='clubDo' name='club_do'>
                <br>

                <label for='clubOffer'><strong>What Does Club Offer</strong></label>
                <input type='url' pattern='https://.*' placeholder='Enter What Club Offers' id='clubOffer' name='club_offer'>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='addAboutSubmit'>Submit Add</button>
                    <button type='button' onclick=\"document.getElementById('addAbout').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>

        <div id='deleteAbout' class='managingForms'>
            <form class='managingContent animate' action='' method='POST'>
                <label for='aboutID'><strong>About ID</strong></label>
                <input type='text' placeholder='About ID is REQUIRED' id='aboutID' name='aboutID' required>
                <br>

                <div class='submitCancelbtn'>
                    <button type='submit' class='submitbtn' name='deleteAboutSubmit'>Submit Delete</button>
                    <button type='button' onclick=\"document.getElementById('deleteAbout').style.display='none'\" class='cancelbtn'>Cancel</button>
                </div>
            </form>
        </div>
        <hr>
        ";
    }



















}









