# 🔧 How to Switch to Mailgun

The code already supports Mailgun! Just follow these steps:

## ✅ Code Already Updated

The `mailfunction.php` already includes Mailgun support. It will automatically use Mailgun if `MAILGUN_API_KEY` and `MAILGUN_DOMAIN` are set.

## 📋 Steps to Switch

### 1. Install Mailgun Package

The `composer.json` already includes Mailgun. After pushing to Railway, it will automatically install.

Or manually run:
```bash
composer require mailgun/mailgun-php symfony/http-client
```

### 2. Add Mailgun Variables to Railway

1. Go to Railway Dashboard → Your Service → **Variables**
2. Add:
   - **Name:** `MAILGUN_API_KEY`
     - **Value:** Your Mailgun Private API Key
   - **Name:** `MAILGUN_DOMAIN`
     - **Value:** Your verified domain (e.g., `sandbox-xxxxx.mailgun.org`)

### 3. Remove Old Service (Optional)

If switching from SendGrid, you can remove:
- ❌ `SENDGRID_API_KEY`

### 4. Keep These Variables

- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com` (sender email)
- ✅ `MAIL_FROM_NAME=Website Form`

### 5. Test

Submit the contact form - it will automatically use Mailgun!

## 🎯 Priority Order

The code tries email services in this order:
1. **Resend** (if `RESEND_API_KEY` is set)
2. **Mailgun** (if `MAILGUN_API_KEY` + `MAILGUN_DOMAIN` are set) ← You're here
3. **SendGrid** (if `SENDGRID_API_KEY` is set)
4. **SMTP** (fallback, may not work on Railway)

So if you set `MAILGUN_API_KEY` and `MAILGUN_DOMAIN`, it will use Mailgun automatically (unless Resend is also set)!

## ✅ That's It!

No code changes needed - just add the Mailgun variables to Railway and it will work!
