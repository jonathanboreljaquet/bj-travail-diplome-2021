<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process connection request.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\UserController;
use App\Models\User;

require "../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new UserController($dbConnection);

parse_str(file_get_contents('php://input'), $input);

$user = new User();
$user->email = $input["email"] ?? null;
$user->password_hash = $input["password"] ?? null;

switch ($requestMethod) {
    case 'POST':
        $response = $controller->connection($user);
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
        break;
}

header($response['status_code_header']);
if ($response['body']) {
    echo $response['body'];
}
