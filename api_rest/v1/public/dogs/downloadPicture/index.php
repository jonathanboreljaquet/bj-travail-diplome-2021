<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process upload dog picture.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\DogController;

require "../../../bootstrap.php";

$requestMethod = $_SERVER["REQUEST_METHOD"];

$controller = new DogController($dbConnection);

switch ($requestMethod) {
    case 'GET':
        $response = $controller->downloadDogPicture();
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
