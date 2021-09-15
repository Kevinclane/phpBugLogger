<?php

//Login view should either call to log the user in or to toggle to create account
//Create Account view should either call to create account or toggle to login

class LoginPage{

  public $cl;

  public $newUser = false;

  public $newUsername = "";
  public $newPassword = "";
  public $newConfirmPassword = "";
  public $newEmail = "";

  public $username = "";
  public $password = "";

  public $users = "";



  function __construct(){

    $this->cl = require 'console_logger.php';

    $this->checkNSetCookie();

    if(isset($_POST['togglenewuser'])){
      if($_COOKIE['newuser'] == 'true'){
        setcookie('newuser', 'false');
      } else {
        setcookie('newuser', "true");
      }
      $this->reload();
    }

    if(isset($_POST['login'])){
      $this->login();
      $this->redirect("bugs");
    } 

    //Going to need to pull this out of the loginPage and put it in it's own class. Probably not bad to move some of the functionality from login into the new class as well. The problem is that when you click logout on a page that is NOT the login page, the post never gets read. Maybe have these functions be built into the navbar as a required functionality.
    if(isset($_POST['logout'])){
      $this->cl.console_log("First stage logout");
      $this->logout($_POST['logout']);
      $this->redirect("home");
    }
    
    if(isset($_POST['createaccount'])){
      $this->createAccount();
      $this->redirect("bugs");
    }


  } //construct

  private function redirect($path){
    switch($path){
      case 'bugs':
        Header("Location: http://localhost/buglogger/home.php?route=bugs", true);
        break;
      case 'home':
        Header("Locationi: http://localhost/buglogger/home.php", true);
        break;
    }
  }

  private function checkNSetCookie(){
    if(!isset($_COOKIE['newuser'])){
      setcookie('newuser', 'false');
    } else {
      $this->setDataFromCookies();
    }
  }

  private function setDataFromCookies(){

    if(isset($_COOKIE['newusername'])){
      $this->newUsername = $_COOKIE['newusername'];
    }

    if(isset($_COOKIE['newpassword'])){
      $this->newPassword = $_COOKIE['newpassword'];
    }

    if(isset($_COOKIE['newconfirmpassword'])){
      $this->newConfirmPassword = $_COOKIE['newconfirmpassword'];
    }

    if(isset($_COOKIE['newemail'])){
      $this->newEmail = $_COOKIE['newemail'];
    }

    if(isset($_COOKIE['username'])){
      $this->username = $_COOKIE['username'];
    }

    if(isset($_COOKIE['password'])){
      $this->password = $_COOKIE['password'];
    }

  }

  private function reload(){
    header("Location: http://localhost/buglogger/home.php?route=login", true);
  }

  public function buildView(){

    $opening = '
    <div class="container mt-5">
      <div class="row">
        <div class="col-10 offset-1 col-lg-6 offset-lg-3 py-5">
    ';

    $body = '';
    
    if($_COOKIE['newuser'] == 'false'){
      $body = $this->buildLoginForm();
    } else {
      $body = $this->buildCreateAccountForm();
    }

    $closing = '
        </div>
      </div>
    </div>
    ';
    
    $view = $opening.$body.$closing;

    return $view;
  }

  private function buildLoginForm(){
    $template = '
    <div class="bg-darkgray p-2 rounded shadow-md">
      <form class="super-center-col" action="" method="post">

        <div class="w-100">
          <label for="username">Username</label>
          <input class="rounded my-1 w-100" type="username" name="username" id="username" required>
        </div>

        <div class="w-100">
          <label for="password">Password</label>
          <input class="rounded my-1 w-100" type="password" name="password" id="password" required>
        </div>

        <div class="text-center w-100 mt-3">
          <button class="btn btn-success w-50" type="submit" name="login">Login</button>
        </div>
      
      </form>

      <form class="super-center-col my-4" action="" method="post">
        Don\'t have an account?
        <button class="btn btn-info mt-2 w-50" type="submit" name="togglenewuser">Create Account</button>
      </form>

    </div>
    ';
    return $template;
  }

  private function buildCreateAccountForm(){
    $template = '
    <div class="bg-darkgray p-2 rounded shadow-md">
      <form class="super-center-col " action="" method="post">

        <div class="w-100">
          <label for="newemail">Email</label>
          <input class="rounded my-1 w-100" type="email" name="newemail" id="newemail" value="'.
          $this->newEmail
          .'"required>
        </div>

        <div class="w-100">
          <label for="newusername">Username</label>
          <input class="rounded my-1 w-100" type="username" name="newusername" id="newusername" value="'.
          $this->newUsername
          .'" required>
        </div>
        
        <div class="w-100">
          <label for="newpassword">Password</label>
          <input class="rounded my-1 w-100" type="password" name="newpassword" id="newpassword" required>
        </div>

        <div class="w-100">
          <label for="newconfirmpassword">Confirm Password</label>
          <input class="rounded my-1 w-100" type="password" name="newconfirmpassword" id="newconfirmpassword" required>
        </div>

        <div class="text-center w-100 mt-3">
          <button class="btn btn-success w-50" type="submit" name="createaccount">Create Account</button>
        </div>
      
      </form>

      <form class="super-center-col my-4" action="" method="post">
        Already have an account?
        <button class="btn btn-info mt-2 w-50" type="submit" name="togglenewuser">Login</button>
      </form>

    </div>
    ';
    return $template;
  }

  private function login(){

    $username;
    $password;
    
    if(isset($_POST['username'])){
      $username = $_POST['username'];
    }

    if(isset($_POST['password'])){
      $password = $_POST['password'];
    }

    $token = $this->getUser($username, $password);

  }

  private function logout($token){
    $this->cl.console_log("In Logout");
    $this->deleteToken($token);
    setcookie("authtoken", "", time() - 1000);
  }

  private function createAccount(){

    $newEmail = '';
    $newUsername = '';
    $newPassword = '';
    $newConfirmPassword = '';
    
    $aborted = false;
    
    if(isset($_POST['newemail'])){
      $newEmail = $_POST['newemail'];
      setcookie('newemail', $newEmail);
    } else {
      $aborted = true;
    }

    if(isset($_POST['newusername'])){
      $newUsername = $_POST['newusername'];
      setcookie('newusername', $newUsername);
    } else {
      $aborted = true;
    }

    if(isset($_POST['newpassword'])){
      $newPassword = $_POST['newpassword'];
    } else {
      $aborted = true;
    }

    if(isset($_POST['newconfirmpassword'])){
      $newConfirmPassword = $_POST['newconfirmpassword'];
    } else {
      $aborted = true;
    }

    if($newPassword != $newConfirmPassword){
      $aborted = true;
      //throw new error with message
    }

    if(!$aborted){

      $db = require 'db.php';
      $newUserQuery = sprintf(
        "INSERT INTO users (username, password, email)
        VALUES ('%s', '%s', '%s')",
        $db->real_escape_string($newUsername),
        $db->real_escape_string($newPassword),
        $db->real_escape_string($newEmail)
      );
      $db->query($newUserQuery);
      $db->close();
      
      $this->getUser($newUsername, $newPassword);

      // delete leftover cookies
      setcookie('newuser', '', time() - 100);
      setcookie('newusername', $newUsername, time() - 100);
      setcookie('newemail', $newEmail, time() - 100);
    }

  }

  private function getUser($dbusername, $dbpassword){
    
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

    $this->refreshToken($user);

  }

  private function refreshToken($user){

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

      $this->deleteToken($token['id']);

    }
    
    $generatedKey = $this->generateKey(40);
    
    $createTokenQuery = sprintf("INSERT INTO authtokens (userid, token) VALUES ('%d', '%s')",
    $user['id'], $generatedKey);
    $db->query($createTokenQuery);
    
    $getTokenQuery = sprintf("SELECT * FROM authtokens WHERE userid = ".$user['id']);
    $tokenList = $db->query($getTokenQuery);

    foreach($tokenList as $t){
      $token = $t;
    }
    
    setcookie('authtoken', $token['token']);
    
  }

  private function generateKey($length){

    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randKey = [];
    $max = mb_strlen($characters, '8bit') - 1;
    for($i=0; $i < $length; $i++){
      $randKey [] = $characters[random_int(0, $max)];
    }
        
    return implode("", $randKey);

  }

  private function deleteToken($id){
    $db = require "db.php";
    $delTokenQuery = sprintf("DELETE FROM authtokens WHERE id = ".$id);
    $db->query($delTokenQuery);
  }

}

?>



  
