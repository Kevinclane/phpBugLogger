<?php

class BugPage
{
    public $cl;

    public $bugs;
    
    function __construct(){
        $this->cl = require 'console_logger.php';
        
        //Load all bugs for the view
        $db = require "db.php";
        $query = "SELECT * FROM bug_reports";
        $this->bugs = $db->query($query);
        $db->close();

        session_start();
        if(isset($_POST['create'])){
            $this->createBugData();
            $this->reload();
            return;
        }

        if(isset($_POST['delete'])){
            $this->cl.console_log("First Del Path");
            $this->deleteBug($_POST['delete']);
            $this->reload();
            return;
        }

    }


    private function reload(){
        header("Location: http://localhost/buglogger/home.php?route=bugs", true);
    }

    //#REGION HTML BUILDING 

    public function buildView(){

        $form = $this->buildAddForm();
        $list = $this->buildList();

        $view = $form.$list;

        return $view;

    }

    private function buildAddForm(){

        $form = '
        <div class="container p-3 my-3 bg-darkgray rounded">
            <form action="" method="post">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input type="text" name="newTitle" class="form-control" id="title" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <input type="text" name="newDescription" class="form-control" id="description" aria-describedby="desc" required>
                    <div id="desc" class="form-text">Please be as thorough as possible.</div>
                </div>
                <div class="mb-3">
                    <label for="severity" class="form-label">Severity</label>
                    <input type="number" min="1" max="10" name="newSeverity" class="form-control" id="severity" required>
                </div>        
            <button type="submit" name="create" class="btn btn-primary">Submit</button>
            </form>
        </div>
        ';

        return $form;
    }

    private function buildList(){
        if(isset($this->bugs) > 0){

            $opening = '<div class="container-fluid bg-secondary p-3 mb-5">';

            $bodyHeader = '
            <div class="row border-bottom">
            <div class="col-4 bold">Title</div>
            <div class="col-6 bold">Description</div>
            <div class="col-1 bold">Severity</div>
            </div>
            ';
                
            $body = '';
        
            foreach($this->bugs as $bug){
                $body .= 
                '<div class="row my-2">
                    <form class="col-4" action="" method="get"> 
                        <a class="text-black mx-2" type="submit" href="?route=bugdetails&id='.$bug['id'].'">'.$bug['title'].'</a>
                    </form>
                    <div class="col-6">' .
                        $bug['description'] . 
                    '</div>
                    <div class="col-1">' . 
                        $bug['severity'] .
                    '</div>
                    <div class="col-1">
                        <form action="" method="post">
                            <button class="btn btn-sm btn-danger" type="submit" name="delete" value="'.$bug['id'].'">delete</button>
                        </form>
                    </div>
                </div>';
            }
            
            $closing = '</div>';
        
            $template = $opening . $bodyHeader . $body . $closing;
        
            return $template;

        } else {
            return "";
        }
    }

    //#END REGION HTML BUILDING

    //#REGION DB FUNCTIONS

    private function createBugData(){

        $aborted = false;

        if($_POST['newTitle'] == ""){
            $aborted = true;
        } else {
            $_SESSION['newTitle'] = $_POST['newTitle'];
            // $this->newTitle = $_POST['newTitle'];
        };
    
        if($_POST['newDescription'] == ""){
            $aborted = true;
        } else{
            $_SESSION['newDescription'] = $_POST['newDescription'];
            // $this->newDescription = $_POST['newDescription'];
        };
    
        if($_POST['newSeverity'] == ""){
            $aborted = true;
        } else {
            $_SESSION['newSeverity'] = $_POST['newSeverity'];
            // $this->newSeverity = $_POST['newSeverity'];
        };

        if(!$aborted){            
            $db = require "db.php";

            $newBugQuery = sprintf(
                "INSERT INTO bug_reports (title, description, severity)
                VALUES ('%s', '%s', '%s')",
                $db->real_escape_string($_SESSION['newTitle']),
                $db->real_escape_string($_SESSION['newDescription']),
                $db->real_escape_string($_SESSION['newSeverity'])
            );        

            $db->query($newBugQuery);
            $db->close();        
        }
    }

    private function deleteBug($id){

        $db = require "db.php";
        $delBugQuery = "DELETE FROM bug_reports WHERE id=$id;";
        $db->query($delBugQuery);
        $db->close();

        $this->cl.console_log($id);
    }

    //#END REGION DB FUNCTIONS

}

?>

