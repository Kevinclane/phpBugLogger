<?php

class BugDetailsPage
{

    public $bug;

    function __construct(){
        if(isset($_GET['id'])){
            $this->setBugData($_GET['id']);
        }
    }

    private function setBugData($id){

        $db = require "db.php";
        $query = "SELECT * FROM bug_reports WHERE id=$id LIMIT 1";
        $tempLIst = $db->query($query);
        foreach($tempLIst as $item){
            $this->bug = $item;
        }
        $db->close();
        
    }

    public function buildView(){

        $view = '
            <div class="container-fluid">
                <h4 class="text-center m-3"> This page is mostly demonstrating my ability to pass multiple parameters through the URL and query based on these parameters</h4>
                <div class="row text-center">
                    <div class="col-12 my-2">
                        '.$this->bug['title'].'
                    </div>
                    <div class="col-12">
                        '.$this->bug['description'].'
                    </div>
                </div>
            </div>
        ';
        return $view;

    }

}

?>