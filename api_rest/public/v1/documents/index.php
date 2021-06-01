<?php
/**
 * index.php
 *
 * File being the front controller of the API and allowing to process document requests.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

use App\Controllers\DocumentController;
use App\Models\Document;

require "../../../bootstrap.php";

header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET,POST,PATCH,DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

$requestMethod = $_SERVER["REQUEST_METHOD"];

if (strcmp("OPTIONS", $requestMethod) == 0) {
	header('Allow: GET,POST,PATCH,DELETE');
	return;
}

$controller = new DocumentController($dbConnection);

$path = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$pathFragments = explode('/', $path);
$id = intval(end($pathFragments));

$document = new Document();
$document->id = $id ?? null;

switch ($requestMethod) {
    case 'GET':
        if (empty($id) || !is_numeric($id)) {
            $response = $controller->getAllDocuments();
        }
        else{
            $response = $controller->getDocument($id);
        }
        break;

    case 'POST':
        $document->document_serial_id = $_POST["document_serial_id"] ?? null;
        $document->type = $_POST["type"] ?? null;
        $document->user_id = isset($_POST["user_id"]) && is_numeric($_POST["user_id"]) ? $_POST["user_id"] : null;
        $document->package_number =  isset($_POST["package_number"]) && is_numeric($_POST["package_number"]) ? $_POST["package_number"] : null;
        $document->signature_base64 = $_POST["signature_base64"] ?? null;
        $response = $controller->createDocument($document);
        break;

    case 'PATCH':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->updateDocument($document);
        break;

    case 'DELETE':
        if (empty($id) || !is_numeric($id)) {
            header("HTTP/1.1 404 Not Found");
            exit();
        }
        $response = $controller->deleteDocument($id);
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
