<?php
class Interaction{
 
    // database connection and table name
    private $conn;
    private $table_name = "user_actions";
 
    // object properties
    public $interactionid;
    public $actionid;
    public $userid;
    public $timestamp;
    public $actionname;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
 
        // select all query
        $query = "SELECT interactionid, ua.actionid, ua.userid, timestamp, actionname FROM " . $this->table_name." ua 
                    LEFT JOIN actions a ON ua.actionid = a.actionid";
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }

    function create(){
 
        // query to insert record
        $query = "INSERT INTO
                    " . $this->table_name . "
                SET
                    userid=:userid, actionid=:actionid";
     
        // prepare query
        $stmt = $this->conn->prepare($query);
     
        // sanitize
        $this->userid=htmlspecialchars(strip_tags($this->userid));
        $this->actionid=htmlspecialchars(strip_tags($this->actionid));
     
        // bind values
        $stmt->bindParam(":userid", $this->userid);
        $stmt->bindParam(":actionid", $this->actionid);

        // execute query
        if($stmt->execute()){
            return true;
        }
 
         return false;
    }
     
}