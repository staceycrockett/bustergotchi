<?php
// required headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");
 
// include database and object files
include_once '../config/database.php';
include_once '../objects/action.php';

// required to decode jwt
include_once '../config/core.php';
include_once '../libs/php-jwt-master/src/BeforeValidException.php';
include_once '../libs/php-jwt-master/src/ExpiredException.php';
include_once '../libs/php-jwt-master/src/SignatureInvalidException.php';
include_once '../libs/php-jwt-master/src/JWT.php';
use \Firebase\JWT\JWT;


$data = json_decode(file_get_contents("php://input"));
 
// get jwt
$jwt=isset($data->jwt) ? $data->jwt : "";

if($jwt){
 
    // if decode succeed, show user details
    try {
 
        // decode jwt
        $decoded = JWT::decode($jwt, $key, array('HS256'));

        // instantiate database and product object
        $database = new Database();
        $db = $database->getConnection();
        
        // initialize object
        $action = new Action($db);

        $stmt = $action->read();
        $num = $stmt->rowCount();

        if($num>0){
        
            
            $action_arr=array();
            $action_arr["records"]=array();
        
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)){
                
                extract($row);
        
                $action_item=array(
                    "actionid" => $actionid,
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

 
       
    }catch (Exception $e){
 
        // set response code
        http_response_code(401);
     
        // show error message
        echo json_encode(array(
            "message" => "Access denied.",
            "error" => $e->getMessage()
        ));
    }
 
    
}else{
 
    // set response code
    http_response_code(401);
 
    // tell the user access denied
    echo json_encode(array("message" => "Access denied."));
}
 
// error message if jwt is empty will be here
 
