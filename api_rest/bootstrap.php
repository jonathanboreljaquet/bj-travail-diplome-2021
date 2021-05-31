<?php
/**
 * bootstrap.php
 *
 * Bootable file of the API.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

require 'vendor/autoload.php';
use Dotenv\Dotenv;
use App\System\DatabaseConnector;



// Loads the environment variables from the .env file
$dotenv = new DotEnv(__DIR__);
$dotenv->load();


$dbConnection = (new DatabaseConnector())->getConnection();














