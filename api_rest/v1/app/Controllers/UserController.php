<?php
/**
 * UserController.php
 *
 * Controller of the User model.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use App\DataAccessObject\DAODog;
use App\DataAccessObject\DAOUser;
use app\Models\User;
use App\Controllers\ResponseController;
use App\System\Constants;
class UserController {

    private DAOUser $DAOUser;
    private DAODog $DAODog;

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
    }

    /**
     * 
     * Method to return all users in JSON format.
     * 
     * @return string The status and the body in json format of the response
     */
    public function getAllUsers()
    {
        $headers = apache_request_headers();

        if (!isset($headers['Authorization'])) {
            return ResponseController::notFoundAuthorizationHeader();
        }

        $api_token = $headers['Authorization'];

        $userAuth = $this->DAOUser->findUserWithApiToken($api_token);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }
        
        $allUsers = $this->DAOUser->findAll(Constants::USER_CODE_ROLE);

        return ResponseController::successfulRequest($allUsers);  
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

        
        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
        }

        return ResponseController::successfulRequest($user);
    }

    /**
     * 
     * Method to create a user.
     * 
     * @param User $user The user model object
     * @return string The status and the body in JSON format of the response
     */
    public function createUser(User $user)
    {     
        $user->api_token = HelperController::generateApiToken();
        $user->code_role = Constants::USER_CODE_ROLE;
        
        if (!$this->validateUser($user)) {
            return ResponseController::unprocessableEntityResponse();
        } 

        if (!HelperController::validateEmailFormat($user->email)) {
            return ResponseController::invalidEmailFormat();
        }

        if ($user->password_hash != null) {
            $user->password_hash = password_hash($user->password_hash,PASSWORD_DEFAULT);
        }
        else{
            $random_password = HelperController::generateRandomString();
            $user->password_hash = password_hash($random_password,PASSWORD_DEFAULT);
        }

        $this->DAOUser->insert($user);

        if (isset($random_password)) {
            HelperController::sendMail($random_password,$user->email);
        }

        return ResponseController::successfulCreatedRessource();
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

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

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

        $userAuth = $this->DAOUser->findUserWithApiToken($headers['Authorization']);

        if (is_null($userAuth) || $userAuth->code_role != Constants::ADMIN_CODE_ROLE) {
            return ResponseController::unauthorizedUser();
        }

        $user = $this->DAOUser->find($id);

        if (is_null($user)) {
            return ResponseController::notFoundResponse();
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

        $userAuth = $this->DAOUser->findUserWithEmail($user->email);

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
        
        $user = $this->DAOUser->findUserWithApiToken($headers['Authorization']);
        $dog = $this->DAODog->findWithUserId($user->id);
        $user->dogs = $dog;
        
        
        return ResponseController::successfulRequest($user);
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