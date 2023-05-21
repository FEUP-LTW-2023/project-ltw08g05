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

            const main = document.getElementById('adminPage');
            const editWindow = document.getElementById('editWindow');
            const userEditMenu = document.getElementById('user_edit_menu');
            const departmentEditMenu = document.getElementById('department_edit_menu');

            main.style.filter = 'blur(4px)';
            editWindow.style.display = 'block';

            switch(table.id) {
                case "usersTable":
                    const userType = this.parentElement.previousElementSibling.innerHTML;
                    const adminButton = document.getElementById('user_type_admin');
                    const agentButton = document.getElementById('user_type_agent');
                    const clientButton = document.getElementById('user_type_client');
                    const userId = tableRow.dataset.userid;
                    const userIdInput = document.getElementById('postUserId');

                    userIdInput.value = userId;

                    if(userType === "Admin") {
                        adminButton.checked = true;
                    } else if(userType === "Agent") {
                        agentButton.checked = true;
                    } else {
                        clientButton.checked = true;
                    }

                    userEditMenu.style.display = 'flex';
                    //document.location.assign('../../src/controllers/action_edit_user.php');
                    break;
                case "departmentsTable":
                    departmentEditMenu.style.display = 'flex';
                    //document.location.assign('../../src/controllers/action_edit_department.php');
                    break;
                default:
                    console.log('Unexpected Table ID.');
                    break;
            }
        });
    }
}

function closeEditWindowListener() {
    const closebutton = document.querySelector('#editWindow .closeIcon');
    const main = document.getElementById('adminPage');
    const editWindow = document.querySelector('#editWindow');
    const userEditMenu = document.getElementById('user_edit_menu');
    const departmentEditMenu = document.getElementById('department_edit_menu');
    const adminButton = document.getElementById('user_type_admin');
    const agentButton = document.getElementById('user_type_agent');
    const clientButton = document.getElementById('user_type_client');

    closebutton.addEventListener('click', function() {
        editWindow.style.display = 'none';
        main.style.filter = 'blur(0px)';
        userEditMenu.style.display = 'none';
        departmentEditMenu.style.display = 'none';
        adminButton.checked = false;
        agentButton.checked = false;
        clientButton.checked = false;
    });
}

deleteIconListener();
editIconListener();
closeEditWindowListener();