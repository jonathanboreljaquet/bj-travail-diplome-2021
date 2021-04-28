<?php
/**
 * ResponseController.php
 *
 * Controller allowing to return different type of HTTP response.
 *
 * @link https://developer.mozilla.org/fr/docs/Web/HTTP/Status
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;


class ResponseController {

    

    /**
     * 
     * Method to return the error message in case of undefined authorization header.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function notFoundAuthorizationHeader()
    {
        $response['status_code_header'] = 'HTTP/1.1 401 Unauthorized';
        $response['body'] = json_encode([
            'error' => "L'en-tête d'autorisation n'est pas défini."
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of no access permission.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function unauthorizedUser()
    {
        $response['status_code_header'] = 'HTTP/1.1 403 Forbidden';
        $response['body'] = json_encode([
            'error' => "Vous n'avez pas les permissions."
        ]);
        return $response;
    }
    /**
     * 
     * Method to return the error message in case of not found response.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function notFoundResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 404 Not Found';
        $response['body'] = json_encode([
            'error' => "Le serveur n'a pas trouvé la ressource demandée."
        ]);

        return $response;
    }

    /**
     * 
     * Method to return the GET success message.
     * 
     * @param array $result The associative array containing all the result rows
     * @return string The status and the body in JSON format of the response
     */
    public static function successfulRequest($result = null){
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = (!is_null($result)) ? json_encode($result) : null;
        return $response;
    }

    /**
     * 
     * Method to return the GET success message for download picture.
     * 
     * @param array $result The associative array containing all the result rows
     * @return string The status and the body in JSON format of the response
     */
    public static function successfulRequestWithBase64($result){
        $response['status_code_header'] = 'HTTP/1.1 200 OK';
        $response['body'] = $result;
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid attributes.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function unprocessableEntityResponse()
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Attributs invalides.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of a permanent calendar already created.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function permanentScheduleAlreadyExist()
    {
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Un calendrier permanent a déjà été créé.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the POST success message.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function successfulCreatedRessource(){
        $response['status_code_header'] = 'HTTP/1.1 201 Created';
        $response['body'] = null;
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid date format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidDateFormat(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Format de date invalide => (YYYY-MM-DD).'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid time format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidTimeFormat(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Format de temps invalide => (HH:MM:SS).'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of date overlap problem.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function dateOverlapProblem(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Les dates chevauchent d\'autres dates déjà existantes.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of time overlap problem.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function timeOverlapProblem(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Les horaires chevauchent d\'autres horaires déjà existants.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of chronological problem between two times.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function chronologicalDateProblem(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'La date ou l\'heure de début est plus récente que la date ou l\'heure de fin.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid code day format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidCodeDayFormat(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Format de jour invalide => (1 jusqu\'à 7, dimanche = 1).'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid document type format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidDocumentTypeFormat(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Type de document invalide => (conditions_inscription,poster).'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid email format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidEmailFormat(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Format d\'adresse email invalide.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid login.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function invalidLogin(){
        $response['status_code_header'] = 'HTTP/1.1 400 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Identifiants de connexion invalides.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of upload failed.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function uploadFailed(){
        $response['status_code_header'] = 'HTTP/1.1 404 Not found';
        $response['body'] = json_encode([
            'error' => 'Échec d\'upload.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid image file format.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function imageFileFormatProblem(){
        $response['status_code_header'] = 'HTTP/1.1 404 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Format d\'image par pris en charge.'
        ]);
        return $response;
    }

    /**
     * 
     * Method to return the error message in case of invalid package number of conditions of registration document.
     * 
     * @return string The status and the body in JSON format of the response
     */
    public static function packageNumberFormatProblem(){
        $response['status_code_header'] = 'HTTP/1.1 404 Bad Request';
        $response['body'] = json_encode([
            'error' => 'Numéro de Forfait invalide => (1 jusqu\'à 5).'
        ]);
        return $response;
    }

    
}