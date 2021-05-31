<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process download note graphical.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\AppointmentController;

require "../../../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (strcmp("OPTIONS", $requestMethod) == 0) {
	header('Allow: GET,POST,PATCH,DELETE');
	return;
}

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$serial_id = end($pathFragments);

$controller = new AppointmentController($dbConnection);

switch ($requestMethod) {
    case 'GET':
        if (empty($serial_id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->downloadNoteGraphical($serial_id);
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
