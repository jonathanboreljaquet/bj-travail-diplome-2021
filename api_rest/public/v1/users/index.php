<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process user requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\UserController;
use App\Models\User;

require "../../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE,OPTIONS");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (strcmp("OPTIONS", $requestMethod) == 0) {
	header('Allow: GET,POST,PATCH,DELETE');
	return;
}

$controller = new UserController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$user = new User();
$user->id = $id ?? null;
$user->email = $input["email"] ?? null;
$user->firstname = $input["firstname"] ?? null;
$user->lastname = $input["lastname"] ?? null;
$user->phonenumber = $input["phonenumber"] ?? null;
$user->address = $input["address"] ?? null;
$user->api_token = $input["api_token"] ?? null;
$user->code_role = $input["code_role"] ?? null;
$user->password_hash = $input["password"] ?? null;
$reCAPTCHAuserResponseToken = $input["reCAPTCHAuserResponseToken"] ?? null;

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllCustomerUsers();
        }
        else{
            $response = $controller->getUser($id);
        }
        break;

    case 'POST':
        $response = $controller->createUser($user,$reCAPTCHAuserResponseToken);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateUser($user);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteUser($id);
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
