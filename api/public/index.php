<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

$mail = new PHPMailer(true);

//Server settings
try{
$mail->isSMTP();                                            //Send using SMTP
$mail->Host       = 'mail.infomaniak.com';                     //Set the SMTP server to send through
$mail->SMTPAuth   = true;                                   //Enable SMTP authentication
$mail->Username   = 'dev@boreljaquet.ch';                     //SMTP username
$mail->Password   = 'DsJSH_HNhR8';                               //SMTP password
$mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;         //Enable TLS encryption; `PHPMailer::ENCRYPTION_SMTPS` encouraged
$mail->Port       = 587; 

$mail->setFrom('dev@boreljaquet.ch', 'Dev');
$mail->addAddress('jonathan.brljq@eduge.ch', 'Jonathan Borel-Jaquet');     //Add a recipient
$mail->addReplyTo('dev@boreljaquet.ch', 'Information');

  //Content
$mail->isHTML(true);                                  //Set email format to HTML
$mail->Subject = 'Here is the subject';
$mail->Body    = 'This is the HTML message body <b>in bold!</b>';
$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';

var_dump($mail->send());
echo 'Message has been sent';
}
catch (Exception $e) {
    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
}