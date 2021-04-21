<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process the request.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

require "../bootstrap.php";

use App\Controllers\UserController;
use App\Controllers\WeeklyScheduleController;
use App\Controllers\ScheduleOverrideController;
use App\Controllers\AbsenceController;
use App\Controllers\TimeSlotController;
use App\System\Constants;

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri = explode('/', $uri);

$requestMethod = $_SERVER["REQUEST_METHOD"];


switch ($uri[6]) {
    case 'users':
        $controller = new UserController($dbConnection, $requestMethod);

        if (isset($uri[7]) && is_numeric($uri[7])) {
            $userId = (int) $uri[7];
            $controller = new UserController($dbConnection, $requestMethod, $userId);
        }

        break;
    case 'weeklySchedules':
        $controller = new WeeklyScheduleController($dbConnection,$requestMethod);

        if (isset($uri[7]) && is_numeric($uri[7])) {
            $weeklyScheduleId = (int) $uri[7];
            $controller = new WeeklyScheduleController($dbConnection, $requestMethod, $weeklyScheduleId);
        }

        break;
    case 'scheduleOverrides':
        $controller = new ScheduleOverrideController($dbConnection,$requestMethod);

        if (isset($uri[7]) && is_numeric($uri[7])) {
            $scheduleOverrideId = (int) $uri[7];
            $controller = new ScheduleOverrideController($dbConnection, $requestMethod, $scheduleOverrideId);
        }

        break;
    case 'absences':
        $controller = new AbsenceController($dbConnection,$requestMethod);

        if (isset($uri[7]) && is_numeric($uri[7])) {
            $absenceId = (int) $uri[7];
            $controller = new AbsenceController($dbConnection, $requestMethod, $absenceId);
        }

        break;
    case 'timeSlots':
        $controller = new TimeSlotController($dbConnection,$requestMethod);

        if (isset($uri[7]) && is_numeric($uri[7])) {
            $timeSlotId = (int) $uri[7];
            $controller = new TimeSlotController($dbConnection, $requestMethod, $timeSlotId);
        }

        if (isset($uri[7]) && $uri[7] == Constants::PLANNING_ENDPOINT) {
            $controller = new TimeSlotController($dbConnection, $requestMethod,null,true);
        }
        break;
    default:
        header("HTTP/1.1 404 Not Found");
        exit();
        break;
}

$controller->processRequest();








//Server settings

/*
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try{
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'mail.infomaniak.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'dev@boreljaquet.ch';                     //SMTP username
$mail->Password   = '';                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
$mail->Port       = 587; 

$mail->setFrom('dev@boreljaquet.ch', 'Dev');
$mail->addAddress('jonathan.brljq@eduge.ch', 'Jonathan Borel-Jaquet');     //Add a recipient
$mail->addReplyTo('dev@boreljaquet.ch', 'Information');

  //Content
$mail->isHTML(true);                                  //Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

var_dump($mail->send());
echo 'Message has been sent';
}
catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
} */
