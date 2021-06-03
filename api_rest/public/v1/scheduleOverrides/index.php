<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process schedule override requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\ScheduleOverrideController;
use App\Models\ScheduleOverride;

require "../../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (strcmp("OPTIONS", $requestMethod) == 0) {
  header('Allow: GET,POST,DELETE');
  return;
}

$controller = new ScheduleOverrideController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$scheduleOverride = new ScheduleOverride();
$scheduleOverride->id = $id ?? null;
$scheduleOverride->date_schedule_override = $input["date_schedule_override"] ?? null;

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllScheduleOverrides();
        }
        else{
            $response = $controller->getScheduleOverride($id);
        }
        break;

    case 'POST':
        $response = $controller->createScheduleOverride($scheduleOverride);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteScheduleOverride($id);
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