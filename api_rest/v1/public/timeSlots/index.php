<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process time slot requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\TimeSlotController;
use App\Models\TimeSlot;

require "../../bootstrap.php";

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

$controller = new TimeSlotController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$timeSlot = new TimeSlot();
$timeSlot->id = $id ?? null;
$timeSlot->code_day = $input["code_day"] ?? null;
$timeSlot->time_start = $input["time_start"] ?? null;
$timeSlot->time_end = $input["time_end"] ?? null;
$timeSlot->id_weekly_schedule = $input["id_weekly_schedule"] ?? null;
$timeSlot->id_schedule_override = $input["id_schedule_override"] ?? null;


switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllTimeSlots();
        }
        else{
            $response = $controller->getTimeSlot($id);
        }
        break;

    case 'POST':
        $response = $controller->createTimeSlot($timeSlot);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateTimeSlot($timeSlot);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteTimeSlot($id);
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
