<?php

class ProfilePage{

  private $user;

  function __construct(){

    $this->checkImgUpload();

    $db = require 'db.php';
    $tokenQuery = sprintf("SELECT * FROM authtokens WHERE token='%s' LIMIT 1",
    $db->real_escape_string($_COOKIE['authtoken']));
    $tokenList = $db->query($tokenQuery);
    
    foreach($tokenList as $t){
      $fullToken = $t;
    }
    
    $userQuery = 'SELECT * FROM users WHERE id ='. $fullToken['userid'];
    $userList = $db->query($userQuery);

    $db->close();
    
    foreach($userList as $u){
      $this->user = $u;
    }  
    
    $cl = require 'console_logger.php';
    $cl.console_log($this->user);

  }

  private function checkImgUpload(){

    if(isset($_FILES['image'])){
      $errors= array();
      $file_name = $_FILES['image']['name'];
      $file_size =$_FILES['image']['size'];
      $file_tmp =$_FILES['image']['tmp_name'];
      $file_type=$_FILES['image']['type'];
      $file_ext=strtolower(end(explode('.',$_FILES['image']['name'])));
      
      $extensions= array("jpeg","jpg","png");
      
      if(in_array($file_ext,$extensions)=== false){
         $errors[]="extension not allowed, please choose a JPEG or PNG file.";
      }
      
      if($file_size > 2097152){
         $errors[]='File size must be excately 2 MB';
      }
      
      if(empty($errors)==true){
         move_uploaded_file($file_tmp,"images/".$file_name);
         echo "Success";
      }else{
         print_r($errors);
      }
   }

  }

  public function buildView(){

    $view = "";

    $title = "<h1>Welcome ".$this->user['username']."</h1>";

    $picture;

    if($this->user['picture']){
      $picture = "<img src='".$this->user['picture']."' alt='Error loading image'>";
    } else {
      $picture = "No Image Found.";
    }

    $pictureUpload = "
      <form action='' method='post'>
        Select and image to upload.
        <input type='file' name='fileToUpload' id='fileToUpload'>
        <div>
          <button class='btn btn-success' type='submit' value='Upload Image' name='submit'>Submit</button>
        </div>
      </form>
    ";


    $view .= $title.$picture.$pictureUpload;

    return $view;

  }

}

?>

