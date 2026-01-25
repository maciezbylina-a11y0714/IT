<?php   
    require("./mailing/mailfunction.php");

    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $applyfor = $_POST["status"];
    $experience = $_POST["experience"];
    $otherdetails = $_POST["details"];

    $filename = $_FILES["fileToUpload"]["name"];
	$filetype = $_FILES["fileToUpload"]["type"];
	$filesize = $_FILES["fileToUpload"]["size"];
	$tempfile = $_FILES["fileToUpload"]["tmp_name"];
	// Create tmp-uploads directory if it doesn't exist
	$uploadDir = "tmp-uploads/";
	if (!file_exists($uploadDir)) {
		mkdir($uploadDir, 0777, true);
	}
	$filenameWithDirectory = $uploadDir . $name . "_" . time() . ".pdf";  // Use timestamp to avoid filename conflicts

    $body = "<ul><li>Name: ".$name."</li><li>Phone: ".$phone."</li><li>Email: ".$email."</li><li>Apply For: ".$applyfor."</li><li>Experience: ".$experience." Yrs.</li><li>Resume(Attached Below):</li></ul>";
	if(move_uploaded_file($tempfile, $filenameWithDirectory))
	{
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
		
		$status = mailfunction($receiver_email, "Allyson Tech Solutions", $body, $filenameWithDirectory); //reciever
        if($status) {
			error_log("Email sent successfully to: " . $receiver_email);
            echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
        } else {
			error_log("Failed to send email to: " . $receiver_email);
            echo '<center><h1>Error sending message! Please try again.</h1></center>';
        }
	}
	else 
	{
		echo "<center><h1>Error uploading file! Please try again.</h1></center>";
	}

?>