<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mticket.php');
    require_once('../../src/models/Musers.php');
    require_once('../../src/models/MDep.php');
    require_once('../templates/Tcommon.php');

    function drawAdminManagement($users, $departments, $tickets) {?>
        <main>
            <section id="users">
                <h2>Users</h2>
                <table>
                    <tr><td>Name</td><td>Email</td><td>Type</td><td>Options</td></tr>
                    <?php foreach($users as $user) { ?>
                        <tr>
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
                                    <button>Change Type</button>
                                    <button>Delete</button>
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
                <h2>Departments</h2>
                <table>
                    <tr><td>Name</td><td>Options</td></tr>
                    <?php foreach($departments as $department) { ?>
                        <tr>
                            <td><?=$department->title?></td>
                            <td>
                                <button>Edit Name</button>
                                <button>Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
            <section id="tickets">
                <h2>Tickets</h2>
                <table>
                    <tr><td>Name</td><td>Status</td><td>Agent</td><td>Options</td></tr>
                    <?php foreach($tickets as $ticket) { ?>
                        <tr>
                            <td><?=$ticket->title?></td>
                            <td><?=$ticket->ticket_status?></td>
                            <?php
                            if($ticket->agent_assigned !== NULL) { ?>
                                <td><?=$ticket->agent_assigned?></td>
                            <?php
                            } else { ?>
                                <td>-</td>
                            <?php
                            } ?>
                            <td>
                                <button>Change Status</button>
                                <button>Delete</button>
                            </td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
        </main>
    <?php
    }

    function drawNoPermission() {?>
        <main>
            <h2>Permission Denied!</h2>
        </main>
    <?php
    }

    session_start();
    $loggedIn = isset($_SESSION['email']);
    $db = getDatabaseConnection();

    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>Database is null</p> <?php
    }

    $email = $_SESSION['email'];
    $current_user = User::getUserByEmail($db, $email);

    drawHeader();

    if ($current_user->getIsAdmin()) {
        $users = User::getAllUsers($db);
        $departments = Department::getAllDepartments($db);
        $tickets = Ticket::getAllTickets($db);

        drawAdminManagement($users, $departments, $tickets);
    } else {
        drawNoPermission();
    }

    drawFooter();
?>