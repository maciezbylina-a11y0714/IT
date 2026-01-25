<?php
    // Use environment variables if available (for Railway deployment), otherwise use defaults
    $mail_host = getenv('MAIL_HOST') ?: "smtp.gmail.com";
    $mail_port = getenv('MAIL_PORT') ?: "587";
    $mail_sender_email = getenv('MAIL_USERNAME') ?: ""; //sender
    $mail_sender_password = getenv('MAIL_PASSWORD') ?: ""; //sender
    $mail_sender_name = getenv('MAIL_FROM_NAME') ?: "Website Form";
?>