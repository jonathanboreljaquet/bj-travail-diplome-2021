<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process weekly schedule requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\WeeklyScheduleController;
use App\Models\WeeklySchedule;

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

$controller = new WeeklyScheduleController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$weeklySchedule = new WeeklySchedule();
$weeklySchedule->id = $id ?? null;
$weeklySchedule->date_valid_from = $input["date_valid_from"] ?? null;
$weeklySchedule->date_valid_to = $input["date_valid_to"] ?? null;

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllWeeklySchedules();
        }
        else{
            $response = $controller->getWeeklySchedule($id);
        }
        break;

    case 'POST':
        $response = $controller->createWeeklySchedule($weeklySchedule);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateWeeklySchedule($weeklySchedule);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteWeeklySchedule($id);
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
