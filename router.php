<?php
    function redirect($path){
        switch($path){
          case 'bugs':
            Header("Location: http://localhost/buglogger/home.php?route=bugs", true);
            break;
          case 'home':
            Header("Locationi: http://localhost/buglogger/home.php", true);
            break;
          case 'login':
            Header("Location: http://localhost/buglogger/home.php?route=login", true);
        }
      }
?>