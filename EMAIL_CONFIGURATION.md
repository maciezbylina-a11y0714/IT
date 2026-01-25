# 📧 Email Configuration Guide

## Receiver Email
**Receiver Email:** Your Gmail address (maciezbylina Gmail)

This email will receive:
- Contact form submissions
- Career/Job application submissions (with resume attachments)

**Note:** Set `RECEIVER_EMAIL` environment variable in Railway to your Gmail address.

---

## 🚂 Railway Deployment Configuration

### Step 1: Add Environment Variables in Railway

1. **Go to your Railway project dashboard**
2. **Click on your service** (the deployed website)
3. **Go to the "Variables" tab**
4. **Add the following environment variables:**

#### Required Variables:

| Variable Name | Value | Description |
|--------------|-------|-------------|
| `RECEIVER_EMAIL` | `your-gmail@gmail.com` | **Your Gmail address** (maciezbylina Gmail - receives form submissions) |
| `MAIL_HOST` | `smtp.gmail.com` | Gmail SMTP server |
| `MAIL_PORT` | `587` | SMTP port (587 for TLS) |
| `MAIL_USERNAME` | `your-gmail@gmail.com` | **Same Gmail address** (sender) |
| `MAIL_PASSWORD` | `xpggceyzrruvgfgu` | Gmail App Password (provided) |
| `MAIL_FROM_NAME` | `Website Form` | Display name for sent emails |

### Step 2: Gmail App Password

Your Gmail App Password is already provided: `xpggceyzrruvgfgu`

**Note:** Make sure:
- 2-Factor Authentication is enabled on your Gmail account
- The app password is correct (no spaces)
- Your Gmail address is set as `MAIL_USERNAME`

### Step 3: Railway Variable Setup Example

In Railway Variables tab, add:

```
RECEIVER_EMAIL=your-gmail@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
```

**Important:** 
- Replace `your-gmail@gmail.com` with your actual Gmail address (maciezbylina Gmail)
- Use the **same Gmail address** for both `RECEIVER_EMAIL` and `MAIL_USERNAME`

**Example:**
```
RECEIVER_EMAIL=maciezbylina@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=maciezbylina@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
```

### Step 4: Redeploy

After adding variables:
1. Railway will automatically redeploy
2. Or click "Redeploy" button
3. Wait for deployment to complete

---

## 💻 Local Development Configuration

### Option 1: Environment Variables (Recommended)

Create a `.env` file in your project root:

```env
RECEIVER_EMAIL=allysondavid99@outlook.com
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-app-password
MAIL_FROM_NAME=Website Form
```

**Note:** You'll need to load `.env` file in PHP (use a library like `vlucas/phpdotenv`)

### Option 2: Direct Configuration (For Testing)

The files already have the default email set to `allysondavid99@outlook.com`, so if you don't set environment variables, it will use this email.

To configure sender email, edit `mailing/mailingvariables.php`:

```php
<?php
    $mail_host = "smtp-mail.outlook.com";
    $mail_port = "587";
    $mail_sender_email = "your-email@outlook.com"; // Your Outlook email
    $mail_sender_password = "your-app-password"; // Outlook App Password
    $mail_sender_name = "Website Form";
?>
```

---

## 📋 Quick Reference

### Receiver Email
- **Contact Form:** Your Gmail address (set via `RECEIVER_EMAIL` in Railway)
- **Career Form:** Your Gmail address (set via `RECEIVER_EMAIL` in Railway)

### Gmail SMTP Settings (Current Setup)
- **Host:** `smtp.gmail.com`
- **Port:** `587`
- **Encryption:** TLS/STARTTLS
- **Authentication:** Required (use App Password: `xpggceyzrruvgfgu`)

### Outlook SMTP Settings (Alternative)
- **Host:** `smtp-mail.outlook.com`
- **Port:** `587`
- **Encryption:** TLS/STARTTLS
- **Authentication:** Required (use App Password)

### Gmail Alternative (If you prefer Gmail)

If you want to use Gmail instead:

| Variable | Value |
|----------|-------|
| `MAIL_HOST` | `smtp.gmail.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `your-email@gmail.com` |
| `MAIL_PASSWORD` | Gmail App Password |

**Gmail App Password:**
1. Go to: https://myaccount.google.com/apppasswords
2. Generate app password
3. Use it as `MAIL_PASSWORD`

---

## ✅ Verification Steps

After configuration:

1. **Test Contact Form:**
   - Fill out the contact form on your website
   - Submit it
   - Check your Gmail inbox (the address you set as `RECEIVER_EMAIL`)

2. **Test Career Form:**
   - Fill out the career application form
   - Upload a resume
   - Submit it
   - Check your Gmail inbox (should have resume attachment)

3. **Check Railway Logs:**
   - If emails don't arrive, check Railway logs for errors
   - Look for SMTP connection issues

---

## 🔧 Troubleshooting

### Emails Not Sending?

1. **Check Railway Variables:**
   - Make sure all variables are set correctly
   - No extra spaces in values
   - App password is correct (16 characters)

2. **Check Outlook Settings:**
   - Make sure 2FA is enabled
   - App password is generated correctly
   - Email account is active

3. **Check Railway Logs:**
   - Go to Railway dashboard → Your service → Logs
   - Look for PHP errors or SMTP errors

4. **Test SMTP Connection:**
   - Try sending a test email from PHP
   - Check if port 587 is open (Railway should handle this)

### Common Issues

- **"Authentication failed"**: Wrong app password or username
- **"Connection timeout"**: Check `MAIL_HOST` is correct
- **"Port blocked"**: Use port 587 (TLS) instead of 465 (SSL)

---

## 📝 Summary

**Receiver Email:** `allysondavid99@outlook.com` ✅

**Railway Setup:**
1. Add environment variables in Railway
2. Use Outlook App Password for `MAIL_PASSWORD`
3. Set `RECEIVER_EMAIL=allysondavid99@outlook.com`
4. Redeploy

**Local Setup:**
- Default email is already set in code
- Configure sender in `mailing/mailingvariables.php`

Your forms will now send emails to `allysondavid99@outlook.com`! 🎉
