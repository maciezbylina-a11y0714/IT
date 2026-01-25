# 🔧 Fix SMTP Connection Failed Error

## Current Error
```
PHPMailer Error: SMTP connect() failed
```

## ✅ What I've Fixed

1. **Syntax Error:** Fixed quote mismatch in `check-email-config.php` line 103
2. **SMTP Settings:** Added SSL options and timeout settings for Railway

## 🔍 Root Cause

The "SMTP connect() failed" error means PHPMailer cannot establish a connection to Gmail's SMTP server. This can happen because:

1. **Railway Network Restrictions:** Railway might block outbound SMTP connections on port 587
2. **SSL/TLS Issues:** Certificate verification problems
3. **Firewall/Network:** Connection timeout or blocked ports

## 🚀 Solutions

### Solution 1: Enable SMTP Debug (Temporary)

To see detailed connection errors, temporarily enable debug mode:

1. Edit `mailing/mailfunction.php`
2. Change line 15 from:
   ```php
   $mail->SMTPDebug = SMTP::DEBUG_OFF;
   ```
   To:
   ```php
   $mail->SMTPDebug = SMTP::DEBUG_SERVER;
   ```
3. Commit and push
4. Submit form again
5. Check Railway logs for detailed SMTP communication
6. **Disable debug** after troubleshooting

### Solution 2: Try Port 465 (SSL) Instead of 587 (TLS)

Some cloud platforms work better with SSL on port 465:

1. In Railway Variables, change:
   ```
   MAIL_PORT=465
   ```
2. In `mailing/mailfunction.php`, change:
   ```php
   $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS; // Instead of ENCRYPTION_STARTTLS
   ```
3. Redeploy and test

### Solution 3: Use Railway's SMTP Service (Recommended)

Railway might block direct SMTP. Consider using:

1. **SendGrid** (Free tier available)
2. **Mailgun** (Free tier available)
3. **Resend** (Free tier available)
4. **Postmark** (Free tier available)

These services provide HTTP APIs that work better in cloud environments.

### Solution 4: Check Railway Network Settings

1. Go to Railway Dashboard → Your Project → Settings
2. Check if there are any network/firewall restrictions
3. Verify outbound connections are allowed

## 📋 Current Configuration

Your Railway variables are set correctly:
- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_HOST=smtp.gmail.com`
- ✅ `MAIL_PORT=587`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com`
- ✅ `MAIL_PASSWORD=xpggceyzrruvgfgu`
- ✅ `MAIL_FROM_NAME=Website Form`

The issue is the **SMTP connection**, not the configuration.

## 🧪 Test Steps

1. **Enable SMTP Debug** (Solution 1)
2. **Submit form** and check Railway logs
3. **Look for specific error** in the debug output
4. **Try port 465** if 587 doesn't work (Solution 2)

## 💡 Alternative: Use Email Service API

If SMTP continues to fail, consider switching to an email service API:

**Example with SendGrid:**
```php
// Instead of PHPMailer, use SendGrid API
$apiKey = getenv('SENDGRID_API_KEY');
// Send via HTTP API instead of SMTP
```

This bypasses SMTP connection issues entirely.

## 🎯 Next Steps

1. **Try Solution 1** first (enable debug) to see the exact error
2. **Check Railway logs** for detailed SMTP communication
3. **Try Solution 2** (port 465) if port 587 is blocked
4. **Consider Solution 3** (email service API) if SMTP continues to fail

The code is now updated with better error handling. Check Railway logs after the next deployment for more details!
