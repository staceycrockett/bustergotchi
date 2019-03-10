<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/interaction.php';
 
// instantiate database and product object
$database = new Database();
$db = $database->getConnection();
 
// initialize object
$action = new Interaction($db);

$stmt = $action->read();
$num = $stmt->rowCount();

if($num>0){
 
    
    $action_arr=array();
    $action_arr["records"]=array();
 
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
        
        extract($row);
 
        $action_item=array(
            "interactionid" => $interactionid,
            "actionid" => $actionid,
            "userid" => $userid,
            "timestamp" => $timestamp,
            "actionname" => $actionname
        );
 
        array_push($action_arr["records"], $action_item);
    }
 
    // set response code - 200 OK
    http_response_code(200);
 
    // show products data in json format
    echo json_encode($action_arr);
}else{
 
        // set response code - 404 Not found
        http_response_code(404);
     
        // tell the user no products found
        echo json_encode(
            array("message" => "No products found.")
        );
    }
