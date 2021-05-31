<?php
/**
 * UserController.php
 *
 * Controller of the User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAOUser;
use App\DataAccessObject\DAODog;
use App\DataAccessObject\DAODocument;
use App\DataAccessObject\DAOAppointment;
use app\Models\User;
use App\Controllers\ResponseController;
use App\System\Constants;

class UserController {

    private DAOUser $DAOUser;
    private DAODog $DAODog;
    private DAODocument $DAODocument;
    private DAOAppointment $DAOAppointment;

    /**
     * 
     * Constructor of the UserController object.
     * 
     * @param PDO $db The database connection
     */
    public function __construct(\PDO $db)
    {
        $this->DAOUser = new DAOUser($db);
        $this->DAODog = new DAODog($db);
        $this->DAODocument = new DAODocument($db);
        $this->DAOAppointment = new DAOAppointment($db);
    }

    /**
     * 
     * Method to return all users in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllCustomerUsers()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $api_token = $headers['Authorization'];

        $userAuth = $this->DAOUser->findByApiToken($api_token);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allCustomerUsers = $this->DAOUser->findAll(Constants::USER_CODE_ROLE);

        foreach ($allCustomerUsers as $customerUser) {
            $customerUser->dogs = $this->DAODog->findByUserId($customerUser->id);
        }

        return ResponseController::successfulRequest($allCustomerUsers);  
    }

    /**
     * 
     * Method to return all users in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllEducatorUsers()
    { 
        $allEducatorUsers = $this->DAOUser->findAll(Constants::ADMIN_CODE_ROLE);

        return ResponseController::successfulRequest($allEducatorUsers);  
    }

    /**
     * 
     * Method to return a user in JSON format.
     * 
     * @param int $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    public function getUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }
        
        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        $dogs = $this->DAODog->findByUserId($user->id);
        $documents = $this->DAODocument->findByUserId($user->id);
        $appointments = $this->DAOAppointment->findByUserIdForAdmin($user->id);
        $user->dogs = $dogs;
        $user->documents = $documents;
        $user->appointments = $appointments;

        return ResponseController::successfulRequest($user);
    }

    /**
     * 
     * Method to create a user.
     * 
     * @param User $user The user model object
     * @param string $reCAPTCHAuserResponseToken The user response token provided by the reCAPTCHA client-side integration.
     * @return string The status and the body in JSON format of the response
     */
    public function createUser(User $user, string $reCAPTCHAuserResponseToken = null)
    {     
        $user->api_token = HelperController::generateApiToken();
        $user->code_role = Constants::USER_CODE_ROLE;
        
        if (!$this->validateUser($user)) {
            return ResponseController::unprocessableEntityResponse();
        } 

        if (!HelperController::validateEmailFormat($user->email)) {
            return ResponseController::invalidEmailFormat();
        }

        if (!is_null($this->DAOUser->findUserByEmail($user->email))) {
            return ResponseController::emailAlreadyExist();
        }

        if ($user->password_hash != null) {
            $user->password_hash = password_hash($user->password_hash,PASSWORD_DEFAULT);
            if (is_null($reCAPTCHAuserResponseToken)) {
                return ResponseController::unprocessableEntityResponse();
            }
            if (!HelperController::reCAPTCHAvalidate($reCAPTCHAuserResponseToken)) {
                return ResponseController::invalidCaptcha();
            }
        }
        else{
            $random_password = HelperController::generateRandomString();
            $user->password_hash = password_hash($random_password,PASSWORD_DEFAULT);
        }

        $this->DAOUser->insert($user);

        if (isset($random_password)) {
            HelperController::sendMail("Bonjour et merci de faire confiance à la société Douceur de Chien, vous trouverez plus bas votre mot de passe généré aléatoirement afin d'accéder à votre compte. Toutefois, il est fortement conseillé de modifier votre mot de passe une fois connecté.","Création de votre compte Douceur de Chien",$user->email,$random_password);
        }

        $result = array();
        $result["api_token"] = $user->api_token;
        $result["code_role"] = $user->code_role;
        return ResponseController::successfulCreatedRessourceWithJson($result);
    }

    /**
     * 
     * Method to update a user.
     * 
     * @param User $user The user model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateUser(User $user)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $actualUser = $this->DAOUser->find($user->id);

        if (is_null($actualUser)) {
            return ResponseController::notFoundResponse();
        }

        $actualUser->email = $user->email ?? $actualUser->email;
        $actualUser->firstname = $user->firstname ?? $actualUser->firstname;
        $actualUser->lastname = $user->lastname ?? $actualUser->lastname;
        $actualUser->phonenumber = $user->phonenumber ?? $actualUser->phonenumber;
        $actualUser->address = $user->address ?? $actualUser->address;

        if (!HelperController::validateEmailFormat($actualUser->email)) {
            return ResponseController::invalidEmailFormat();
        }

        $this->DAOUser->update($actualUser);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to delete a user.
     * 
     * @param int  $id The user identifier
     * @return string The status and the body in JSON format of the response
     */
    public function deleteUser(int $id)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        $dogs = $this->DAODog->findByUserId($user->id);

        foreach ($dogs as $dog) {

            if (!is_null($dog->picture_serial_id)) {

                $filename = HelperController::getDefaultDirectory()."storage/app/dog_picture/".$dog->picture_serial_id.".jpeg";

                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }

        $documents = $this->DAODocument->findByUserId($user->id);

        foreach ($documents as $document) {

            if (!is_null($document->document_serial_id)) {

                if ($document->type == Constants::DOCUMENT_TYPE_CONDTIONS_OF_REGISTRATION) {

                    $filename = HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$document->document_serial_id.".pdf";
                }
                else{
                    $filename = HelperController::getDefaultDirectory()."storage/app/pdf/".$document->document_serial_id.".pdf";
                }
                
                if (file_exists($filename)) {

                    unlink($filename);
                }
            }
        }

        $appointments = $this->DAOAppointment->findByUserId($user->id);

        foreach ($appointments as $appointment) {

            if (!is_null($appointment->note_graphical_serial_id)) {

                $filename = HelperController::getDefaultDirectory()."storage/app/graphical_note/".$appointment->note_graphical_serial_id.".png";

                if (file_exists($filename)) {
                    unlink($filename);
                }
            }
        }

        $this->DAOUser->delete($user);

        return ResponseController::successfulRequest(null);
    }

    /**
     * 
     * Method to get the api token of a user by logging in.
     * 
     * @param User $user The user model object
     * @return string The status and the body in JSON format of the response
     */
    public function connection(User $user)
    {
        if (is_null($user->email) || is_null($user->password_hash)) {
            return ResponseController::unprocessableEntityResponse();
        }

        $userAuth = $this->DAOUser->findUserByEmail($user->email);

        if (is_null($userAuth)) {
            return ResponseController::invalidLogin();
        }

        if (!password_verify($user->password_hash,$userAuth->password_hash)) {
            return ResponseController::invalidLogin();
        }

        $userAuth->api_token = HelperController::generateApiToken();

        $this->DAOUser->update($userAuth);
        
        $result = array();
        $result["api_token"] = $userAuth->api_token;
        $result["code_role"] = $userAuth->code_role;
        return ResponseController::successfulRequest($result);
    }

    /**
     * 
     * Method to return all information of the currently logged in user in JSON format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public function getMyInformations()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }
        
        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::notFoundResponse();
        }
        $dogs = $this->DAODog->findByUserId($userAuth->id);
        $documents = $this->DAODocument->findByUserId($userAuth->id);
        $appointments = $this->DAOAppointment->findByUserId($userAuth->id);
        $userAuth->dogs = $dogs;
        $userAuth->documents = $documents;
        $userAuth->appointments = $appointments;
        
        
        return ResponseController::successfulRequest($userAuth);
    }

    /**
     * 
     * Method to update the password of the currently logged user.
     * 
     * @param User $user The user model object
     * @return string The status and the body in JSON format of the response
     */
    public function updateMyPassword(User $user)
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }
        
        $userAuth = $this->DAOUser->findByApiToken($headers['Authorization']);

        if (is_null($userAuth)) {
            return ResponseController::notFoundResponse();
        }

        $userAuth->password_hash = password_hash($user->password_hash,PASSWORD_DEFAULT) ?? $userAuth->password_hash;

        $this->DAOUser->update($userAuth);

        return ResponseController::successfulRequest(null);
    }

     /**
     * 
     * Method to check if the user required fields have been defined for the creation.
     * 
     * @param User $user The user model object
     * @return bool
     */
    private function validateUser(User $user)
    {
        if ($user->email == null) {
            return false;
        }

        if ($user->firstname == null) {
            return false;
        }

        if ($user->lastname == null) {
            return false;
        }

        if ($user->phonenumber == null) {
            return false;
        }

        if ($user->address == null) {
            return false;
        }

        return true;
    }
}