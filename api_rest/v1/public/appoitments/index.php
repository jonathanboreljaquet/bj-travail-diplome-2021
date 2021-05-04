<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process appoitment requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\AppoitmentController;
use App\Models\Appoitment;

require "../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");



$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new AppoitmentController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$appoitment = new Appoitment();
$appoitment->id = $id ?? null;
$appoitment->datetime_appoitment = $input["datetime_appoitment"] ?? null;
$appoitment->duration_in_hour = $input["duration_in_hour"] ?? null;
$appoitment->note_text = $input["note_text"] ?? null;
$appoitment->note_graphical_serial_id = $input["note_graphical_serial_id"] ?? null;
$appoitment->summary = $input["summary"] ?? null;
$appoitment->user_id_customer = $input["user_id_customer"] ?? null;
$appoitment->user_id_educator = $input["user_id_educator"] ?? null;


switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllAppoitments();
        }
        else{
            $response = $controller->getAppoitment($id);
        }
        break;

    case 'POST':
        $response = $controller->createAppoitment($appoitment);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateAppoitment($appoitment);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteAppoitment($id);
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
