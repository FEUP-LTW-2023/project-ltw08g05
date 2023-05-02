<?php

function getDatabaseConnection(){
    $db = new PDO('sqlite:/project-ltw08g05/database/database.db');

    return $db;
}

?>