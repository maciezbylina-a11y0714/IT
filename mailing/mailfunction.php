<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

require('./vendor/autoload.php');
require 'mailingvariables.php';

function mailfunction($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false){

    $mail = new PHPMailer();
    $mail->isSMTP();

    // Enable debug for troubleshooting (disable in production)
    $mail->SMTPDebug = SMTP::DEBUG_SERVER; // Temporarily enabled to diagnose SMTP connection issues
    $mail->Debugoutput = function($str, $level) {
        error_log("PHPMailer: $str");
    };

    $mail->Host = $GLOBALS['mail_host'];

    $mail->Port = intval($GLOBALS['mail_port']);
    
    // Use SMTPS (SSL) for port 465, STARTTLS for port 587
    if (intval($GLOBALS['mail_port']) == 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // SSL
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; // TLS
    }
    
    // Additional settings for Railway/cloud environments
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    // Set timeout for Railway
    $mail->Timeout = 30;

    $mail->SMTPAuth = true;

    $mail->Username = $GLOBALS['mail_sender_email'];

    $mail->Password = $GLOBALS['mail_sender_password'];

    $mail->setFrom($GLOBALS['mail_sender_email'], $GLOBALS['mail_sender_name']);

    $mail->addAddress($mail_reciever_email, $mail_reciever_name);

    $mail->Subject = 'Someone Contacted You!';

    $mail->isHTML($isHtml = true );

    $mail->msgHTML($mail_msg);


    if($attachment !== false){
        $mail->AddAttachment($attachment);
    }
    
    $mail->AltBody = 'This is a plain-text message body';
 
    if (!$mail->send()) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    } else {
        error_log("Email sent successfully to: " . $mail_reciever_email);
        return true;
    }
}

?>