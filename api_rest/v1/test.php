<?php

use App\Controllers\HelperController;

require 'bootstrap.php';

$date1="12:00:00";

$date2="12:00:00";



if (HelperController::validateChornologicalDate($date1,$date2)) {
    echo "C'est juste";
}
else{
    echo "C'est pas juste";
}

