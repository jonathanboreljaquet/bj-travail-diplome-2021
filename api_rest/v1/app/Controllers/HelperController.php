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
     * Method to load the email HTML CSS template.
     * 
     * @param string $title The title of the mail
     * @param string $content The content of the mail
     * @param string $importantContent The important content of the mail
     * @return bool
     */
    public static function loadMailTemplate(string $title, string $content, string $importantContent = null)
    {
        $html ='<!DOCTYPE html>
        <html lang="fr">
        <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width,initial-scale=1">
          <meta name="x-apple-disable-message-reformatting">
          <title></title>
          <style>
            table, td, div, h1, p {font-family: Arial, sans-serif;}
          </style>
        </head>
        <body style="margin:0;padding:0;">
          <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;background:#ffffff;">
            <tr>
              <td align="center" style="padding:0;">
                <table role="presentation" style="width:602px;border-collapse:collapse;border:1px solid #cccccc;border-spacing:0;text-align:left;">
                  <tr>
                    <td align="center" style="padding:40px 0 30px 0;">
                      <img src="cid:logo-image" alt="" width="300" style="height:auto;display:block;" />
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:36px 30px 42px 30px;">
                      <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;">
                        <tr>
                          <td style="padding:0 0 36px 0;color:#153643;">
                            <h1 style="font-size:24px;margin:0 0 20px 0;font-family:Arial,sans-serif;">'.$title.'</h1>
                            <p style="margin:0 0 12px 0;font-size:16px;line-height:24px;font-family:Arial,sans-serif;">'.$content.'</p>
                            <h3 style="font-size:18px;margin:0 0 20px 0;font-family:Arial,sans-serif;">'.$importantContent.'</h3>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                  <tr>
                    <td style="padding:30px;background:#3ea3d8;">
                      <table role="presentation" style="width:100%;border-collapse:collapse;border:0;border-spacing:0;font-size:9px;font-family:Arial,sans-serif;">
                        <tr>
                          <td style="padding:0;width:50%;" align="left">
                            <p style="margin:0;font-size:14px;line-height:16px;font-family:Arial,sans-serif;color:#ffffff;">
                              &reg; Douceur de Chien 2021<br/>
                              <a href="https://boreljaquet.ch/" style="color:#ffffff;text-decoration:underline;">Site web Douceur de Chien</a>
                            </p>
                          </td>
                          <td style="padding:0;width:50%;" align="right">
                            <table role="presentation" style="border-collapse:collapse;border:0;border-spacing:0;">
                              <tr>
                                <td style="padding:0 0 0 10px;width:38px;">
                                  <a href="http://www.twitter.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/tw_1.png" alt="Twitter" width="38" style="height:auto;display:block;border:0;" /></a>
                                </td>
                                <td style="padding:0 0 0 10px;width:38px;">
                                  <a href="http://www.facebook.com/" style="color:#ffffff;"><img src="https://assets.codepen.io/210284/fb_1.png" alt="Facebook" width="38" style="height:auto;display:block;border:0;" /></a>
                                </td>
                              </tr>
                            </table>
                          </td>
                        </tr>
                      </table>
                    </td>
                  </tr>
                </table>
              </td>
            </tr>
          </table>
        </body>
        </html>';
        return $html;
    }

    /**
     * 
     * Method to send an email.
     * 
     * @param string $message Message of the mail
     * @param string $subject Subject of the mail
     * @param string $emailRecipient Recipient's email address
     * @param string $importantMessage Important message if there is one
     * @param string $attachmentFilePath Path of the file to attach if there is one
     * @return void
     */
    public static function sendMail(string $message, string $subject,string $emailRecipient, string $importantMessage = null, string $attachmentFilePath = null)
    {
    $mail = new PHPMailer(true);
    $body = HelperController::loadMailTemplate($subject,$message,$importantMessage);
    try {
        //Server settings
        $mail->SMTPDebug = SMTP::DEBUG_SERVER;                      
        $mail->Host       = getenv('SMTP_HOST');                     
        $mail->SMTPAuth   = true;                                  
        $mail->Username   = getenv('SMTP_USERNAME');                   
        $mail->Password   = getenv('SMTP_PASSWORD');                             
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         
        $mail->Port       = 587; 

        //Recipients
        $mail->setFrom('noreply@douceurdechien.com', 'Douceur de Chien');
        $mail->addAddress($emailRecipient); 

        //Attachments
        if (!is_null($attachmentFilePath)) {
            $mail->addAttachment($attachmentFilePath);
        }

        //Content
        $mail->isHTML(true);     
        $mail->CharSet = 'UTF-8';      
        $mail->Encoding = 'base64';                      
        $mail->Subject = $subject;
        $mail->AddEmbeddedImage("./../../resources/image/logo_douceur_de_chien.png", "logo-image", "logo_douceur_de_chien.png");
        $mail->Body = $body;
        
        $mail->send();
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

