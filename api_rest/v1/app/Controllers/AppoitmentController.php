<?php
/**
 * AppoitmentController.php
 *
 * Controller of the Appoitment model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOAppoitment;
use App\DataAccessObject\DAOTimeSlot;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Appoitment;
use App\System\Constants;
use DateTime;

class AppoitmentController {

    private DAOAppoitment $DAOAppoitment;
    private DAOTimeSlot $DAOTimeSlot;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the AppoitmentController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOAppoitment = new DAOAppoitment($db);
        $this->DAOTimeSlot = new DAOTimeSlot($db);
        $this->DAOUser = new DAOUser($db);
    }

    /**
     * 
     * Method to return all Appoitments in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllAppoitments()
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
            $allAppoitments = $this->DAOAppoitment->findAll(null,$userAuth->id);
        }
        else{
            $allAppoitments = $this->DAOAppoitment->findByUserIdForCustomer($userAuth->id);
        }
        
        return ResponseController::successfulRequest($allAppoitments);   
    }

    /**
     * 
     * Method to return a appoitment in JSON format.
     * 
     * @param int $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getAppoitment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $appoitment = $this->DAOAppoitment->find($id);

        if (is_null($appoitment)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($appoitment);
    }

    /**
     * 
     * Method to create a appoitment.
     * 
     * @param Appoitment $appoitment The appoitment model object
     * @return string The status and the body in JSON format of the response
     */
    public function createAppoitment(Appoitment $appoitment)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateAppoitment($appoitment)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $userCustomer = $this->DAOUser->find($appoitment->user_id_customer);
        $userEducator = $this->DAOUser->find($appoitment->user_id_educator);

        if (is_null($userCustomer) || is_null($userEducator) || $userCustomer->code_role != Constants::USER_CODE_ROLE || $userEducator->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::notFoundResponse();
        }

        if (!HelperController::validateDateTimeFormat($appoitment->datetime_appoitment)) {
            return ResponseController::invalidDateTimeFormat();
        }

        $elements = explode(" ",$appoitment->datetime_appoitment);
        $date = $elements[0];
        $time_start = $elements[1];
        $datetime = new DateTime($time_start);
        $datetime->modify('+'.$appoitment->duration_in_hour.' hours');
        $time_end = $datetime->format("H:i:s");
        
        if (!$this->DAOTimeSlot->findAppoitmentSlotsForEducator($date,$time_start,$time_end,$appoitment->user_id_educator)) {
            return ResponseController::invalidAppointment();
        }
        
        $this->DAOAppoitment->insert($appoitment);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a appoitment.
     * 
     * @param int  $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function updateAppoitment(Appoitment $appoitment)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualAppoitment = $this->DAOAppoitment->find($appoitment->id);

        if (is_null($actualAppoitment)) {
            return ResponseController::notFoundResponse();
        }

        $actualAppoitment->note_text = $appoitment->note_text ?? $actualAppoitment->note_text;
        $actualAppoitment->summary = $appoitment->summary ?? $actualAppoitment->summary;

        $this->DAOAppoitment->update($actualAppoitment);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a appoitment.
     * 
     * @param int  $id The appoitment identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteAppoitment(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::unauthorizedUser();
        }

        $appoitment = $this->DAOAppoitment->find($id);

        if (is_null($appoitment)) {
            return ResponseController::notFoundResponse();
        }

        if ($userAuth->id == $appoitment->user_id_educator xor $userAuth->id !=$appoitment->user_id_customer ) {
            return ResponseController::unauthorizedUser();
        }

        $this->DAOAppoitment->delete($appoitment->id,$userAuth->id);

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

        if (!isset($_FILES["note_graphical"]) || !is_uploaded_file($_FILES["note_graphical"]["tmp_name"]) || !isset($_POST["appoitment_id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        $appoitment = $this->DAOAppoitment->find($_POST["appoitment_id"]);

        if (is_null($appoitment)) {
            return ResponseController::notFoundResponse();
        }

        if ($_FILES["note_graphical"]["type"] != Constants::IMAGE_TYPE_PNG) {
            return ResponseController::imageFileFormatProblem();
        }

        $tmp_file = $_FILES["note_graphical"]["tmp_name"];
        $img_name = HelperController::generateRandomString();
        $upload_dir = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$img_name.".png";

        if (!is_null($appoitment->note_graphical_serial_id)) {
            $filename = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$appoitment->note_graphical_serial_id.".png";
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        
        if (!move_uploaded_file($tmp_file,$upload_dir)) {
            return ResponseController::uploadFailed();
        }
        
        $appoitment->note_graphical_serial_id = $img_name;

        $this->DAOAppoitment->update($appoitment);
        
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

        if(is_null($this->DAOAppoitment->findBySerialId($serial_id))){
            return ResponseController::notFoundResponse();
        }

        $image = file_get_contents(HelperController::getDefaultDirectory()."storage/app/graphical_note/".$serial_id.".png");
        
        return ResponseController::successfulRequestWithoutJson('data:image/png;base64, '.base64_encode($image));
    }

     /**
     * 
     * Method to check if the appoitment required fields have been defined for the creation.
     * 
     * @param Appoitment $appoitment The appoitment model object
     * @return bool
     */
    private function validateAppoitment(Appoitment $appoitment)
    {
        if ($appoitment->datetime_appoitment == null) {
            return false;
        }

        if ($appoitment->duration_in_hour == null) {
            return false;
        }

        if ($appoitment->user_id_customer == null) {
            return false;
        }

        if ($appoitment->user_id_educator == null) {
            return false;
        }

        return true;
    }
}