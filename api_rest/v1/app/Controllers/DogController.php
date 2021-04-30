<?php
/**
 * DogController.php
 *
 * Controller of the Dog model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAODog;
use App\DataAccessObject\DAOUser;
use App\Controllers\ResponseController;
use App\Controllers\HelperController;
use App\Models\Dog;
use App\System\Constants;

class DogController {

    private DAODog $DAODog;
    private DAOUser $DAOUser;


    /**
     * 
     * Constructor of the DogController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAODog = new DAODog($db);
        $this->DAOUser = new DAOUser($db);       
    }

    /**
     * 
     * Method to return all dogs in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllDogs()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allDogs = $this->DAODog->findAll();

        return ResponseController::successfulRequest($allDogs);  
    }

    /**
     * 
     * Method to return a dog in JSON format.
     * 
     * @param int $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        if (is_null($dog)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($dog);
    }

    /**
     * 
     * Method to create a dog.
     * 
     * @param Dog $dog The dog model object
     * @return string The status and the body in JSON format of the response
     */
    public function createDog(Dog $dog)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateDog($dog)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $user = $this->DAOUser->find($dog->user_id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        $this->DAODog->insert($dog);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a dog.
     * 
     * @param Dog $dog The dog model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateDog(Dog $dog)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDog = $this->DAODog->find($dog->id);

        if (is_null($actualDog)) {
            return ResponseController::notFoundResponse();
        }

        $actualDog->name = $dog->name ?? $actualDog->name;
        $actualDog->breed = $dog->breed ?? $actualDog->breed;
        $actualDog->sex = $dog->sex ?? $actualDog->sex;
        $actualDog->picture_serial_id = $dog->picture_serial_id ?? $actualDog->picture_serial_id;
        $actualDog->chip_id = $dog->chip_id ?? $actualDog->chip_id;

        $this->DAODog->update($actualDog);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a dog.
     * 
     * @param int  $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        if (is_null($dog)) {
            return ResponseController::notFoundResponse();
        }

        if (!is_null($dog->picture_serial_id)) {
            $filename = HelperController::getDefaultDirectory()."storage/app/dog_picture/".$dog->picture_serial_id.".jpeg";
            if (file_exists($filename)) {
                unlink($filename);
            }
        }

        $this->DAODog->delete($dog);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to upload a dog picture.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public function uploadDogPicture()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!isset($_FILES["dog_picture"]) || !is_uploaded_file($_FILES["dog_picture"]["tmp_name"]) || !isset($_POST["dog_id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        $dog = $this->DAODog->find($_POST["dog_id"]);

        if (is_null($dog)) {
            return ResponseController::notFoundResponse();
        }

        switch ($_FILES["dog_picture"]["type"]) {
            case Constants::IMAGE_TYPE_PNG:
                HelperController::pngTojpegConverter($_FILES["dog_picture"]["tmp_name"]);
                break;   
            case Constants::IMAGE_TYPE_JPEG:
                break;    
            default:
                return ResponseController::imageFileFormatProblem();
                break;
        }

        $tmp_file = $_FILES["dog_picture"]["tmp_name"];
        $img_name = HelperController::generateRandomString();
        $upload_dir = HelperController::getDefaultDirectory()."storage/app/dog_picture/".$img_name.".jpeg";

        if (!is_null($dog->picture_serial_id)) {
            $filename = HelperController::getDefaultDirectory()."storage/app/dog_picture/".$dog->picture_serial_id.".jpeg";
            if (file_exists($filename)) {
                unlink($filename);
            }
        }
        
        if (!move_uploaded_file($tmp_file,$upload_dir)) {
            return ResponseController::uploadFailed();
        }
        
        $dog->picture_serial_id = $img_name;

        $this->DAODog->update($dog);
        
        return ResponseController::successfulRequest();
    }

    /**
     * 
     * Method to download a dog picture.
     * 
     * @param string  $serial_id The serial_id of the dog picture
     * @return string The status and the body in JSON format of the response
     */
    public function downloadDogPicture(string $serial_id)
    {
        if(!$this->DAODog->findBySerialId($serial_id)){
            return ResponseController::notFoundResponse();
        }

        $image = file_get_contents(HelperController::getDefaultDirectory()."storage/app/dog_picture/".$serial_id.".jpeg");
        
        return ResponseController::successfulRequestWithoutJson('data:image/jpeg;base64, '.base64_encode($image));
    }

     /**
     * 
     * Method to check if the dog required fields have been defined for the creation.
     * 
     * @param Dog $dog The dog model object
     * @return bool
     */
    private function validateDog(Dog $dog)
    {
        if ($dog->name == null) {
            return false;
        }

        if ($dog->breed == null) {
            return false;
        }

        if ($dog->sex == null) {
            return false;
        }

        if ($dog->user_id == null) {
            return false;
        }

        return true;
    }
}