<?php
class Action{
 
    // database connection and table name
    private $conn;
    private $table_name = "actions";
 
    // object properties
    public $actionid;
    public $actionname;
 
    // constructor with $db as database connection
    public function __construct($db){
        $this->conn = $db;
    }

    function read(){
 
        // select all query
        $query = "SELECT actionid, actionname FROM " . $this->table_name;
     
        // prepare query statement
        $stmt = $this->conn->prepare($query);
     
        // execute query
        $stmt->execute();
     
        return $stmt;
    }
}