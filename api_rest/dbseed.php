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

$userStatement = "
    
INSERT INTO `api-rest_douceur-de-chien`.`user`
    (id, email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash, password_salt)
VALUES
    (1, 'borel@eduge.ch', 'Jonathan', 'Borel-Jaquet', '0772345212', 'Route de Frontenex 99 1208 Genève','e8e08012c93cce830cb19ff8e2977504','2', null, null),
    (2, 'ackermangue@eduge.ch', 'Gawen', 'Ackermangue', '0781355282', 'Chemin des Charmilles 12 1202 Genève','".generateApiToken()."','2', null, null),
    (3, 'merguez@eduge.ch', 'David', 'Merguez', '0714248272', 'Chemin des Charmilles 11 1202 Genève','".generateApiToken()."','2', null, null),
    (4, 'burger@eduge.ch', 'Flo', 'Burger', '0791924210', 'Route de Satigny 07 1228 Genève','".generateApiToken()."','1', null, null),
    (5, 'uber@eduge.ch', 'Fabian', 'Uber', '0761735282', 'Route de Frontenex 89 1208 Genève','".generateApiToken()."','1', null, null),
    (6, 'alfiero@eduge.ch', 'Elena', 'Alfiero', '0721567812', 'Route de Frontenex 97 1208 Genève','".generateApiToken()."','1', null, null),
    (7, 'donetallo@eduge.ch', 'Daniel', 'Donatallo', '0771235496', 'Route des Acacias 45 1245 Genève','".generateApiToken()."','1', null, null),
    (8, 'alex@eduge.ch', 'Alexis', 'Lapon', '0793786791', 'Arthur la brose 12 1268 Genève','".generateApiToken()."','1', null, null),
    (9, 'eric@eduge.ch', 'Eric', 'Dubois', '0745761298', 'Chemin du soleil 127 1248 Genève','".generateApiToken()."','1', null, null)";

$absenceStatement = "
INSERT INTO `api-rest_douceur-de-chien`.`absence`
    (id, date_absence_from, date_absence_to, description, is_deleted, id_educator)
VALUES
    (1, '2021-01-01', '2021-01-07', 'Vacance de janvier',0 , 1),
    (2, '2021-04-05', '2021-04-11', 'Vacance d\'avril',0 , 1),
    (3, '2021-05-03', '2021-05-09', 'Déménagement',0 , 2),
    (4, '2021-06-07', '2021-06-13', 'Malade',0 , 3)";

$weeklyScheduleStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`weekly_schedule`
    (id, date_valid_from, date_valid_to, is_deleted, id_educator)
VALUES
    (1, '2021-03-29', '2021-05-09',0 , 1),
    (2, '2021-04-26', '2021-06-06',0 , 2),
    (3, '2021-05-31', '2021-07-11',0 , 3)";

$scheduleOverrideStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`schedule_override`
    (id, date_schedule_override, is_deleted, id_educator)
VALUES
    (1, '2021-04-15', 0, 1),
    (2, '2021-05-17', 0, 2),
    (3, '2021-06-19', 0, 3)";

$timeSlotStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`time_slot`
    (id, code_day, time_start, time_end,is_deleted, id_weekly_schedule, id_schedule_override, id_educator)
VALUES
    (1, 2,'08:00:00', '10:00:00',0, 1,null,1),
    (2, 3,'13:00:00', '15:00:00',0, 1,null,1),
    (3, 4,'17:00:00', '19:00:00',0, 1,null,1),
    (4, 5,'14:00:00', '16:00:00',0, null,1,1),
    (5, 5,'09:00:00', '11:00:00',0, 2,null,2),
    (6, 6,'12:00:00', '14:00:00',0, 2,null,2),
    (7, 7,'18:00:00', '20:00:00',0, 2,null,2),
    (8, 2,'07:00:00', '09:00:00',0, null,2,2),
    (9, 4,'10:00:00', '12:00:00',0, 3,null,3),
    (10, 5,'15:00:00', '16:00:00',0, 3,null,3),
    (11, 6,'17:00:00', '19:00:00',0, 3,null,3),
    (12, 7,'11:00:00', '13:00:00',0, null,3,3)
";

try {
    $dbConnection->exec($userStatement);
    echo "Table user success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($absenceStatement);
    echo "Table absence success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($weeklyScheduleStatement);
    echo "Table weekly_schedule success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($scheduleOverrideStatement);
    echo "Table schedule_override success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($timeSlotStatement);
    echo "Table time_slot success";
} catch (PDOException $e) {
    exit($e->getMessage());
}



