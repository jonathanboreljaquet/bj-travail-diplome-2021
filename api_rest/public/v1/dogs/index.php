<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process dog requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\DogController;
use App\Models\Dog;

require "../../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (strcmp("OPTIONS", $requestMethod) == 0) {
	header('Allow: GET,POST,PATCH,DELETE');
	return;
}

$controller = new DogController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$dog = new Dog();
$dog->id = $id ?? null;
$dog->name = $input["name"] ?? null;
$dog->breed = $input["breed"] ?? null;
$dog->sex = $input["sex"] ?? null;
$dog->chip_id = $input["chip_id"] ?? null;
$dog->user_id = isset($input["user_id"]) && is_numeric($input["user_id"]) ? $input["user_id"] : null;

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllDogs();
        }
        else{
            $response = $controller->getDog($id);
        }
        break;

    case 'POST':
        $response = $controller->createDog($dog);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateDog($dog);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteDog($id);
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