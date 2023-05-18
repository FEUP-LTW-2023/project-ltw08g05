'use strict';

function deleteIconListener() {
    const deleteButtons = document.querySelectorAll('table td .deleteIcon');
    for (const button of deleteButtons) {
        button.addEventListener('click', function() {
            const tableRow = this.parentElement.parentElement;
            const table = tableRow.parentElement.parentElement;
            const id = tableRow.dataset.userid;
            switch(table.id) {
                case "usersTable":
                    document.location.assign('../../src/controllers/action_delete_user.php?id=' + id);
                    break;
                case "departmentsTable":
                    document.location.assign('../../src/controllers/action_delete_department.php?id=' + id);
                    break;
                case "ticketsTable":
                    document.location.assign('../../src/controllers/action_delete_ticket.php?id=' + id);
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
            const tableRow = this.parentElement.parentElement;
            const table = tableRow.parentElement.parentElement;
            switch(table.id) {
                case "usersTable":
                    //document.location.assign('../../src/controllers/action_edit_user.php');
                    break;
                case "departmentsTable":
                    //document.location.assign('../../src/controllers/action_edit_department.php');
                    break;
                default:
                    console.log('Unexpected Table ID.');
                    break;
            }
            const main = document.getElementById('adminPage');
            const editWindow = document.getElementById('editWindow');
            main.style.filter = 'blur(4px)';
            editWindow.style.display = 'block';
        });
    }
}

function closeEditWindowListener() {
    const closebutton = document.querySelector('#editWindow .closeIcon');
    const main = document.getElementById('adminPage');
    const editWindow = document.querySelector('#editWindow');
    closebutton.addEventListener('click', function() {
        editWindow.style.display = 'none';
        main.style.filter = 'blur(0px)';
    });
}

deleteIconListener();
editIconListener();
closeEditWindowListener();