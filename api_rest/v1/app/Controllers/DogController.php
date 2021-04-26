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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        return ResponseController::successfulRequest($dog);
    }

    /**
     * 
     * Method to create a dog.
     * 
     * @param array  $input The body of request
     * @return string The status and the body in JSON format of the response
     */
    public function createDog(array $input)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $dog = new Dog();
        $dog->name = $input["name"] ?? null;
        $dog->breed = $input["breed"] ?? null;
        $dog->sex = $input["sex"] ?? null;
        $dog->picture_serial_number = $input["picture_serial_number"] ?? null;
        $dog->chip_id = $input["chip_id"] ?? null;
        $dog->user_id = $input["user_id"] ?? null;

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
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
     * @param array  $input The body of request
     * @param int  $id The dog identifier
     * @return string The status and the body in JSON format of the response
     */
    public function updateDog(array $input, int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualDog = $this->DAODog->find($id);

        if (!$actualDog) {
            return ResponseController::notFoundResponse();
        }

        $modifiedDog = new Dog();
        $modifiedDog->id = $actualDog->id;
        $modifiedDog->name = $input["name"] ?? $actualDog->name;
        $modifiedDog->breed = $input["breed"] ?? $actualDog->breed;
        $modifiedDog->sex = $input["sex"] ?? $actualDog->sex;
        $modifiedDog->picture_serial_number = $input["picture_serial_number"] ?? $actualDog->picture_serial_number;
        $modifiedDog->chip_id = $input["chip_id"] ?? $actualDog->chip_id;

        $this->DAODog->update($modifiedDog);

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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $dog = $this->DAODog->find($id);

        if (!$dog) {
            return ResponseController::notFoundResponse();
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

        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (!$user || $user->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        if (!is_uploaded_file($_FILES["dog_picture"]["tmp_name"]) || !isset($_POST["dog_id"])) {
            return ResponseController::unprocessableEntityResponse();
        }

        $actualDog = $this->DAODog->find($_POST["dog_id"]);

        if (!$actualDog) {
            return ResponseController::notFoundResponse();
        }

        $tmp_file = $_FILES["dog_picture"]["tmp_name"];
        $img_name = HelperController::generateRandomString();
        $ext = pathinfo($_FILES['dog_picture']['name'], PATHINFO_EXTENSION);
        $upload_dir = "../../../storage/app/dogPicture/".$img_name.".jpg";

        if (!move_uploaded_file($tmp_file,$upload_dir)) {
            return ResponseController::uploadFailed();
        }

        if ($actualDog->picture_serial_number != null) {
            unlink("../../../storage/app/dogPicture/".$actualDog->picture_serial_number.".jpg");
        }
        
        $actualDog->picture_serial_number = $img_name;
        $this->DAODog->update($actualDog);
        
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
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        if(!$this->DAODog->findWithSerialNumber($serial_number)){
            return ResponseController::notFoundResponse();
        }

        $file_content = file_get_contents("../../../storage/app/dogPicture/".$serial_number.".jpg");
        return ResponseController::successfulRequestWithPictureData($file_content);
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