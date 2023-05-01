<?php

function getDatabaseConnection(){
    $db = new PDO('sqlite:../database/database.db');
    return $db;
}

?>