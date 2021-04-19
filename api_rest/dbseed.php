<?php
/**
 * dbseed.php
 *
 * File for test data generation.
 *
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */
require 'bootstrap.php';

function generateApiToken(){
    return md5(microtime());
}

$statement = "
CREATE TABLE IF NOT EXISTS `api-rest_douceur-de-chien`.`user` (
    `id` INT NOT NULL,
    `email` VARCHAR(100) NOT NULL,
    `firstname` VARCHAR(45) NOT NULL,
    `lastname` VARCHAR(45) NOT NULL,
    `phonenumber` VARCHAR(20) NOT NULL,
    `address` VARCHAR(45) NOT NULL,
    `api_token` VARCHAR(45) NOT NULL,
    `code_role` VARCHAR(10) NOT NULL,
    `password_hash` VARCHAR(45) NULL,
    `password_salt` VARCHAR(45) NULL,
    PRIMARY KEY (`id`))
ENGINE = InnoDB;
    
INSERT INTO `api-rest_douceur-de-chien`.`user`
    (id, email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash, password_salt)
VALUES
    (1, 'borel@eduge.ch', 'Jonathan', 'Borel-Jaquet', '0772345212', 'Route de Frontenex 99 1208 Genève','".generateApiToken()."','2', null, null),
    (2, 'ackermangue@eduge.ch', 'Gawen', 'Ackermangue', '0781355282', 'Chemin des Charmilles 12 1202 Genève','".generateApiToken()."','1', null, null),
    (3, 'merguez@eduge.ch', 'David', 'Merguez', '0714248272', 'Chemin des Charmilles 11 1202 Genève','".generateApiToken()."','1', null, null),
    (4, 'burger@eduge.ch', 'Flo', 'Burger', '0791924210', 'Route de Satigny 07 1228 Genève','".generateApiToken()."','1', null, null),
    (5, 'uber@eduge.ch', 'Fabian', 'Uber', '0761735282', 'Route de Frontenex 89 1208 Genève','".generateApiToken()."','1', null, null),
    (6, 'alfiero@eduge.ch', 'Elena', 'Alfiero', '0721567812', 'Route de Frontenex 97 1208 Genève','".generateApiToken()."','1', null, null),
    (7, 'donetallo@eduge.ch', 'Daniel', 'Donatallo', '0771235496', 'Route des Acacias 45 1245 Genève','".generateApiToken()."','1', null, null),
    (8, 'alex@eduge.ch', 'Alexis', 'Lapon', '0793786791', 'Arthur la brose 12 1268 Genève','".generateApiToken()."','1', null, null),
    (9, 'eric@eduge.ch', 'Eric', 'Dubois', '0745761298', 'Chemin du soleil 127 1248 Genève','".generateApiToken()."','1', null, null)
";

try {
    $dbConnection->exec($statement);
    echo "Success";
} catch (PDOException $e) {
    exit($e->getMessage());
}
