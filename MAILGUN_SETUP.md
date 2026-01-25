# 🚀 Mailgun Setup Guide

## ✅ Why Mailgun?

- **Industry standard** - Most popular email API
- **Excellent deliverability** - Best-in-class
- **Great analytics** - Detailed email tracking
- **Free tier:** 5,000 emails/month for 3 months, then 1,000/month
- **Works perfectly on Railway** - HTTP API (no SMTP blocking)

---

## 📋 Step-by-Step Setup

### Step 1: Create Mailgun Account

1. Go to: https://signup.mailgun.com/new/signup
2. Sign up with your email (use your Gmail: `maciezbylina@gmail.com`)
3. Verify your email address
4. Complete the account setup

### Step 2: Verify Domain

1. Log in to Mailgun Dashboard
2. Go to **Sending** → **Domains**
3. Click **"Add New Domain"**
4. For testing, you can use Mailgun's **sandbox domain** first:
   - Domain: `sandbox-xxxxx.mailgun.org` (provided by Mailgun)
   - This allows sending to **authorized recipients only**
5. For production, add your own domain:
   - Enter your domain (e.g., `allysontech.com`)
   - Add DNS records as instructed
   - Wait for verification (usually 24-48 hours)

### Step 3: Add Authorized Recipients (Sandbox Only)

If using sandbox domain:
1. Go to **Sending** → **Authorized Recipients**
2. Click **"Add New Recipient"**
3. Add: `maciezbylina@gmail.com`
4. Click the verification link sent to your email

### Step 4: Get API Key

1. Go to **Settings** → **API Keys**
2. Copy your **Private API Key**
   - It looks like: `xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx-xxxxxxxx-xxxxxxxx`
3. Keep this secure!

### Step 5: Update Code to Use Mailgun

The code needs to be updated to support Mailgun. See `IMPLEMENT_MAILGUN.md` for code changes.

### Step 6: Add Variables to Railway

1. Go to Railway Dashboard → Your Service → **Variables**
2. Add these variables:

**Required:**
- **Name:** `MAILGUN_API_KEY`
  - **Value:** Your Mailgun Private API Key
- **Name:** `MAILGUN_DOMAIN`
  - **Value:** Your verified domain (e.g., `sandbox-xxxxx.mailgun.org` or `allysontech.com`)

**Keep these:**
- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com` (sender email)
- ✅ `MAIL_FROM_NAME=Website Form`

**Remove (if switching):**
- ❌ `SENDGRID_API_KEY`
- ❌ `MAIL_HOST`, `MAIL_PORT`, `MAIL_PASSWORD`

---

## 🧪 Test

1. Submit the contact form on your website
2. Check your Gmail inbox (`maciezbylina@gmail.com`)
3. You should receive the email!

---

## 📊 Mailgun Dashboard

- **Monitor emails:** Dashboard → Logs
- **View statistics:** Dashboard → Analytics
- **Check delivery:** Dashboard → Logs → Filter by recipient

---

## 🔧 Troubleshooting

### "Email not received"
1. Check Railway logs for Mailgun errors
2. Check Mailgun Dashboard → Logs for delivery status
3. If using sandbox, make sure recipient is authorized
4. Check spam folder

### "Domain not verified"
1. Go to Mailgun → Sending → Domains
2. Check DNS records are correct
3. Wait 24-48 hours for DNS propagation

### "API Key Invalid"
1. Make sure you're using the **Private API Key** (not Public)
2. Check for extra spaces in Railway variable
3. Regenerate API key if needed

---

## 💡 Benefits of Mailgun

- ✅ Industry standard (most reliable)
- ✅ Excellent deliverability
- ✅ Great analytics and tracking
- ✅ Good free tier (5k/month for 3 months)
- ✅ Works perfectly on Railway

---

## 🎯 Quick Checklist

- [ ] Created Mailgun account
- [ ] Verified domain (or using sandbox)
- [ ] Added authorized recipients (sandbox only)
- [ ] Got API Key
- [ ] Updated code to use Mailgun (see `IMPLEMENT_MAILGUN.md`)
- [ ] Added `MAILGUN_API_KEY` and `MAILGUN_DOMAIN` to Railway
- [ ] Tested contact form
- [ ] Received email in inbox

Once you complete these steps, emails will work perfectly! 🎉
