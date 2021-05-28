<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process appointment requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\AppointmentController;
use App\Models\Appointment;

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

$controller = new AppointmentController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

parse_str(file_get_contents('php://input'), $input);

$appointment = new Appointment();
$appointment->id = $id ?? null;
$appointment->datetime_appointment = $input["datetime_appointment"] ?? null;
$appointment->duration_in_hour = $input["duration_in_hour"] ?? null;
$appointment->note_text = $input["note_text"] ?? null;
$appointment->note_graphical_serial_id = $input["note_graphical_serial_id"] ?? null;
$appointment->summary = $input["summary"] ?? null;
$appointment->user_id_customer = $input["user_id_customer"] ?? null;
$appointment->user_id_educator = $input["user_id_educator"] ?? null;


switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllAppointments();
        }
        else{
            $response = $controller->getAppointment($id);
        }
        break;

    case 'POST':
        $response = $controller->createAppointment($appointment);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateAppointment($appointment);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteAppointment($id);
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
