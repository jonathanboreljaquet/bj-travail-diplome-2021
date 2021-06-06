<?php

require 'bootstrap.php';
//Créer un million d'utilisateur
// Pour chacun de ces utilisateurs, créer un weeklyschedule avec 10 time slots par jour pour le mois de juillet (2021-07-01 au 2021-07-31)
// Pour chacun de ces utilisateurs, créer un schedule override avec 5 time slot le 15 juillet (2021-07-15)
// Pour chacun de ces utilisateurs, créer des vacances pour la dernière semaine de juillet (2021-07-26 au 2021-07-31)

//INSERT USER
$sql = "INSERT INTO user (email, firstname, lastname, phonenumber, address, api_token, code_role, password_hash) VALUES ('educator@gmail.com','edu','cator','0781234567','Chemin des fleurs,1241 Puplinge','1234abc','2','PaSsW0rd3')";
for ($i=1; $i <= 100000; $i++) { 
    $sql .= ",('educator@gmail.com','edu','cator','0781234567','Chemin des fleurs,1241 Puplinge','1234abc','2','PaSsW0rd3')";
}
$dbConnection->exec($sql);

//INSERT WEEKLY_SCHEDULE
$sql = "INSERT INTO weekly_schedule (date_valid_from,date_valid_to,id_educator) VALUES ('2021-07-01', '2021-07-31',1)";
for ($i=1; $i <= 100000; $i++) { 
    $sql .= ",('2021-07-01', '2021-07-31',".$i.")";
}
$dbConnection->exec($sql);

//INSERT SCHEDULE OVERRIDE
$sql = "INSERT INTO schedule_override (date_schedule_override,id_educator) VALUES ('2021-07-15',1)";
for ($i=1; $i <= 100000; $i++) { 
    $sql .=",('2021-07-15',".$i.")";
}
$dbConnection->exec($sql);

//INSERT TIME SLOT FOR WEEKLY_SCHEDULE
$sql = "INSERT INTO time_slot (code_day,time_start,time_end,id_weekly_schedule,id_educator) VALUES ('1', '06:00:00', '07:00:00','1','1') ";
for ($i=1; $i <= 10000; $i++) { 
        for ($codeday=1; $codeday <= 7; $codeday++) { 
        $sql .= ",(".$codeday.", '06:00:00', '07:00:00',".$i.",".$i."),(".$codeday.", '07:00:00', '08:00:00',".$i.",".$i."),(".$codeday.", '08:00:00', '09:00:00',".$i.",".$i."),(".$codeday.", '09:00:00', '10:00:00',".$i.",".$i."),(".$codeday.", '10:00:00', '11:00:00',".$i.",".$i."),(".$codeday.", '11:00:00', '12:00:00',".$i.",".$i."),(".$codeday.", '12:00:00', '13:00:00',".$i.",".$i."),(".$codeday.", '13:00:00', '14:00:00',".$i.",".$i."),(".$codeday.", '14:00:00', '15:00:00',".$i.",".$i."),(".$codeday.", '15:00:00', '16:00:00',".$i.",".$i.")";
    }
}
$dbConnection->exec($sql);

//INSERT TIME SLOT FOR SCHEDULE OVERRIDE
$sql = "INSERT INTO time_slot (code_day,time_start,time_end,id_schedule_override,id_educator) VALUES ('1', '06:00:00', '07:00:00','1','1') ";
for ($i=1; $i <= 10000; $i++) { 
        $sql .= ",('1', '06:00:00', '08:00:00',".$i.",".$i."),('1', '08:00:00', '10:00:00',".$i.",".$i."),('1', '10:00:00', '12:00:00',".$i.",".$i."),('1', '12:00:00', '14:00:00',".$i.",".$i."),('1', '14:00:00', '16:00:00',".$i.",".$i.")";
}
$dbConnection->exec($sql);

//INSERT ABSENCE
$sql = "INSERT INTO absence (date_absence_from, date_absence_to, description, id_educator) VALUES ('2021-07-26','2021-07-31','vac','1')";
for ($i=1; $i <= 100000; $i++) { 
    $sql .= ",('2021-07-26','2021-07-31','vac',".$i.")";
}
$dbConnection->exec($sql);

//INSERT APPOINTMENT
$sql = "INSERT INTO `api_rest_douceur_de_chien`.`appointment` (`datetime_appointment`, `duration_in_hour`, `note_text`, `summary`, `user_id_customer`, `user_id_educator`) VALUES ('2021-06-05 01:22:00', '1', 'note', 'resume', '1', '1')";
for ($i=1; $i <= 100000; $i++) { 
    $sql .= ",('2021-07-15 08:00:00', '2', 'note', 'resume', '1', ".$i.")";
}
$dbConnection->exec($sql);
echo "done";

