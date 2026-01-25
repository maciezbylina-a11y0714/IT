<?php
// Diagnostic page to check email configuration
// Access this at: https://your-railway-url/check-email-config.php

header('Content-Type: text/html; charset=utf-8');
?>
<!DOCTYPE html>
<html>
<head>
    <title>Email Configuration Check</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 20px; }
        .success { color: green; }
        .error { color: red; }
        .warning { color: orange; }
        .info { color: blue; }
        h2 { border-bottom: 2px solid #333; padding-bottom: 10px; }
        pre { background: #f5f5f5; padding: 10px; border-radius: 5px; }
    </style>
</head>
<body>
    <h1>Email Configuration Diagnostic</h1>
    
    <h2>Environment Variables</h2>
    <?php
    $receiver_email = getenv('RECEIVER_EMAIL') ?: "";
    $mail_host = getenv('MAIL_HOST') ?: "";
    $mail_port = getenv('MAIL_PORT') ?: "";
    $mail_username = getenv('MAIL_USERNAME') ?: "";
    $mail_password = getenv('MAIL_PASSWORD') ?: "";
    $mail_from_name = getenv('MAIL_FROM_NAME') ?: "";
    
    echo "<p><strong>RECEIVER_EMAIL:</strong> ";
    if (empty($receiver_email)) {
        echo '<span class="error">NOT SET (This is required!)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . htmlspecialchars($receiver_email);
    }
    echo "</p>";
    
    echo "<p><strong>MAIL_HOST:</strong> ";
    if (empty($mail_host)) {
        echo '<span class="warning">NOT SET (Using default: smtp.gmail.com)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . htmlspecialchars($mail_host);
    }
    echo "</p>";
    
    echo "<p><strong>MAIL_PORT:</strong> ";
    if (empty($mail_port)) {
        echo '<span class="warning">NOT SET (Using default: 587)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . htmlspecialchars($mail_port);
    }
    echo "</p>";
    
    echo "<p><strong>MAIL_USERNAME:</strong> ";
    if (empty($mail_username)) {
        echo '<span class="error">NOT SET (This is required!)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . htmlspecialchars($mail_username);
    }
    echo "</p>";
    
    echo "<p><strong>MAIL_PASSWORD:</strong> ";
    if (empty($mail_password)) {
        echo '<span class="error">NOT SET (This is required!)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . str_repeat('*', min(strlen($mail_password), 16)) . ' (length: ' . strlen($mail_password) . ')';
    }
    echo "</p>";
    
    echo "<p><strong>MAIL_FROM_NAME:</strong> ";
    if (empty($mail_from_name)) {
        echo '<span class="warning">NOT SET (Using default: Website Form)</span>';
    } else {
        echo '<span class="success">SET</span> - ' . htmlspecialchars($mail_from_name);
    }
    echo "</p>";
    ?>
    
    <h2>Configuration Status</h2>
    <?php
    $all_set = !empty($receiver_email) && !empty($mail_username) && !empty($mail_password);
    
    if ($all_set) {
        echo '<p class="success"><strong>✓ All required variables are set!</strong></p>';
        echo '<p class="info">If emails are still not being received, check Railway logs for PHPMailer errors.</p>';
    } else {
        echo '<p class="error"><strong>✗ Missing required environment variables!</strong></p>';
        echo '<p>Go to Railway Dashboard → Your Project → Variables and add the missing variables.</p>';
    }
    ?>
    
    <h2>Test Email Function</h2>
    <?php
    if ($all_set && isset($_GET['test'])) {
        require("./mailing/mailfunction.php");
        
        $test_body = "<p>This is a test email from Allyson Tech Solutions website.</p>";
        $test_body .= "<p>If you receive this, your email configuration is working correctly!</p>";
        
        echo '<p class="info">Attempting to send test email...</p>';
        
        $status = mailfunction($receiver_email, "Allyson Tech Solutions", $test_body);
        
        if ($status) {
            echo '<p class="success"><strong>✓ Test email sent successfully!</strong></p>';
            echo '<p>Check your inbox at: ' . htmlspecialchars($receiver_email) . '</p>';
        } else {
            echo '<p class="error"><strong>✗ Failed to send test email!</strong></p>';
            echo '<p>Check Railway logs for PHPMailer error details.</p>';
        }
    } else {
        if ($all_set) {
            echo '<p><a href="?test=1" style="background: #4F84C4; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;">Send Test Email</a></p>';
        } else {
            echo '<p class="warning">Configure all variables first, then test.</p>';
        }
    }
    ?>
    
    <h2>Railway Logs</h2>
    <p>Check Railway Dashboard → Your Service → Logs for detailed error messages.</p>
    <p>Look for messages starting with "ERROR:" or "PHPMailer Error:"</p>
    
    <h2>Quick Fix</h2>
    <p>If variables are missing, add these in Railway:</p>
    <pre>
RECEIVER_EMAIL=your-gmail@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
    </pre>
</body>
</html>
