<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process user requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\UserController;

require "../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new UserController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = end($pathFragments);

parse_str(file_get_contents('php://input'), $input);

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllUsers();
        }
        else{
            $response = $controller->getUser($id);
        }
        break;

    case 'POST':
        $response = $controller->createUser($input);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateUser($input,$id);
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
