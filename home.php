<?php

    $page = "";

   

    if(isset($_GET['route'])){
        // $page = $_GET['route'];
        switch($_GET['route']){
            case 'bugs':
                require 'bugs.php';

                $bugPage = new BugPage();

                $page = $bugPage->buildView();

                break;
            case 'profile':

                

                //build profile view and set to page
                break;
            case 'bugdetails':
                require 'bugDetails.php';

                $bugDetailsPage = new BugDetailsPage();

                $page = $bugDetailsPage->buildView();

                break;
        }

    }


?>





<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/style.css">
    <title>Bug Logger</title>
</head>
<body class="container-fluid bg-offwhite">
    <div class="bg-primary row p-2">
        <form action="" method="get">
            <a class="text-black mx-2" type="submit" href="?route=bugs">Bugs</a>
            <a class="text-black mx-2" type="submit" href="?route=profile">Profile</a>
        </form>
    </div>
    <?php
        echo $page;
    ?>


<script src="https://kit.fontawesome.com/e48b493767.js" crossorigin="anonymous"></script>
</body>
</html>