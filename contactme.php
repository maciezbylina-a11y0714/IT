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
    
    // Check if SendGrid API key is configured (preferred) or SMTP credentials
    $sendgrid_api_key = getenv('SENDGRID_API_KEY') ?: "";
    $mail_username = getenv('MAIL_USERNAME') ?: "";
    $mail_password = getenv('MAIL_PASSWORD') ?: "";
    
    // SendGrid is preferred, but SMTP can be used as fallback
    if (empty($sendgrid_api_key) && (empty($mail_username) || empty($mail_password))) {
        error_log("ERROR: Either SENDGRID_API_KEY or MAIL_USERNAME/MAIL_PASSWORD must be set in Railway!");
        echo '<center><h1 style="color: red;">Configuration Error: Email service not configured. Please set SENDGRID_API_KEY (recommended) or MAIL_USERNAME/MAIL_PASSWORD in Railway Variables.</h1></center>';
        exit;
    }
    
    error_log("Attempting to send email to: " . $receiver_email);
    error_log("Using sender: " . $mail_username);
    
    $status = mailfunction($receiver_email, "Allyson Tech Solutions", $body); //reciever
    
    if($status) {
        error_log("Email sent successfully to: " . $receiver_email);
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    } else {
        error_log("Failed to send email to: " . $receiver_email);
        echo '<center><h1>Error sending message! Please try again later or contact us directly.</h1></center>';
    }    
?>