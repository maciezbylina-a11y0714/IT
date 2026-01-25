<?php   
    require("./mailing/mailfunction.php");

    $name = $_POST["name"];
    $phone = $_POST['phone'];
    $email = $_POST["email"];
    $message = $_POST["message"];

    $body = "<ul><li>Name: ".$name."</li><li>Phone: ".$phone."</li><li>Email: ".$email."</li><li>Message: ".$message."</li></ul>";

    $receiver_email = getenv('RECEIVER_EMAIL') ?: ""; // Get from environment variable (set to your Gmail in Railway)
    $status = mailfunction($receiver_email, "Allyson Tech Solutions", $body); //reciever
    if($status)
        echo '<center><h1>Thanks! We will contact you soon.</h1></center>';
    else
        echo '<center><h1>Error sending message! Please try again.</h1></center>';    
?>