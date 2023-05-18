'use strict';

function deleteIconListener() {
    const deleteButtons = document.querySelectorAll('table td .deleteIcon');
    for (const button of deleteButtons) {
        button.addEventListener('click', function() {
            switch(this.parentElement.parentElement.parentElement.parentElement.id) {
                case "usersTable":
                    document.location.assign('../../src/controllers/action_delete_user.php');
                    break;
                case "departmentsTable":
                    document.location.assign('../../src/controllers/action_delete_department.php');
                    break;
                case "ticketsTable":
                    document.location.assign('../../src/controllers/action_delete_ticket.php');
                    break;
                default:
                    console.log('Unexpected Table ID.');
                    break;
            }
        });
    }
}

function editIconListener() {
    const deleteButtons = document.querySelectorAll('table td .editIcon');
    for (const button of deleteButtons) {
        button.addEventListener('click', function() {
            switch(this.parentElement.parentElement.parentElement.parentElement.id) {
                case "usersTable":
                    document.location.assign('../../src/controllers/action_edit_user.php');
                    break;
                case "departmentsTable":
                    document.location.assign('../../src/controllers/action_edit_department.php');
                    break;
                default:
                    console.log('Unexpected Table ID.');
                    break;
            }
        });
    }
}

deleteIconListener();
editIconListener();