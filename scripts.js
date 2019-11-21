// Get the Managing Forms
let editEventPopup = document.getElementById('editEvent');
let addEventPopup = document.getElementById('addEvent');
let deleteEventPopup = document.getElementById('deleteEvent');

let editOpportunitiesPopup = document.getElementById('editOpportunities');
let addOpportunitiesPopup = document.getElementById('addOpportunities');
let deleteOpportunitiesPopup = document.getElementById('deleteOpportunities');

let editMemberPopup = document.getElementById('editMember');
let addMemberPopup = document.getElementById('addMember');
let deleteMemberPopup = document.getElementById('deleteMember');

let editLeaderPopup = document.getElementById('editLeader');
let addLeaderPopup = document.getElementById('addLeader');
let deleteLeaderPopup = document.getElementById('deleteLeader');

let editFAQPopup = document.getElementById('editFAQ');
let addFAQPopup = document.getElementById('addFAQ');
let deleteFAQPopup = document.getElementById('deleteFAQ');

let editDepartmentLinkPopup = document.getElementById('editDepartmentLink');
let addDepartmentLinkPopup = document.getElementById('addDepartmentLink');
let deleteDepartmentLinkPopup = document.getElementById('deleteDepartmentLink');

let editAboutPopup = document.getElementById('editAbout');
let addAboutPopup = document.getElementById('addAbout'); 
let deleteAboutPopup = document.getElementById('deleteAbout');


// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
    if (event.target == editEventPopup) {
        editEvent.style.display = 'none';
    }

    if (event.target == addEventPopup) {
        addEvent.style.display = 'none';
    }
    
    if (event.target == deleteEventPopup) {
        deleteEvent.style.display = 'none';
    }
/*------------------------------------------------------*/
    if (event.target == editOpportunitiesPopup) {
        editOpportunities.style.display = 'none';
    }

    if (event.target == addOpportunitiesPopup) {
        addOpportunities.style.display = 'none';
    }
    
    if (event.target == deleteOpportunitiesPopup) {
        deleteOpportunities.style.display = 'none';
    }
/*------------------------------------------------------*/
    if (event.target == editMemberPopup) {
        editMember.style.display = 'none';
    }

    if (event.target == addMemberPopup) {
        addMember.style.display = 'none';
    }

    if (event.target == deleteMemberPopup) {
        deleteMember.style.display = 'none';
    }
/*------------------------------------------------------*/
    if (event.target == editLeaderPopup) {
        editLeader.style.display = 'none';
    }

    if (event.target == addLeaderPopup) {
        addLeader.style.display = 'none';
    }

    if (event.target == deleteLeaderPopup) {
        deleteLeader.style.display = 'none';
    }
/*------------------------------------------------------*/
    if (event.target == editFAQPopup) {
        editFAQ.style.display = 'none';
    }

    if (event.target == addFAQPopup) {
        addFAQ.style.display = 'none';
    }

    if (event.target == deleteFAQPopup) {
        deleteFAQ.style.display = 'none';
    }
/*------------------------------------------------------*/
    if (event.target == editDepartmentLinkPopup) {
        editDepartmentLink.style.display = 'none';
    }

    if (event.target == addDepartmentLinkPopup) {
        addDepartmentLink.style.display = 'none';
    }

    if (event.target == deleteDepartmentLinkPopup) {
        deleteDepartmentLink.style.display = 'none';
    }

/*------------------------------------------------------*/
    if (event.target == editAboutPopup) {
        editAbout.style.display = 'none';
    }

    if (event.target == addAboutPopup) {
        addAbout.style.display = 'none';
    }

    if (event.target == deleteAboutPopup) {
        deleteAbout.style.display = 'none';
    }




}