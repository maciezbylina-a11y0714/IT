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

// Try to load Resend class - check multiple possible class names
if (class_exists('Resend', true)) {
    // Global namespace Resend
} elseif (class_exists('Resend\Resend', true)) {
    // Namespaced Resend\Resend
    class_alias('Resend\Resend', 'Resend');
} elseif (class_exists('Resend\Client', true)) {
    // Alternative namespace
    class_alias('Resend\Client', 'Resend');
}

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
        
        // Try multiple class names - Resend package may use different class structure
        $resendClass = null;
        if (class_exists('Resend', true)) {
            $resendClass = 'Resend';
            error_log("Resend: Found class 'Resend' in global namespace");
        } elseif (class_exists('Resend\Resend', true)) {
            $resendClass = 'Resend\Resend';
            error_log("Resend: Found class 'Resend\Resend'");
        } elseif (class_exists('Resend\Client', true)) {
            $resendClass = 'Resend\Client';
            error_log("Resend: Found class 'Resend\Client'");
        } else {
            error_log("ERROR: Resend class not found in any namespace. Checking autoloader...");
            error_log("Vendor directory exists: " . (is_dir(__DIR__ . '/../vendor') ? "YES" : "NO"));
            error_log("Autoload file exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? "YES" : "NO"));
            
            // Check if Resend package directory exists
            $resendPackagePath = __DIR__ . '/../vendor/resend/resend-php';
            if (is_dir($resendPackagePath)) {
                error_log("Resend package directory exists: YES");
                
                // Resend class is in global namespace, but autoloader may not be finding it
                // Try to manually require the Resend.php file
                // First, ensure autoloader is loaded and trigger it for dependencies
                $resendMainFile = $resendPackagePath . '/src/Resend.php';
                if (file_exists($resendMainFile)) {
                    error_log("Resend main file exists: " . $resendMainFile);
                    
                    // Ensure autoloader is registered
                    $autoloadPath = __DIR__ . '/../vendor/autoload.php';
                    if (file_exists($autoloadPath)) {
                        require_once $autoloadPath;
                        error_log("Autoloader loaded");
                    }
                    
                    // Manually require all Resend dependencies before requiring Resend.php
                    // The autoloader isn't finding them, so we'll load them directly
                    $resendSrcPath = $resendPackagePath . '/src';
                    $dependencyFiles = [
                        $resendSrcPath . '/ValueObjects/ApiKey.php',
                        $resendSrcPath . '/ValueObjects/Transporter/BaseUri.php',
                        $resendSrcPath . '/ValueObjects/Transporter/Headers.php',
                        $resendSrcPath . '/Transporters/HttpTransporter.php',
                        $resendSrcPath . '/Client.php',
                    ];
                    
                    foreach ($dependencyFiles as $file) {
                        if (file_exists($file)) {
                            require_once $file;
                            error_log("Loaded: " . basename($file));
                        } else {
                            error_log("WARNING: Dependency file not found: " . $file);
                        }
                    }
                    
                    // Now require Resend.php - dependencies should already be loaded
                    require_once $resendMainFile;
                    error_log("Manually required Resend.php file");
                    
                    // Verify the class exists
                    if (class_exists('Resend', false)) {
                        $resendClass = 'Resend';
                        error_log("Resend class found after manual require");
                    } else {
                        error_log("ERROR: Resend class still not found after manual require");
                    }
                } else {
                    error_log("Resend main file not found at: " . $resendMainFile);
                }
            } else {
                error_log("Resend package directory does not exist at: " . $resendPackagePath);
            }
            
            if (!$resendClass) {
                error_log("ERROR: Could not find Resend class after all attempts");
                return false;
            }
        }
        
        // Instantiate Resend client
        try {
            $resend = $resendClass::client($resend_api_key);
            error_log("Resend: Client initialized successfully using class: " . $resendClass);
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