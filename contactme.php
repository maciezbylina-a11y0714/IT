<?php   
    require("./mailing/mailfunction.php");

    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $body = "<ul><li>Name: ".$name."</li><li>Phone: ".$phone."</li><li>Email: ".$email."</li><li>Message: ".$message."</li></ul>";

    $receiver_email = getenv('RECEIVER_EMAIL') ?: ""; // Get from environment variable (set to your Gmail in Railway)
    
    // Check if receiver email is configured
    if (empty($receiver_email)) {
        error_log("ERROR: RECEIVER_EMAIL environment variable is not set in Railway!");
        echo '<center><h1 style="color: red;">Configuration Error: RECEIVER_EMAIL not set. Please configure in Railway Variables.</h1></center>';
        exit;
    }
    
    // Check if any email service is configured (Resend, Mailgun, SendGrid, or SMTP)
    $resend_api_key = getenv('RESEND_API_KEY') ?: "";
    $mailgun_api_key = getenv('MAILGUN_API_KEY') ?: "";
    $mailgun_domain = getenv('MAILGUN_DOMAIN') ?: "";
    $sendgrid_api_key = getenv('SENDGRID_API_KEY') ?: "";
    $mail_username = getenv('MAIL_USERNAME') ?: "";
    $mail_password = getenv('MAIL_PASSWORD') ?: "";
    
    // At least one email service must be configured
    $hasEmailService = (!empty($resend_api_key)) || 
                       (!empty($mailgun_api_key) && !empty($mailgun_domain)) ||
                       (!empty($sendgrid_api_key)) ||
                       (!empty($mail_username) && !empty($mail_password));
    
    if (!$hasEmailService) {
        error_log("ERROR: No email service configured! Set RESEND_API_KEY, MAILGUN_API_KEY+MAILGUN_DOMAIN, SENDGRID_API_KEY, or MAIL_USERNAME/MAIL_PASSWORD in Railway Variables.");
        echo '<center><h1 style="color: red;">Configuration Error: Email service not configured. Please set RESEND_API_KEY (recommended), MAILGUN_API_KEY+MAILGUN_DOMAIN, SENDGRID_API_KEY, or MAIL_USERNAME/MAIL_PASSWORD in Railway Variables.</h1></center>';
        exit;
    }
    
    error_log("Attempting to send email to: " . $receiver_email);
    error_log("Using sender: " . $mail_username);
    
    $status = mailfunction($receiver_email, "Apollo Technology", $body); //reciever
    
    if($status) {
        error_log("Email sent successfully to: " . $receiver_email);
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    } else {
        error_log("Failed to send email to: " . $receiver_email);
        echo '<center><h1>Error sending message! Please try again later or contact us directly.</h1></center>';
    }    
?>