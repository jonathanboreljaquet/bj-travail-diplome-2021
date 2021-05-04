<?php
/**
 * HelperController.php
 *
 * Controller allowing the use of help functions.
 *
 * @link https://developer.mozilla.org/fr/docs/Web/HTTP/Status
 * @author  Jonathan Borel-Jaquet - CFPT / T.IS-ES2 <jonathan.brljq@eduge.ch>
 */

namespace App\Controllers;

use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Dompdf\Dompdf;
use App\System\cDX;

class HelperController {

     /**
     * 
     * Method to check if a date has the right format (YYYY-MM-DD).
     * 
     * @param string $date Date to check
     * @return bool
     */
    public static function validateDateFormat(string $date)
    {
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$date)) {
            return false;
        }

        $elements = explode("-",$date);

        if (!checkdate($elements[1],$elements[2],$elements[0])) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if a date has the right format (YYYY-MM-DD).
     * 
     * @param string $date Date to check
     * @return bool
     */
    public static function validateDateTimeFormat(string $datetime, string $format = 'Y-m-d H:i:s')
    {
        if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1]) (0[1-9]|1[0-9]|2[0-4]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",$datetime)) {
            return false;
        }

        $date = \DateTime::createFromFormat($format, $datetime);
        if ($date && $date->format($format) != $datetime) {
            return false;
        }
        return true;
    }

    /**
     * 
     * Method to check if a time has the right format (HH:MM:SS).
     * 
     * @param string $time Time to check
     * @return bool
     */
    public static function validateTimeFormat(string $time)
    {
        if (!preg_match("/^(0[1-9]|1[0-9]|2[0-4]):(0[0-9]|[1-5][0-9]):(0[0-9]|[1-5][0-9])$/",$time)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if a code day has the right format (1-6).
     * 
     * @param string $time code day to check
     * @return bool
     */
    public static function validateCodeDayFormat(string $code_day)
    {
        if (!preg_match("/^[1-7]$/",$code_day)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if the first time is smaller or equal to the second one.
     * 
     * @param string $firsttime First time to check
     * @param string $secondtime Second time to check
     * @return bool
     */
    public static function validateChornologicalTime(string $firsttime, string $secondtime)
    {
        $firstdate = strtotime($firsttime);
        $seconddate = strtotime($secondtime);

        if ($seconddate < $firstdate) {
           return false;
        }

        return true;
    }

    /**
     * 
     * Method to generate an api token.
     * 
     * @return string The api token
     */
    public static function generateApiToken()
    {
        return md5(microtime());
    }

    /**
     * 
     * Method to generate a random string.
     * 
     * @return string The random string
     */
    public static function generateRandomString() {
        $alphabet = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890';
        $password = array();
        $alphaLength = strlen($alphabet) - 1; 
        for ($i = 0; $i < 8; $i++) {
            $n = rand(0, $alphaLength);
            $password[] = $alphabet[$n];
        }
        return implode($password);
    }

    /**
     * 
     * Method to check if a document type has the right value (conditions_inscription,poster).
     * 
     * @param string $document_type document type to check
     * @return bool 
     */
    public static function validateDocumentTypeFormat(string $document_type)
    {
        if ($document_type == 'conditions_inscription' || $document_type == 'poster') {
            return true;
        }

        return false;
    }

    /**
     * 
     * Method to check if a email has the right format.
     * 
     * @param string $email email  to check
     * @return bool
     */
    public static function validateEmailFormat(string $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to check if a package number of conditions of registration document has the right format.
     * 
     * @param int $package_number Package number to check
     * @return bool
     */
    public static function validatePackageNumber(int $package_number)
    {
        if ($package_number < 1 || $package_number > 5) {
            return false;
        }

        return true;
    }

    /**
     * 
     * Method to send an email.
     * 
     * @param string $message Message to send
     * @param string $emailRecipient Recipient's email address
     * @return void
     */
    public static function sendMail(string $message,string $emailRecipient)
    {
    $mail = new PHPMailer(true);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output                                           //Send using SMTP
        $mail->Host       = getenv('SMTP_HOST');                     //Set the SMTP server to send through
        $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
        $mail->Username   = getenv('SMTP_USERNAME');                    //SMTP username
        $mail->Password   = getenv('SMTP_PASSWORD');                               //SMTP password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
        $mail->Port       = 587;                                    //TCP port to connect to, use 465 for `PHPMailer::ENCRYPTION_SMTPS` above

        //Recipients
        $mail->setFrom('noreplyfrom@hotmail.com', 'No reply');
        $mail->addAddress($emailRecipient); 

        //Content
        $mail->isHTML(true);                                  //Set email format to HTML
        $mail->Subject = 'Douceur de Chien';
        $mail->Body    = $message;

        $mail->send();
        echo 'Message has been sent';
    } catch (Exception $e) {
        echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
    }
    }

    /**
     * 
     * Method to convert a png image to jpeg image.
     * 
     * @return void
     */
    public static function pngTojpegConverter(string $filename)
    {
        $image = imagecreatefrompng ($filename);
        imagejpeg($image, $filename);
        imagedestroy($image);
    }

    /**
     * 
     * Method to store a conditions of registration.
     * 
     * @param string $filename The filename of conditions of registration
     * @param string $package_number The package number of conditions of registration
     * @param string $date The date of creation of conditions of registration
     * @param string $signature_base64 The signature image in base64 of conditions of registration
     * @param string $userfirstname The user firstname of owner of conditions of registration
     * @param string $userlastname The user lastname of owner of conditions of registration
     * 
     * @return void
     */
    public static function storeConditionsRegistration(string $filename,int $package_number,string $date, string $signature_base64,string $userfirstname, string $userlastname)
    {
        $dompdf = new DOMPDF();        
        ob_start();
        include HelperController::getDefaultDirectory()."resources/template/conditions_registration.php";
        $contents = ob_get_clean();

        $dompdf->loadHtml($contents);
        $dompdf->render();
        $output = $dompdf->output();
        file_put_contents(HelperController::getDefaultDirectory()."storage/app/conditions_registration/".$filename.".pdf", $output);
    }

    
    /**
     * 
     * Method to return the default directory of the API.
     * 
     * @return string The default directory
     */
    public static function getDefaultDirectory(){
        return $_SERVER["DOCUMENT_ROOT"]."/bj-travail-diplome-2021/api_rest/v1/";
    }
}

