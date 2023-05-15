<?php
    declare(strict_types = 1);

    require_once(__DIR__ . '/../../database/connection.php');
    require_once('../../src/models/Mticket.php');
    require_once('../../src/models/Musers.php');
    //require_once('../../src/models/Mdepartments.php');
    require_once('../templates/Tcommon.php');

    function drawAdminManagement($users, $departments, $tickets) {?>
        <main>
            <section id="users">
                <table>
                    <tr><td>Name</td><td>Email</td><td>Options</td></tr>
                    <?php foreach($users as $user) { ?>
                        <tr>
                            <td><?=$user['name'];?></td>
                            <td><?=$user['email'];?></td>
                            <td></td>
                        </tr>
                    <?php } ?>
                </table>
            </section>
            <section id="departments">
                <table>
                    <tr><td>Name</td><td>Options</td></tr>
                </table>
            </section>
            <section id="tickets">
                <?=drawTickets();?>
            </section>
        </main>
    <?php
    }

    $db = getDatabaseConnection();
    if ($db == null) {
        throw new Exception('Database not initialized');
        ?> <p>$db is null</p> <?php
    }

    //$user = User::
    $users = User::getAllUsers($db);
    //$departments = Department::getAllDepartments($db);
    $departments = NULL;
    $tickets = Ticket::getAllTickets($db);

    drawHeader();

    //if($user.isAdmin) {
        drawAdminManagement($users, $departments, $tickets);
    //} else {
        //drawNoPermission();
    //}

    drawFooter();
?>