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
    
    // Check if mail credentials are configured
    $mail_username = getenv('MAIL_USERNAME') ?: "";
    $mail_password = getenv('MAIL_PASSWORD') ?: "";
    
    if (empty($mail_username) || empty($mail_password)) {
        error_log("ERROR: MAIL_USERNAME or MAIL_PASSWORD environment variables are not set in Railway!");
        error_log("MAIL_USERNAME: " . (empty($mail_username) ? "EMPTY" : "SET"));
        error_log("MAIL_PASSWORD: " . (empty($mail_password) ? "EMPTY" : "SET"));
        echo '<center><h1 style="color: red;">Configuration Error: Email server credentials missing. Please set MAIL_USERNAME and MAIL_PASSWORD in Railway Variables.</h1></center>';
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