<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mticket.php');
    require_once('../../src/models/Musers.php');
    require_once('../../src/models/Mdep.php');
    require_once('../templates/Tcommon.php');

    function drawAdminManagement($db) {
        $users = User::getAllUsers($db);
        $departments = Department::getAllDepartments($db);
        $tickets = Ticket::getAllTickets($db);
        ?>
        <main id="adminPage">
            <section id="users">
                <header>
                    <h2>Users</h2>
                </header>
                <table id="usersTable">
                    <tr><td>Name</td><td>Email</td><td>Type</td><td>Options</td></tr>
                    <?php foreach($users as $user) { ?>
                        <tr data-userid="<?=$user->userID?>">
                            <td><?=$user->first_name?> <?=$user->last_name?></td>
                            <td><?=$user->email?></td>
                            <?php
                            if($user->isAdmin) { ?>
                                <td>Admin</td>
                            <?php
                            } elseif($user->isAgent) { ?>
                                <td>Agent</td>
                            <?php
                            } else { ?>
                                <td>Client</td>
                            <?php
                            } ?>
                            <?php
                            if( !($user->isAdmin) ) { ?>
                                <td>
                                    <!-- <button>Change Type</button> -->
                                    <?=drawEditIcon();?>
                                    <?=drawDeleteIcon();?>
                                </td>
                            <?php
                            } else { ?>
                                <td>-</td>
                            <?php
                            } ?>
                        </tr>
                    <?php } ?>
                </table>
            </section>
            <section id="departments">
                <header>
                    <h2>Departments</h2>
                    <a href="add_department.php"><?=drawAddIcon();?></a>
                </header>
                <table id="departmentsTable">
                    <tr><td>Name</td><td>Creation Date</td><td>Agent</td><td>Options</td></tr>
                    <?php foreach($departments as $department) { ?>
                        <tr data-departmentid="<?=$department->id?>">
                            <td><?= $department->title ?></td>
                            <td><?= $department->creationDate ?></td>
                            <?php
                            $departmentAgent = User::getUserById($db, $department->userID);
                            if( !($departmentAgent === null) ) { ?>
                                <td><?= $departmentAgent->first_name ?> <?= $departmentAgent->last_name ?></td>
                            <?php
                            } else { ?>
                                <td>-</td>
                            <?php
                            } ?>
                            <td>
                                <?=drawEditIcon();?>
                                <?=drawDeleteIcon();?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
            <section id="tickets">
                <header>
                    <h2>Tickets</h2>
                    <a href="add_ticket.php"><?=drawAddIcon();?></a>
                </header>
                <table id="ticketsTable">
                    <tr><td>Name</td><td>Department</td><td>Status</td><td>Agent</td><td>Options</td></tr>
                    <?php foreach($tickets as $ticket) { ?>
                        <tr data-ticketid="<?=$ticket->id?>">
                            <td><?=$ticket->title?></td>
                            <td>
                                <?php 
                                $ticketDepartment = Department::getDepartment($db, $ticket->departmentID);
                                if($ticketDepartment !== null) {
                                    echo $ticketDepartment->title;
                                } else {
                                    echo "-";
                                } ?>
                            </td>
                            <td><?=$ticket->status?></td>
                            <?php
                            if($ticket->agentAssignedID !== null) {
                                $agent = User::getUserById($db, $ticket->agentAssignedID);
                            ?>
                                <td><?= $agent->first_name ?> <?= $agent->last_name ?></td>
                            <?php
                            } else { ?>
                                <td>-</td>
                            <?php
                            } ?>
                            <td>
                                <?=drawDeleteIcon();?>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
        </main>
        <?=drawEditWindow($db);?>
    <?php
    }

    function drawNoPermission() {?>
        <main>
            <section id="noPermissionWarning">
                <h2>This area is for Admins only.</h2>
                <a href="index.php">Go Back</a>
            </section>
        </main>
    <?php
    }

    function drawDeleteIcon() { ?>
        <svg class="deleteIcon" width="1.1em" height="1.1em" viewBox="0 0 24 24" fill="none">
            <path d="M10 12V17" stroke="#a3250b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M14 12V17" stroke="#a3250b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M4 7H20" stroke="#a3250b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M6 10V18C6 19.6569 7.34315 21 9 21H15C16.6569 21 18 19.6569 18 18V10" stroke="#a3250b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
            <path d="M9 5C9 3.89543 9.89543 3 11 3H13C14.1046 3 15 3.89543 15 5V7H9V5Z" stroke="#a3250b" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
        </svg>
    <?php
    }

    function drawEditIcon() { ?>
        <svg class="editIcon" width="1em" height="1em" viewBox="0 0 24 24">
            <path d="M20,16v4a2,2,0,0,1-2,2H4a2,2,0,0,1-2-2V6A2,2,0,0,1,4,4H8" fill="none" stroke="#bf9c00" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
            <polygon fill="none" points="12.5 15.8 22 6.2 17.8 2 8.3 11.5 8 16 12.5 15.8" stroke="#bf9c00" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"/>
        </svg>
    <?php
    }

    function drawAddIcon() { ?>
        <svg class="addIcon" width="1.5em" height="1.5em" viewBox="0 0 24 24">
            <line fill="none" stroke="#a3250b" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" x1="12" x2="12" y1="19" y2="5"/>
            <line fill="none" stroke="#a3250b" stroke-linecap="round" stroke-linejoin="round" stroke-width="3.5" x1="5" x2="19" y1="12" y2="12"/>
        </svg>
    <?php        
    }

    function drawCloseIcon() { ?>
        <svg class="closeIcon" width="2em" height="2em" viewBox="0 0 1024 1024">
            <path fill="#a3250b" d="M195.2 195.2a64 64 0 0 1 90.496 0L512 421.504 738.304 195.2a64 64 0 0 1 90.496 90.496L602.496 512 828.8 738.304a64 64 0 0 1-90.496 90.496L512 602.496 285.696 828.8a64 64 0 0 1-90.496-90.496L421.504 512 195.2 285.696a64 64 0 0 1 0-90.496z"/>
        </svg>
    <?php
    }

    function drawEditWindow(PDO $db) { ?>
        <section id="editWindow">
            <header>
                <?=drawCloseIcon();?>
                <h2>Edit Menu</h2>
            </header>
            <section id="user_edit_menu" class="editMenuSection">
                <section id="edit_user_type">
                    <h3>Change User Type</h3>
                    <form action="../../src/controllers/action_edit_user.php" method="post">
                        <div>
                            <input type="radio" name="userType" value="admin" id="user_type_admin">
                            <label for="user_type_admin">Admin</label>
                        </div>
                        <div>
                            <input type="radio" name="userType" value="agent" id="user_type_agent">
                            <label for="user_type_agent">Agent</label>
                        </div>
                        <div>
                            <input type="radio" name="userType" value="client" id="user_type_client">
                            <label for="user_type_client">Client</label>
                        </div>

                        <input type="hidden" name="userId" value="-1" id="postUserId">
                        <input type="hidden" name="email" value="<?=$_SESSION['email'];?>">
                        <input type="hidden" name="csrf" value="<?=$_SESSION['csrf']; ?>">
                        <button type="submit">Submit</button>
                    </form>
                </section>
                
                <?php if (isset($_SESSION['error_message'])): ?>
                <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </section>

            <section id="department_edit_menu" class="editMenuSection">
                <form action="../../src/controllers/action_edit_department.php" method="post">
                    <section id="edit_department">
                        <section id="edit_department_name">
                            <h3>Change Departmet's Name</h3>
                            <section id="departmentNameSection">
                                <label for="department_name">Department Name:</label>
                                <input type="text" name="departmentName" id="department_name" minlength="2" required>
                            </section>
                        </section>

                        <section id="edit_department_agent">
                            <h3>Change Department's Agent</h3>
                            <section id="agentsSection">
                                <?php
                                $agents = User::getAgents($db);
                                foreach($agents as $agent) { ?>
                                    <div>
                                        <input type="radio" name="departmentAgentId" value="<?= $agent->getUserID() ?>" required>
                                        <label for="user_type_admin" class="agentInput"><?=$agent->first_name?> <?=$agent->last_name?></label>
                                    </div>
                                <?php
                                } ?>
                            </section>
                        </section>
                    </section>     

                    <input type="hidden" name="departmentId" value="-1" id="postDepartmentId">
                    <input type="hidden" name="email" value="<?=$_SESSION['email'];?>">
                    <input type="hidden" name="csrf" value="<?=$_SESSION['csrf'];?>">
                    <button type="submit">Submit</button>
                </form>

                <?php if (isset($_SESSION['error_message'])): ?>
                <p class="error-message"><?php echo $_SESSION['error_message']; ?></p>
                <?php unset($_SESSION['error_message']); ?>
                <?php endif; ?>
            </section>
        </section>
    <?php
    }

    /* ---------------------------- */
    
    session_start();

    $loggedIn = isset($_SESSION['email']);
    $db = getDatabaseConnection();

    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $email = $_SESSION['email'];
    $current_user = User::getUserByEmail($db, $email);

    drawHeader($db); ?>
    
    <head>
        <link href="../styles/admin_management.css" rel="stylesheet">
        <script src="../scripts/admin_management.js" defer></script>
    </head>

    <?php

    if ($current_user->getIsAdmin()) {
        drawAdminManagement($db);
    } else {
        drawNoPermission();
    }

    drawFooter();
?>