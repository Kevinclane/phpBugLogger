<?php

$servername="localhost";
$username = "Kevin";
$password = "test123123";
$dbname = "phptesting";

$db = new mysqli($servername, $username, $password, $dbname);

return $db;

?>