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
        $this->DAOUser = new DAOUser($db);
        $this->DAODog = new DAODog($db);
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

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

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

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        return ResponseController::successfulRequest($dog);
    }

    /**
     * 
     * Method to create a dog.
     * 
     * @param Dog $user The dog model object
     * @return string The status and the body in JSON format of the response
     */
    public function createDog(Dog $dog)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!$this->validateDog($dog)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $this->DAODog->insert($dog);

        return ResponseController::successfulCreatedRessource();
    }

    /**
     * 
     * Method to update a dog.
     * 
     * @param Dog $user The dog model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateDog(Dog $dog)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

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
        $actualDog->picture_serial_number = $dog->picture_serial_number ?? $actualDog->picture_serial_number;
        $actualDog->chip_id = $dog->chip_id ?? $actualDog->chip_id;

        $this->DAODog->update($actualDog);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a dog.
     * 
     * @param int  $id The dog identifier
     * @returactualDogn string The status and the body in JSON format of the response
     */
    public function deleteDog(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        if (is_null($dog)) {
            return ResponseController::notFoundResponse();
        }

        if (!is_null($dog->picture_serial_number)) {
            unlink($_SERVER["DOCUMENT_ROOT"]."/bj-travail-diplome-2021/api_rest/v1/storage/app/dog_picture/".$dog->picture_serial_number.".jpeg");
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

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!isset($_FILES["dog_picture"]) || !is_uploaded_file($_FILES["dog_picture"]["tmp_name"]) || !isset($_POST["dog_id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        $dog = $this->DAODog->find($_POST["dog_id"]);

        if (!$dog) {
            return ResponseController::notFoundResponse();
        }

        $image_type = exif_imagetype($_FILES["dog_picture"]["tmp_name"]); 

        switch ($image_type) {
            case IMAGETYPE_PNG:
                HelperController::pngTojpegConverter($_FILES["dog_picture"]["tmp_name"]);
                break;   
            case IMAGETYPE_JPEG:
                break;    
            default:
                return ResponseController::imageFileFormatProblem();
                break;
        }

        

        $tmp_file = $_FILES["dog_picture"]["tmp_name"];
        $img_name = HelperController::generateRandomString();
        $upload_dir = $_SERVER["DOCUMENT_ROOT"]."/bj-travail-diplome-2021/api_rest/v1/storage/app/dog_picture/".$img_name.".jpeg";

        if (!move_uploaded_file($tmp_file,$upload_dir)) {
            return ResponseController::uploadFailed();
        }

        if ($dog->picture_serial_number) {
            unlink($_SERVER["DOCUMENT_ROOT"]."/bj-travail-diplome-2021/api_rest/v1/storage/app/dog_picture/".$dog->picture_serial_number.".jpeg");
        }
        
        $dog->picture_serial_number = $img_name;

        $this->DAODog->update($dog);
        
        return ResponseController::successfulRequest();
    }

    /**
     * 
     * Method to download a dog picture.
     * 
     * @param string  $serial_number The serial_number of the dog picture
     * @return string The status and the body in JSON format of the response
     */
    public function downloadDogPicture(string $serial_number)
    {
        if(!$this->DAODog->findWithSerialNumber($serial_number)){
            return ResponseController::notFoundResponse();
        }

        $image = file_get_contents($_SERVER["DOCUMENT_ROOT"]."/bj-travail-diplome-2021/api_rest/v1/storage/app/dog_picture/".$serial_number.".jpeg");
        
        return ResponseController::successfulRequestWithBase64('data:image/jpeg;base64, '.base64_encode($image));
    }

     /**
     * 
     * Method to check if the dog required fields have been defined for the creation.
     * 
     * @param Dog $dog The user model object
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