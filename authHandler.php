<?php


// region Variables
$router = include 'router.php';
$authButton = "";
$profileButton = "";

// endRegion Variables



//region functions

function deleteToken($id){
  $db = require "db.php";
  $delTokenQuery = sprintf("DELETE FROM authtokens WHERE id = ".$id);
  $db->query($delTokenQuery);
  $db->close();
}

function generateKey($length){

  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $randKey = [];
  $max = mb_strlen($characters, '8bit') - 1;
  for($i=0; $i < $length; $i++){
    $randKey [] = $characters[random_int(0, $max)];
  }
      
  return implode("", $randKey);

}

function refreshToken($user){

  $db = require 'db.php';

  $getTokenQuery = sprintf("SELECT * FROM authtokens WHERE userid = '%d'",
    $db->real_escape_string($user['id']));

  $tokenList = $db->query($getTokenQuery);

  $token = [];

  foreach($tokenList as $t){
    $token = $t;
  }

  //delete old token
  if($token != null){

    deleteToken($token['id']);

  }
  
  $generatedKey = generateKey(40);
  
  $createTokenQuery = sprintf("INSERT INTO authtokens (userid, token) VALUES ('%d', '%s')",
  $user['id'], $generatedKey);
  $db->query($createTokenQuery);
  
  $getTokenQuery = sprintf("SELECT * FROM authtokens WHERE userid = ".$user['id']);
  $tokenList = $db->query($getTokenQuery);

  $db->close();

  foreach($tokenList as $t){
    $token = $t;
  }
  
  setcookie('authtoken', $token['token']);
  
}

function getUser($dbusername, $dbpassword){
    
  $db = require "db.php";
  $profileQuery = sprintf("SELECT * FROM users WHERE username = '%s' and password = '%s' LIMIT 1;",
    $db->real_escape_string($dbusername),
    $db->real_escape_string($dbpassword));
  
  $userList = $db->query($profileQuery);
  $db->close();

  $user;

  foreach($userList as $u){
    $user = $u;
  }

  refreshToken($user);

}

function logout($tokenId){
  deleteToken($tokenId);
  setcookie("authtoken", "", time() - 1000);
}

function login(){
  $username;
  $password;
  
  if(isset($_POST['username'])){
    $username = $_POST['username'];
  }

  if(isset($_POST['password'])){
    $password = $_POST['password'];
  }

  $token = getUser($username, $password);
}

//endRegion functions


//region task checkers

if(isset($_POST['logout'])){
  logout($_POST['logout']);
  $router.redirect('landingpage');
}

if(isset($_POST['login'])){
  login();
  $router.redirect('bugs');
}

//endRegion task checkers


if(isset($_COOKIE['authtoken'])){
    $profileButton = '<a class="text-black mx-2" type="submit" href="?route=profilepage">Profile</a>';
    $authButton = '
        <form action="" method="post">
            <button class="btn btn-sm btn-danger" type="submit" name="logout" value="'.$_COOKIE['authtoken'].'">Logout</button>
        </form>
    ';
} else {
    $authButton = '
        <form action="" method="get">
            <button class="btn btn-sm btn-success" type="submit" name="route" value="login">Login</button>
        </form>
    ';
}

?>