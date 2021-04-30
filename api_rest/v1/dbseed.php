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
    (id, email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash)
VALUES
    (1, 'borel@eduge.ch', 'Jonathan', 'Borel-Jaquet', '0772345212', 'Route de Frontenex 99 1208 Genève','e8e08012c93cce830cb19ff8e2977504','2', '$2y$10$8H2s458tYmy72XFrEw3DlOBvgn60NKo0itM/KVd6HVzd0gj4nmQPG'),
    (2, 'ackermangue@eduge.ch', 'Gawen', 'Ackermangue', '0781355282', 'Chemin des Charmilles 12 1202 Genève','16a58476139a21dac6d5a4cdb07441b1','2', null),
    (3, 'merguez@eduge.ch', 'David', 'Merguez', '0714248272', 'Chemin des Charmilles 11 1202 Genève','d51c9d05c1399ca18eace53a84ab00c6','2', null),
    (4, 'burger@eduge.ch', 'Flo', 'Burger', '0791924210', 'Route de Satigny 07 1228 Genève','3f71973da617595f8410114d3d5009e1','1', null),
    (5, 'uber@eduge.ch', 'Fabian', 'Uber', '0761735282', 'Route de Frontenex 89 1208 Genève','".generateApiToken()."','1', '$2y$10$8H2s458tYmy72XFrEw3DlOBvgn60NKo0itM/KVd6HVzd0gj4nmQPG'),
    (6, 'alfiero@eduge.ch', 'Elena', 'Alfiero', '0721567812', 'Route de Frontenex 97 1208 Genève','".generateApiToken()."','1', null),
    (7, 'donetallo@eduge.ch', 'Daniel', 'Donatallo', '0771235496', 'Route des Acacias 45 1245 Genève','".generateApiToken()."','1', null),
    (8, 'alex@eduge.ch', 'Alexis', 'Lapon', '0793786791', 'Arthur la brose 12 1268 Genève','".generateApiToken()."','1', null),
    (9, 'eric@eduge.ch', 'Eric', 'Dubois', '0745761298', 'Chemin du soleil 127 1248 Genève','".generateApiToken()."','1', null)";

$dogStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`dog`
    (id, name, breed, sex, picture_serial_id, chip_id, user_id)
VALUES
    (1, 'Paco', 'Staffy', 'Mâle', null, '123456789112345',4),
    (2, 'Hyron', 'Staffy', 'Mâle', null, '123451234512345',5),
    (3, 'Jaya', 'Rhodesian Ridgeback', 'Femelle', 'fYPxlcOc', '123123123123123',6)";

$documentStatement = "
INSERT INTO `api-rest_douceur-de-chien`.`document`
    (id, document_serial_id, type, user_id)
VALUES
    (1, 'ly5uy43256', 'conditions_inscription', 4),
    (2, 'p1yay43ko6', 'conditions_inscription', 5),
    (3, 'V9CUouI8.pdf', 'poster', 6),
    (4, 'mASE47FP', 'conditions_inscription', 4)";

$appoitmentStatement = "
INSERT INTO `api-rest_douceur-de-chien`.`appoitment`
    (id, datetime_appoitment, duration_in_hour, note_text, note_graphical_serial_id,summary,datetime_deletion,user_id_customer,user_id_educator,user_id_deletion)
VALUES
    (1, '2020-04-02 09:00:00',2, null, null, null, null, 4, 1, null),
    (2, '2020-05-12 10:00:00',3 ,null, null, null, null, 5, 2, null),
    (3, '2020-06-22 14:00:00',2 ,null, null, null, null, 6, 3, null)";

$absenceStatement = "
INSERT INTO `api-rest_douceur-de-chien`.`absence`
    (id, date_absence_from, date_absence_to, description, is_deleted, id_educator)
VALUES
    (1, '2021-01-01', '2021-01-07', 'Vacance de janvier',0 , 1),
    (2, '2021-04-05', '2021-04-11', 'Première Vacance d\'avril',0 , 1),
    (3, '2021-04-19', '2021-04-25', 'Deuxième Vacance d\'avril',1 , 1),
    (4, '2021-05-03', '2021-05-09', 'Déménagement',0 , 2),
    (5, '2021-06-07', '2021-06-13', 'Malade',0 , 3)";

$weeklyScheduleStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`weekly_schedule`
    (id, date_valid_from, date_valid_to, is_deleted, id_educator)
VALUES
    (1, '2021-03-29', '2021-05-09',0 , 1),
    (2, '2021-08-01', '2021-08-31',0 , 1),
    (3, '2021-09-01', '2021-09-30',1 , 1),
    (4, '2021-10-01', null,0 , 1),
    (5, '2021-04-26', '2021-06-06',0 , 2),
    (6, '2021-05-31', '2021-07-11',0 , 3)";

$scheduleOverrideStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`schedule_override`
    (id, date_schedule_override, is_deleted, id_educator)
VALUES
    (1, '2021-04-15', 0, 1),
    (2, '2021-02-11', 0, 1),
    (3, '2021-03-27', 1, 1),
    (4, '2021-05-17', 0, 2),
    (5, '2021-06-19', 0, 3)";

$timeSlotStatement = "

INSERT INTO `api-rest_douceur-de-chien`.`time_slot`
    (id, code_day, time_start, time_end,is_deleted, id_weekly_schedule, id_schedule_override, id_educator)
VALUES
    (1, 2,'08:00:00', '10:00:00',0, 1,null,1),
    (2, 3,'13:00:00', '15:00:00',0, 1,null,1),
    (3, 4,'17:00:00', '19:00:00',0, 1,null,1),
    (4, 5,'20:00:00', '21:00:00',1, 1,null,1),
    (5, 5,'14:00:00', '16:00:00',0, null,1,1),
    (6, 5,'13:00:00', '15:00:00',1, null,2,1),
    (7, 5,'09:00:00', '11:00:00',0, 2,null,2),
    (8, 6,'12:00:00', '14:00:00',0, 2,null,2),
    (9, 7,'18:00:00', '20:00:00',0, 2,null,2),
    (10, 2,'07:00:00', '09:00:00',0, null,2,2),
    (11, 4,'10:00:00', '12:00:00',0, 3,null,3),
    (12, 5,'15:00:00', '16:00:00',0, 3,null,3),
    (13, 6,'17:00:00', '19:00:00',0, 3,null,3),
    (14
    , 7,'11:00:00', '13:00:00',0, null,3,3)
";

try {
    $dbConnection->exec($userStatement);
    echo "Table user success";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($dogStatement);
    echo "Table dog success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}


try {
    $dbConnection->exec($documentStatement);
    echo "Table document success ";
} catch (PDOException $e) {
    exit($e->getMessage());
}

try {
    $dbConnection->exec($appoitmentStatement);
    echo "Table appoitment success ";
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



