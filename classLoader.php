<?php

$page = "";


if(isset($_GET['route'])){

    switch($_GET['route']){
        case 'bugs':
            require 'bugs.php';

            $bugPage = new BugPage();

            $page = $bugPage->buildView();

            break;
        case 'profilepage':

            //build profile view and set to page
            break;
        case 'bugdetails':
            require 'bugDetails.php';

            $bugDetailsPage = new BugDetailsPage();

            $page = $bugDetailsPage->buildView();

            break;
        case 'login':
            require 'loginPage.php';

            $loginPage = new LoginPage();

            $page = $loginPage->buildView();

            break;
        case 'landingpage':
            $page = require 'landingpage.php';
            break;
        
    }

}

?>