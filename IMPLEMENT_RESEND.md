# 🔧 How to Switch to Resend

The code already supports Resend! Just follow these steps:

## ✅ Code Already Updated

The `mailfunction.php` already includes Resend support. It will automatically use Resend if `RESEND_API_KEY` is set.

## 📋 Steps to Switch

### 1. Install Resend Package

The `composer.json` already includes Resend. After pushing to Railway, it will automatically install.

Or manually run:
```bash
composer require resend/resend-php
```

### 2. Add Resend API Key to Railway

1. Go to Railway Dashboard → Your Service → **Variables**
2. Add:
   - **Name:** `RESEND_API_KEY`
   - **Value:** Your Resend API key (from `RESEND_SETUP.md`)

### 3. Remove Old Service (Optional)

If switching from SendGrid, you can remove:
- ❌ `SENDGRID_API_KEY`

### 4. Keep These Variables

- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com` (sender email)
- ✅ `MAIL_FROM_NAME=Website Form`

### 5. Test

Submit the contact form - it will automatically use Resend!

## 🎯 Priority Order

The code tries email services in this order:
1. **Resend** (if `RESEND_API_KEY` is set)
2. **Mailgun** (if `MAILGUN_API_KEY` + `MAILGUN_DOMAIN` are set)
3. **SendGrid** (if `SENDGRID_API_KEY` is set)
4. **SMTP** (fallback, may not work on Railway)

So if you set `RESEND_API_KEY`, it will use Resend automatically!

## ✅ That's It!

No code changes needed - just add the `RESEND_API_KEY` variable to Railway and it will work!
