# Railway Deployment Guide

This guide will help you deploy your IT Company Website to Railway.

## Prerequisites

1. A Railway account (sign up at https://railway.app)
2. Your GitHub repository connected to Railway
3. Email credentials for PHPMailer (Gmail SMTP recommended)

## Deployment Steps

### 1. Push to GitHub

First, make sure your code is pushed to GitHub:

```bash
git add .
git commit -m "Initial commit - ready for Railway deployment"
git push origin main
```

### 2. Connect to Railway

1. Go to https://railway.app and sign in
2. Click "New Project"
3. Select "Deploy from GitHub repo"
4. Choose your repository: `maciezbylina-a11y0714/IT`
5. Railway will automatically detect it's a PHP project

### 3. Configure Environment Variables

In Railway dashboard, go to your project → Variables tab and add:

**For Email Configuration:**
- `RECEIVER_EMAIL` = `allysondavid99@outlook.com` (Email to receive form submissions)
- `MAIL_HOST` = `smtp-mail.outlook.com` (For Outlook) or `smtp.gmail.com` (For Gmail)
- `MAIL_PORT` = `587`
- `MAIL_USERNAME` = Your sender email address
- `MAIL_PASSWORD` = Your email app password (see below)
- `MAIL_FROM_NAME` = `Website Form`

**Note:** 
- **For Outlook:** Get App Password at https://account.microsoft.com/security/app-passwords
- **For Gmail:** Get App Password at https://myaccount.google.com/apppasswords
- Enable 2-Factor Authentication first, then generate App Password
- Use the App Password as `MAIL_PASSWORD`

### 4. Update PHP Files

You'll need to update these files to use environment variables:

**mailing/mailingvariables.php:**
```php
<?php
    $mail_host = getenv('MAIL_HOST') ?: "smtp.gmail.com";
    $mail_port = getenv('MAIL_PORT') ?: "587";
    $mail_sender_email = getenv('MAIL_USERNAME') ?: "";
    $mail_sender_password = getenv('MAIL_PASSWORD') ?: "";
    $mail_sender_name = getenv('MAIL_FROM_NAME') ?: "Website Form";
?>
```

**contactme.php:**
```php
// Update line 11 to use environment variable:
$status = mailfunction(getenv('RECEIVER_EMAIL') ?: "", "Company", $body);
```

**careers.php:**
```php
// Update line 20 to use environment variable:
$status = mailfunction(getenv('RECEIVER_EMAIL') ?: "", "Company", $body, $filenameWithDirectory);
```

### 5. Create tmp-uploads Directory

Railway will need a writable directory for file uploads. The `careers.php` file expects a `tmp-uploads` folder. Make sure this directory exists and is writable.

### 6. Deploy

Railway will automatically:
- Install Composer dependencies
- Start the PHP server
- Assign a public URL

### 7. Custom Domain (Optional)

1. Go to Settings → Domains
2. Add your custom domain
3. Follow Railway's DNS instructions

## Troubleshooting

- **Email not working?** Check environment variables are set correctly
- **File uploads failing?** Ensure `tmp-uploads` directory exists and is writable
- **500 errors?** Check Railway logs in the dashboard

## Notes

- Railway uses the `Procfile` or `railway.json` to determine how to run your app
- The PHP built-in server is used for simplicity
- For production, consider using a proper web server like Nginx + PHP-FPM
