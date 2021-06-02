<?php
/**
 * AppointmentController.php
 *
 * Controller of the Appointment model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOAppointment;
use App\DataAccessObject\DAOTimeSlot;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Appointment;
use App\System\Constants;

class AppointmentController {

    private DAOAppointment $DAOAppointment;
    private DAOTimeSlot $DAOTimeSlot;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the AppointmentController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOAppointment = new DAOAppointment($db);
        $this->DAOTimeSlot = new DAOTimeSlot($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all Appointments in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllAppointments()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        if($userAuth->code_role == Constants::ADMIN_CODE_ROLE){
            $allAppointments = $this->DAOAppointment->findAll(null,$userAuth->id);
        }
        else{
            $allAppointments = $this->DAOAppointment->findByUserId($userAuth->id);
        }
        
        return ResponseController::successfulRequest($allAppointments);   
    }

    /**
     * 
     * Method to return a appointment in JSON format.
     * 
     * @param int $id The appointment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getAppointment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $appointment = $this->DAOAppointment->find($id);

        if (is_null($appointment)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($appointment);
    }

    /**
     * 
     * Method to create a appointment.
     * 
     * @param Appointment $appointment The appointment model object
     * @return string The status and the body in JSON format of the response
     */
    public function createAppointment(Appointment $appointment)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateAppointment($appointment)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $userCustomer = $this->DAOUser->find($appointment->user_id_customer);
        $userEducator = $this->DAOUser->find($appointment->user_id_educator);

        if (is_null($userCustomer) || is_null($userEducator) || $userCustomer->code_role != Constants::USER_CODE_ROLE || $userEducator->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::notFoundResponse();
        }

        if (!HelperController::validateDateTimeFormat($appointment->datetime_appointment)) {
            return ResponseController::invalidDateTimeFormat();
        }

        $datetime_start = new \DateTime($appointment->datetime_appointment, new \DateTimeZone("Europe/Berlin"));
        $datetime_end = clone $datetime_start;
        $datetime_end->modify('+'.$appointment->duration_in_hour." hours");
        
        $date = $datetime_start->format("Y-m-d");
        $time_start = $datetime_start->format("H:i:s");
        $time_end = $datetime_end->format("H:i:s");

        $filename =  "iCal-" . $datetime_start->format("Ymd"). ".ics";

        $educator_fullname = $userEducator->firstname . " " . $userEducator->lastname;

        HelperController::sendMailWithICSFile($datetime_start, $datetime_end, $educator_fullname, $userCustomer->email, $filename);

        if (!$this->DAOTimeSlot->findAppointmentSlotsForEducator($date,$time_start,$time_end,$appointment->user_id_educator)) {
            return ResponseController::invalidAppointment();
        }

        $this->DAOAppointment->insert($appointment);

        

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a appointment.
     * 
     * @param int  $id The appointment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function updateAppointment(Appointment $appointment)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualAppointment = $this->DAOAppointment->find($appointment->id);

        if (is_null($actualAppointment)) {
            return ResponseController::notFoundResponse();
        }

        $actualAppointment->note_text = $appointment->note_text ?? $actualAppointment->note_text;
        $actualAppointment->summary = $appointment->summary ?? $actualAppointment->summary;

        $this->DAOAppointment->update($actualAppointment);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a appointment.
     * 
     * @param int  $id The appointment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteAppointment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        $appointment = $this->DAOAppointment->find($id);

        if (is_null($appointment)) {
            return ResponseController::notFoundResponse();
        }

        if ($userAuth->id == $appointment->user_id_educator xor $userAuth->id !=$appointment->user_id_customer ) {
            return ResponseController::unauthorizedUser();
        }

        $this->DAOAppointment->delete($appointment->id,$userAuth->id);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to upload a graphical note.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public function uploadNoteGraphical()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!isset($_FILES["note_graphical"]) || !is_uploaded_file($_FILES["note_graphical"]["tmp_name"]) || !isset($_POST["appointment_id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        $appointment = $this->DAOAppointment->find($_POST["appointment_id"]);

        if (is_null($appointment)) {
            return ResponseController::notFoundResponse();
        }

        switch ($_FILES["note_graphical"]["type"]) {
            case Constants::IMAGE_TYPE_PNG:
                HelperController::pngTojpegConverter($_FILES["note_graphical"]["tmp_name"]);
                break;   
            case Constants::IMAGE_TYPE_JPEG:
                break;    
            default:
                return ResponseController::imageFileFormatProblem();
                break;
        }

        $tmp_file = $_FILES["note_graphical"]["tmp_name"];
        $img_name = HelperController::generateRandomString();
        $upload_dir = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$img_name.".jpeg";

        if (!is_null($appointment->note_graphical_serial_id)) {
            $filename = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$appointment->note_graphical_serial_id.".jpeg";
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        
        if (!move_uploaded_file($tmp_file,$upload_dir)) {
            return ResponseController::uploadFailed();
        }
        
        $appointment->note_graphical_serial_id = $img_name;

        $this->DAOAppointment->update($appointment);
        
        return ResponseController::successfulRequest();
    }

    /**
     * 
     * Method to download a graphical note.
     * 
     * @param string  $serial_id The serial_id of the graphical note
     * @return string The status and the body in JSON format of the response
     */
    public function downloadNoteGraphical(string $serial_id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if(is_null($this->DAOAppointment->findBySerialId($serial_id))){
            return ResponseController::notFoundResponse();
        }

        $image = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$serial_id.".jpeg";
        
        $imageInfo = getimagesize($image);

        header("Content-Type: " . $imageInfo["mime"]);
        header("Content-Length: " . filesize($image));
        readfile($image);

        return ResponseController::successfulRequest();
    }

     /**
     * 
     * Method to check if the appointment required fields have been defined for the creation.
     * 
     * @param Appointment $appointment The appointment model object
     * @return bool
     */
    private function validateAppointment(Appointment $appointment)
    {
        if ($appointment->datetime_appointment == null) {
            return false;
        }

        if ($appointment->duration_in_hour == null) {
            return false;
        }

        if ($appointment->user_id_customer == null) {
            return false;
        }

        if ($appointment->user_id_educator == null) {
            return false;
        }

        return true;
    }
}