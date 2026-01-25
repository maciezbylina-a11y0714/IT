<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;

// Load autoloader from project root (mailing/ is a subdirectory)
$autoloadPath = __DIR__ . '/../vendor/autoload.php';
if (file_exists($autoloadPath)) {
    require_once $autoloadPath;
    
    // Verify autoloader is working by checking if it can find a known class
    if (!class_exists('PHPMailer\PHPMailer\PHPMailer', true)) {
        error_log("ERROR: Autoloader loaded but PHPMailer not found - autoloader may not be working");
        error_log("Autoloader path: " . $autoloadPath);
        error_log("Autoloader file exists: " . (file_exists($autoloadPath) ? "YES" : "NO"));
        error_log("Registered autoloaders: " . (function_exists('spl_autoload_functions') ? count(spl_autoload_functions()) : "N/A"));
        
        // Check if autoloader files exist
        $autoloadFilesPath = __DIR__ . '/../vendor/composer';
        if (is_dir($autoloadFilesPath)) {
            error_log("Composer autoload files directory exists: YES");
            error_log("autoload_classmap.php exists: " . (file_exists($autoloadFilesPath . '/autoload_classmap.php') ? "YES" : "NO"));
            error_log("autoload_psr4.php exists: " . (file_exists($autoloadFilesPath . '/autoload_psr4.php') ? "YES" : "NO"));
        } else {
            error_log("ERROR: Composer autoload files directory does not exist at: " . $autoloadFilesPath);
        }
    } else {
        error_log("Autoloader verified: PHPMailer found successfully");
    }
    
    // Ensure GuzzleHttp is available (required by Resend)
    // GuzzleHttp should be autoloaded by Composer, but if it's not working,
    // we'll manually load it
    if (!class_exists('GuzzleHttp\Client', true)) {
        error_log("WARNING: GuzzleHttp\Client not found via autoloader");
        error_log("Attempting to manually load GuzzleHttp...");
        
        $guzzleBasePath = __DIR__ . '/../vendor/guzzlehttp/guzzle/src';
        
        if (is_dir($guzzleBasePath)) {
            // Load GuzzleHttp files in dependency order
            // First, load PSR interfaces that GuzzleHttp depends on
            $psrHttpClientPath = __DIR__ . '/../vendor/psr/http-client/src';
            if (is_dir($psrHttpClientPath)) {
                $psrFiles = [
                    'ClientInterface.php',
                    'ClientExceptionInterface.php',
                    'NetworkExceptionInterface.php',
                    'RequestExceptionInterface.php'
                ];
                foreach ($psrFiles as $file) {
                    $fullPath = $psrHttpClientPath . '/' . $file;
                    if (file_exists($fullPath)) {
                        require_once $fullPath;
                    }
                }
            }
            
            // Load GuzzleHttp Promise classes first (dependencies)
            $promisePath = $guzzleBasePath . '/Promise';
            if (is_dir($promisePath)) {
                $promiseFiles = [
                    'PromiseInterface.php',
                    'Create.php',
                    'Promise.php',
                    'RejectionException.php',
                    'TaskQueue.php',
                    'TaskQueueInterface.php',
                    'Utils.php'
                ];
                foreach ($promiseFiles as $file) {
                    $fullPath = $promisePath . '/' . $file;
                    if (file_exists($fullPath)) {
                        require_once $fullPath;
                    }
                }
            }
            
            // Load GuzzleHttp PSR7 classes (must load traits before classes that use them)
            $guzzlePsr7Path = __DIR__ . '/../vendor/guzzlehttp/psr7/src';
            if (is_dir($guzzlePsr7Path)) {
                // Load PSR-7 interfaces first
                $psrMessagePath = __DIR__ . '/../vendor/psr/http-message/src';
                if (is_dir($psrMessagePath)) {
                    $psrMessageFiles = glob($psrMessagePath . '/*.php');
                    foreach ($psrMessageFiles as $file) {
                        require_once $file;
                    }
                }
                
                // Load GuzzleHttp PSR7 files in dependency order
                // First, load base classes and traits
                $psr7BaseFiles = [
                    'MessageTrait.php',
                    'StreamTrait.php',
                    'StreamDecoratorTrait.php',
                    'UriNormalizer.php',
                    'Uri.php',
                    'Stream.php',
                    'Message.php',
                    'Request.php',
                    'Response.php',
                    'ServerRequest.php',
                    'UploadedFile.php',
                    'UriResolver.php',
                    'NoSeekStream.php',
                    'PumpStream.php',
                    'InflateStream.php',
                    'LazyOpenStream.php',
                    'AppendStream.php',
                    'BufferStream.php',
                    'CachingStream.php',
                    'DroppingStream.php',
                    'FnStream.php',
                    'LimitStream.php',
                    'MultipartStream.php',
                    'Utils.php'
                ];
                
                foreach ($psr7BaseFiles as $file) {
                    $fullPath = $guzzlePsr7Path . '/' . $file;
                    if (file_exists($fullPath)) {
                        try {
                            require_once $fullPath;
                        } catch (\Throwable $e) {
                            error_log("WARNING: Error loading " . basename($file) . ": " . $e->getMessage());
                        }
                    }
                }
                
                // Then load any remaining files
                $allPsr7Files = glob($guzzlePsr7Path . '/*.php');
                foreach ($allPsr7Files as $file) {
                    if (basename($file) !== 'functions_include.php' && !in_array(basename($file), $psr7BaseFiles)) {
                        try {
                            require_once $file;
                        } catch (\Throwable $e) {
                            error_log("WARNING: Error loading " . basename($file) . ": " . $e->getMessage());
                        }
                    }
                }
            }
            
            // Load GuzzleHttp Exception classes
            $exceptionPath = $guzzleBasePath . '/Exception';
            if (is_dir($exceptionPath)) {
                $exceptionFiles = [
                    'GuzzleException.php',
                    'TransferException.php',
                    'RequestException.php',
                    'BadResponseException.php',
                    'ClientException.php',
                    'ServerException.php',
                    'TooManyRedirectsException.php',
                    'ConnectException.php'
                ];
                foreach ($exceptionFiles as $file) {
                    $fullPath = $exceptionPath . '/' . $file;
                    if (file_exists($fullPath)) {
                        require_once $fullPath;
                    }
                }
            }
            
            // Load GuzzleHttp core classes
            $coreFiles = [
                'HandlerStack.php',
                'RequestOptions.php',
                'Utils.php',
                'ClientTrait.php',
                'Client.php'
            ];
            foreach ($coreFiles as $file) {
                $fullPath = $guzzleBasePath . '/' . $file;
                if (file_exists($fullPath)) {
                    require_once $fullPath;
                }
            }
            
            // Check if it worked
            if (class_exists('GuzzleHttp\Client', false)) {
                error_log("GuzzleHttp\Client loaded manually successfully");
            } else {
                error_log("ERROR: GuzzleHttp\Client still not found after manual load");
                error_log("GuzzleHttp base path: " . $guzzleBasePath);
                error_log("GuzzleHttp base path exists: " . (is_dir($guzzleBasePath) ? "YES" : "NO"));
            }
        } else {
            error_log("ERROR: GuzzleHttp package directory not found at: " . $guzzleBasePath);
        }
    } else {
        error_log("GuzzleHttp\Client found via autoloader");
    }
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
        $resendResult = sendEmailViaResend($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
        if ($resendResult === true) {
            return true; // Success, no need to try other services
        }
        error_log("Resend failed, trying other email services...");
    } else {
        error_log("RESEND_API_KEY not found via getenv() or \$_ENV, checking other services...");
        error_log("Available env vars starting with RESEND: " . implode(", ", array_filter(array_keys($_ENV), function($k) { return strpos($k, 'RESEND') === 0; })));
    }
    
    // 2. Mailgun (Industry standard, excellent deliverability)
    $mailgun_api_key = getenv('MAILGUN_API_KEY') ?: "";
    $mailgun_domain = getenv('MAILGUN_DOMAIN') ?: "";
    if (!empty($mailgun_api_key) && !empty($mailgun_domain)) {
        error_log("Using Mailgun API for email delivery");
        $mailgunResult = sendEmailViaMailgun($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
        if ($mailgunResult === true) {
            return true; // Success, no need to try other services
        }
        error_log("Mailgun failed, trying other email services...");
    }
    
    // 3. SendGrid (Good balance)
    $sendgrid_api_key = getenv('SENDGRID_API_KEY') ?: "";
    if (!empty($sendgrid_api_key)) {
        error_log("Using SendGrid API for email delivery");
        $sendgridResult = sendEmailViaSendGrid($mail_reciever_email, $mail_reciever_name, $mail_msg, $attachment);
        if ($sendgridResult === true) {
            return true; // Success, no need to try other services
        }
        error_log("SendGrid failed, trying SMTP...");
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
        
        // Ensure GuzzleHttp is available before trying to use Resend
        if (!class_exists('GuzzleHttp\Client', true)) {
            error_log("ERROR: GuzzleHttp\Client not found - cannot use Resend without it");
            error_log("Falling back to other email services...");
            // Return false to trigger fallback to Mailgun/SendGrid/SMTP
            return false;
        }
        
        // Try to find Resend class - it's in the global namespace
        if (!class_exists('Resend', true)) {
            error_log("ERROR: Resend class not found");
            error_log("Checking if Resend package exists at: " . __DIR__ . '/../vendor/resend/resend-php');
            
            // Try to manually load Resend if autoloader failed
            $resendMainFile = __DIR__ . '/../vendor/resend/resend-php/src/Resend.php';
            if (file_exists($resendMainFile)) {
                // Load Resend dependencies first (ValueObjects, etc.)
                $resendSrcPath = __DIR__ . '/../vendor/resend/resend-php/src';
                
                // Load ValueObjects first (Resend needs ApiKey)
                $valueObjectsPath = $resendSrcPath . '/ValueObjects';
                if (is_dir($valueObjectsPath)) {
                    $valueObjectFiles = glob($valueObjectsPath . '/*.php');
                    foreach ($valueObjectFiles as $file) {
                        require_once $file;
                    }
                }
                
                // Load Contracts
                $contractsPath = $resendSrcPath . '/Contracts';
                if (is_dir($contractsPath)) {
                    $contractFiles = glob($contractsPath . '/*.php');
                    foreach ($contractFiles as $file) {
                        require_once $file;
                    }
                }
                
                // Load Resource base class
                $resourceFile = $resendSrcPath . '/Resource.php';
                if (file_exists($resourceFile)) {
                    require_once $resourceFile;
                }
                
                // Load Service base class
                $serviceFile = $resendSrcPath . '/Service/Service.php';
                if (file_exists($serviceFile)) {
                    require_once $serviceFile;
                }
                
                // Load Emails service (needed by Resend)
                $emailsServiceFile = $resendSrcPath . '/Service/Emails.php';
                if (file_exists($emailsServiceFile)) {
                    require_once $emailsServiceFile;
                }
                
                // Finally load Resend.php
                require_once $resendMainFile;
                
                if (class_exists('Resend', false)) {
                    error_log("Resend class loaded manually");
                } else {
                    error_log("ERROR: Resend class still not found after manual load");
                    return false;
                }
            } else {
                error_log("ERROR: Resend main file not found at: " . $resendMainFile);
                return false;
            }
        }
        
        // Now instantiate Resend client
        // This may fail if GuzzleHttp dependencies aren't properly loaded
        try {
            $resend = Resend::client($resend_api_key);
            error_log("Resend: Client initialized successfully");
        } catch (\Error $e) {
            // Catch fatal errors (like missing classes/traits)
            error_log("ERROR: Failed to initialize Resend client (Fatal Error): " . $e->getMessage());
            error_log("ERROR: This is likely due to missing GuzzleHttp dependencies. Falling back to other email services.");
            return false;
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