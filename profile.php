<?php

class Profile{

  private $user;

  function __construct(){
    $db = require 'db.php';
    $query = 'SELECT * FROM authtoken WHERE token = '. $_COOKIE['token'];
    $fullToken = $db->query($query);

    $userQuery = 'SELECT * FROM users WHERE id ='. $fullToken['userId'];
    $user = $db->query($userQuery);
    $this->user = $user;
    
  }

}

?>

