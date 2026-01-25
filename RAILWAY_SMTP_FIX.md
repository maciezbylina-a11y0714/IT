# 🔧 Railway SMTP Connection Timeout Fix

## ❌ Current Error
```
PHPMailer: SMTP ERROR: Failed to connect to server: Connection timed out (110)
```

## 🔍 Problem
Railway is **blocking outbound SMTP connections** on port 587. This is common with cloud platforms.

## ✅ Solution 1: Use Port 465 (SSL) Instead

Port 465 uses SSL encryption and is often less restricted than port 587.

### Step 1: Update Railway Variables
Go to Railway Dashboard → Your Service → Variables and change:
```
MAIL_PORT=465
```
(Change from `587` to `465`)

### Step 2: Code Already Updated
The code now automatically uses `ENCRYPTION_SMTPS` (SSL) when port is 465, and `ENCRYPTION_STARTTLS` (TLS) for port 587.

### Step 3: Redeploy
After changing the variable, Railway will automatically redeploy.

### Step 4: Test
Submit the contact form again and check if emails are sent.

---

## ✅ Solution 2: Use Email Service API (Recommended if SMTP Still Fails)

If port 465 also times out, use an email service API instead of SMTP:

### Option A: SendGrid (Free tier: 100 emails/day)

1. **Sign up**: https://sendgrid.com
2. **Get API Key**: Settings → API Keys → Create API Key
3. **Install**: `composer require sendgrid/sendgrid`
4. **Update Railway Variables**:
   ```
   SENDGRID_API_KEY=your_api_key_here
   ```
5. **Replace SMTP code** with SendGrid API calls

### Option B: Mailgun (Free tier: 5,000 emails/month)

1. **Sign up**: https://mailgun.com
2. **Get API Key**: Dashboard → Settings → API Keys
3. **Install**: `composer require mailgun/mailgun-php`
4. **Update Railway Variables**:
   ```
   MAILGUN_API_KEY=your_api_key
   MAILGUN_DOMAIN=your_domain
   ```
5. **Replace SMTP code** with Mailgun API calls

### Option C: Resend (Free tier: 3,000 emails/month)

1. **Sign up**: https://resend.com
2. **Get API Key**: Dashboard → API Keys
3. **Install**: `composer require resend/resend-php`
4. **Update Railway Variables**:
   ```
   RESEND_API_KEY=your_api_key
   ```
5. **Replace SMTP code** with Resend API calls

---

## 🎯 Recommended Action Plan

1. **Try Solution 1 first** (port 465) - it's the quickest fix
2. **If that fails**, implement Solution 2 with SendGrid or Mailgun
3. **Email service APIs** are more reliable in cloud environments

---

## 📝 Current Status

- ✅ Code updated to support both port 465 (SSL) and 587 (TLS)
- ✅ SMTP debug enabled (will show detailed connection info)
- ⏳ **Action needed**: Change `MAIL_PORT` to `465` in Railway Variables

---

## 🚀 Quick Fix Steps

1. Railway Dashboard → Your Service → Variables
2. Find `MAIL_PORT`
3. Change value from `587` to `465`
4. Save (Railway will auto-redeploy)
5. Test contact form
6. Check Railway logs for success/errors

If port 465 also times out, we'll need to switch to an email service API.
