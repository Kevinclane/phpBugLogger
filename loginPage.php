<?php

class LoginPage{

  public $newUser = false;

  public $newUsername = "";
  public $newPassword = "";
  public $newConfirmPassword = "";
  public $newEmail = "";

  public $username = "";
  public $password = "";

  public $users = "";



  function __construct(){

    $this->checkNSetCookie();

    if(isset($_POST['togglenewuser'])){
      if($_COOKIE['newuser'] == 'true'){
        setcookie('newuser', 'false');
      } else {
        setcookie('newuser', "true");
      }
      redirect('login');
    }
    
    if(isset($_POST['createaccount'])){
      $this->createAccount();
      redirect("bugs");
    }


  } //construct



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
      
      getUser($newUsername, $newPassword);

      // delete leftover cookies
      setcookie('newuser', '', time() - 100);
      setcookie('newusername', $newUsername, time() - 100);
      setcookie('newemail', $newEmail, time() - 100);
    }

  }

}

?>



  
