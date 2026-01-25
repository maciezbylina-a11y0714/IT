<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Load autoloader from project root (mailing/ is a subdirectory)
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
} else {
    // Fallback to relative path
    require('./vendor/autoload.php');
}
require 'mailingvariables.php';

function mailfunction($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false){
    
    // Try email services in priority order (all work on Railway via HTTP API)
    
    // 1. Resend (Recommended - easiest, great free tier)
    // Try multiple methods to get environment variable (Railway compatibility)
    $resend_api_key = getenv('RESEND_API_KEY') ?: ($_ENV['RESEND_API_KEY'] ?? "");
    if (!empty($resend_api_key)) {
        error_log("Using Resend API for email delivery (API key length: " . strlen($resend_api_key) . ")");
        return sendEmailViaResend($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
    } else {
        error_log("RESEND_API_KEY not found via getenv() or \$_ENV, checking other services...");
        error_log("Available env vars starting with RESEND: " . implode(", ", array_filter(array_keys($_ENV), function($k) { return strpos($k, 'RESEND') === 0; })));
    }
    
    // 2. Mailgun (Industry standard, excellent deliverability)
    $mailgun_api_key = getenv('MAILGUN_API_KEY') ?: "";
    $mailgun_domain = getenv('MAILGUN_DOMAIN') ?: "";
    if (!empty($mailgun_api_key) && !empty($mailgun_domain)) {
        error_log("Using Mailgun API for email delivery");
        return sendEmailViaMailgun($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
    }
    
    // 3. SendGrid (Good balance)
    $sendgrid_api_key = getenv('SENDGRID_API_KEY') ?: "";
    if (!empty($sendgrid_api_key)) {
        error_log("Using SendGrid API for email delivery");
        return sendEmailViaSendGrid($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
    }
    
    // 4. Fallback to SMTP (may not work on Railway due to blocked ports)
    error_log("WARNING: No email API service configured (RESEND_API_KEY, MAILGUN_API_KEY, or SENDGRID_API_KEY). Falling back to SMTP which may not work on Railway.");
    return sendEmailViaSMTP($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
}

function sendEmailViaResend($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false) {
    try {
        $resend_api_key = getenv('RESEND_API_KEY');
        if (empty($resend_api_key)) {
            error_log("ERROR: RESEND_API_KEY is empty or not set!");
            return false;
        }
        
        $sender_email = getenv('MAIL_USERNAME') ?: $GLOBALS['mail_sender_email'];
        $sender_name = getenv('MAIL_FROM_NAME') ?: $GLOBALS['mail_sender_name'];
        
        error_log("Resend: Initializing client with API key (length: " . strlen($resend_api_key) . ")");
        
        // Resend class is in Resend\Resend namespace, not global namespace
        // Use autoloader to load Resend class and all its dependencies
        if (!class_exists('Resend\Resend', true)) {
            error_log("ERROR: Resend\Resend class not found. Autoloader may not be working.");
            error_log("Vendor directory exists: " . (is_dir(__DIR__ . '/../vendor') ? "YES" : "NO"));
            error_log("Autoload file exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? "YES" : "NO"));
            
            // Check if vendor/autoload.php was loaded
            if (!class_exists('Composer\Autoload\ClassLoader', false)) {
                error_log("ERROR: Composer autoloader not loaded. Re-requiring vendor/autoload.php");
                if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
                    require_once __DIR__ . '/../vendor/autoload.php';
                    
                    // Try again with autoload
                    if (!class_exists('Resend\Resend', true)) {
                        error_log("ERROR: Resend\Resend class still not found after re-requiring autoloader");
                        return false;
                    }
                } else {
                    error_log("ERROR: vendor/autoload.php not found!");
                    return false;
                }
            } else {
                error_log("ERROR: Autoloader is loaded but Resend\Resend class not found. Package may not be installed.");
                return false;
            }
        }
        
        // Instantiate Resend client (using fully qualified namespace)
        try {
            $resend = \Resend\Resend::client($resend_api_key);
            error_log("Resend: Client initialized successfully");
        } catch (\Exception $e) {
            error_log("ERROR: Failed to initialize Resend client: " . $e->getMessage());
            error_log("ERROR Trace: " . $e->getTraceAsString());
            return false;
        } catch (\Throwable $e) {
            error_log("ERROR: Failed to initialize Resend client (Throwable): " . $e->getMessage());
            error_log("ERROR Trace: " . $e->getTraceAsString());
            return false;
        }
        
        $params = [
            'from' => $sender_name . ' <' . $sender_email . '>',
            'to' => [$mail_reciever_email],
            'subject' => 'Someone Contacted You!',
            'html' => $mail_msg,
            'text' => strip_tags($mail_msg),
        ];
        
        if ($attachment !== false && file_exists($attachment)) {
            $file_content = file_get_contents($attachment);
            $file_encoded = base64_encode($file_content);
            $params['attachments'] = [
                [
                    'filename' => basename($attachment),
                    'content' => $file_encoded,
                ]
            ];
        }
        
        error_log("Resend: Sending email to " . $mail_reciever_email . " from " . $sender_email);
        $result = $resend->emails->send($params);
        
        // Check if result has id property (PHP 8.3 compatible)
        $resultId = property_exists($result, 'id') ? $result->id : null;
        if ($resultId !== null) {
            error_log("Resend: Email sent successfully to: " . $mail_reciever_email . " (ID: " . $resultId . ")");
            return true;
        } else {
            error_log("Resend Error: Result does not have id. Result: " . json_encode($result));
            return false;
        }
    } catch (Exception $e) {
        error_log("Resend Exception: " . $e->getMessage());
        error_log("Resend Exception Trace: " . $e->getTraceAsString());
        return false;
    } catch (\Throwable $e) {
        error_log("Resend Throwable: " . $e->getMessage());
        error_log("Resend Throwable Trace: " . $e->getTraceAsString());
        return false;
    }
}

function sendEmailViaMailgun($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false) {
    try {
        $mailgun_api_key = getenv('MAILGUN_API_KEY');
        $mailgun_domain = getenv('MAILGUN_DOMAIN');
        $sender_email = getenv('MAIL_USERNAME') ?: $GLOBALS['mail_sender_email'];
        $sender_name = getenv('MAIL_FROM_NAME') ?: $GLOBALS['mail_sender_name'];
        
        $mg = \Mailgun\Mailgun::create($mailgun_api_key);
        
        $params = [
            'from' => $sender_name . ' <' . $sender_email . '>',
            'to' => $mail_reciever_email,
            'subject' => 'Someone Contacted You!',
            'html' => $mail_msg,
            'text' => strip_tags($mail_msg),
        ];
        
        if ($attachment !== false && file_exists($attachment)) {
            $params['attachment'] = [
                ['filePath' => $attachment, 'filename' => basename($attachment)]
            ];
        }
        
        $result = $mg->messages()->send($mailgun_domain, $params);
        
        // Check if result has getId method and it returns non-null (PHP 8.3 compatible)
        $resultId = null !== $result && method_exists($result, 'getId') ? $result->getId() : null;
        if ($resultId !== null) {
            error_log("Mailgun: Email sent successfully to: " . $mail_reciever_email . " (ID: " . $resultId . ")");
            return true;
        } else {
            error_log("Mailgun Error: " . json_encode($result));
            return false;
        }
    } catch (Exception $e) {
        error_log("Mailgun Exception: " . $e->getMessage());
        return false;
    }
}

function sendEmailViaSendGrid($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false) {
    try {
        $sendgrid_api_key = getenv('SENDGRID_API_KEY');
        $sender_email = getenv('MAIL_USERNAME') ?: $GLOBALS['mail_sender_email'];
        $sender_name = getenv('MAIL_FROM_NAME') ?: $GLOBALS['mail_sender_name'];
        
        $email = new \SendGrid\Mail\Mail();
        $email->setFrom($sender_email, $sender_name);
        $email->setSubject('Someone Contacted You!');
        $email->addTo($mail_reciever_email, $mail_reciever_name);
        $email->addContent("text/html", $mail_msg);
        $email->addContent("text/plain", strip_tags($mail_msg));
        
        if ($attachment !== false && file_exists($attachment)) {
            $file_content = file_get_contents($attachment);
            $file_encoded = base64_encode($file_content);
            $email->addAttachment(
                $file_encoded,
                "application/pdf",
                basename($attachment),
                "attachment"
            );
        }
        
        $sendgrid = new \SendGrid($sendgrid_api_key);
        $response = $sendgrid->send($email);
        
        if ($response->statusCode() >= 200 && $response->statusCode() < 300) {
            error_log("SendGrid: Email sent successfully to: " . $mail_reciever_email);
            return true;
        } else {
            error_log("SendGrid Error: Status " . $response->statusCode() . " - " . $response->body());
            return false;
        }
    } catch (Exception $e) {
        error_log("SendGrid Exception: " . $e->getMessage());
        return false;
    }
}

function sendEmailViaSMTP($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment = false) {
    $mail = new PHPMailer();
    $mail->isSMTP();

    $mail->SMTPDebug = SMTP::DEBUG_OFF;
    $mail->Debugoutput = function($str, $level) {
        error_log("PHPMailer: $str");
    };

    $mail->Host = $GLOBALS['mail_host'];
    $mail->Port = intval($GLOBALS['mail_port']);
    
    if (intval($GLOBALS['mail_port']) == 465) {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }
    
    $mail->SMTPOptions = array(
        'ssl' => array(
            'verify_peer' => false,
            'verify_peer_name' => false,
            'allow_self_signed' => true
        )
    );
    
    $mail->Timeout = 30;
    $mail->SMTPAuth = true;
    $mail->Username = $GLOBALS['mail_sender_email'];
    $mail->Password = $GLOBALS['mail_sender_password'];
    $mail->setFrom($GLOBALS['mail_sender_email'], $GLOBALS['mail_sender_name']);
    $mail->addAddress($mail_reciever_email, $mail_reciever_name);
    $mail->Subject = 'Someone Contacted You!';
    $mail->isHTML(true);
    $mail->msgHTML($mail_msg);
    
    if($attachment !== false){
        $mail->AddAttachment($attachment);
    }
    
    $mail->AltBody = strip_tags($mail_msg);
 
    if (!$mail->send()) {
        error_log("PHPMailer Error: " . $mail->ErrorInfo);
        return false;
    } else {
        error_log("SMTP: Email sent successfully to: " . $mail_reciever_email);
        return true;
    }
}

?>