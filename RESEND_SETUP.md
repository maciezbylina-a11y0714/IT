# 🚀 Resend Setup Guide (Recommended Alternative)

## ✅ Why Resend?

- **Easiest setup** - Modern, developer-friendly API
- **Great free tier** - 3,000 emails/month
- **Works perfectly on Railway** - HTTP API (no SMTP blocking)
- **Fast delivery** - Excellent performance

---

## 📋 Step-by-Step Setup

### Step 1: Create Resend Account

1. Go to: https://resend.com/signup
2. Sign up with your email (use your Gmail: `maciezbylina@gmail.com`)
3. Verify your email address
4. Complete the account setup

### Step 2: Create API Key

1. Log in to Resend Dashboard
2. Go to **API Keys** (left sidebar)
3. Click **"Create API Key"**
4. Name it: `Railway Website`
5. Select **"Full Access"** (or "Sending Access" only)
6. Click **"Create"**
7. **COPY THE API KEY** - you'll only see it once!
   - It looks like: `re_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

### Step 3: Verify Domain (Optional but Recommended)

**Option A: Verify Single Email (Quick Start)**
1. Go to **Domains** → **Add Domain**
2. Or use **"Send Test Email"** to verify your sender email works

**Option B: Verify Domain (Production)**
1. Go to **Domains** → **Add Domain**
2. Add your domain (e.g., `allysontech.com`)
3. Add DNS records as instructed
4. Wait for verification (usually instant)

For quick testing, you can use Resend's default domain first.

### Step 4: Update Code to Use Resend

The code needs to be updated to support Resend. See `IMPLEMENT_RESEND.md` for code changes.

### Step 5: Add API Key to Railway

1. Go to Railway Dashboard → Your Service → **Variables**
2. Click **"+ New Variable"**
3. Add:
   - **Name:** `RESEND_API_KEY`
   - **Value:** `re_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` (your API key)
4. Click **"Add"**
5. Railway will automatically redeploy

### Step 6: Update Environment Variables

Keep these:
- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com` (sender email)
- ✅ `MAIL_FROM_NAME=Website Form`

Add:
- ✅ `RESEND_API_KEY=re_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

You can remove:
- ❌ `SENDGRID_API_KEY` (if switching from SendGrid)
- ❌ `MAIL_HOST`, `MAIL_PORT`, `MAIL_PASSWORD` (not needed)

---

## 🧪 Test

1. Submit the contact form on your website
2. Check your Gmail inbox (`maciezbylina@gmail.com`)
3. You should receive the email!

---

## 📊 Resend Dashboard

- **Monitor emails:** Dashboard → Logs
- **View statistics:** Dashboard → Analytics
- **Check delivery:** Dashboard → Logs

---

## 🔧 Troubleshooting

### "Email not received"
1. Check Railway logs for Resend errors
2. Check Resend Dashboard → Logs for delivery status
3. Check spam folder
4. Verify sender email is allowed

### "API Key Invalid"
1. Make sure you copied the full API key (starts with `re_`)
2. Check for extra spaces in Railway variable
3. Regenerate API key if needed

---

## 💡 Benefits of Resend

- ✅ Easiest setup (modern API)
- ✅ Great free tier (3,000/month)
- ✅ Fast delivery
- ✅ Excellent documentation
- ✅ Works perfectly on Railway

---

## 🎯 Quick Checklist

- [ ] Created Resend account
- [ ] Created API Key
- [ ] Updated code to use Resend (see `IMPLEMENT_RESEND.md`)
- [ ] Added `RESEND_API_KEY` to Railway Variables
- [ ] Tested contact form
- [ ] Received email in inbox

Once you complete these steps, emails will work perfectly! 🎉
