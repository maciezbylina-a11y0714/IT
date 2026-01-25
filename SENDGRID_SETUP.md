# 🚀 SendGrid Setup Guide (Recommended for Railway)

## ✅ Why SendGrid?

Railway blocks outbound SMTP connections (ports 587 and 465), causing connection timeouts. **SendGrid uses HTTP API** which works perfectly on Railway!

**Free Tier:** 100 emails/day forever

---

## 📋 Step-by-Step Setup

### Step 1: Create SendGrid Account

1. Go to: https://signup.sendgrid.com/
2. Sign up with your email (use your Gmail: `maciezbylina@gmail.com`)
3. Verify your email address
4. Complete the account setup

### Step 2: Create API Key

1. Log in to SendGrid Dashboard
2. Go to **Settings** → **API Keys** (left sidebar)
3. Click **"Create API Key"**
4. Name it: `Railway Website`
5. Select **"Full Access"** (or "Restricted Access" with Mail Send permissions)
6. Click **"Create & View"**
7. **COPY THE API KEY** - you'll only see it once!
   - It looks like: `SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx`

### Step 3: Verify Sender Email (Important!)

1. Go to **Settings** → **Sender Authentication**
2. Click **"Verify a Single Sender"**
3. Fill in the form:
   - **From Email:** `maciezbylina@gmail.com`
   - **From Name:** `Allyson Tech Solutions`
   - **Reply To:** `maciezbylina@gmail.com`
   - **Company Address:** Your address
4. Click **"Create"**
5. **Check your Gmail inbox** for verification email
6. Click the verification link in the email

### Step 4: Add API Key to Railway

1. Go to Railway Dashboard → Your Service → **Variables**
2. Click **"+ New Variable"**
3. Add:
   - **Name:** `SENDGRID_API_KEY`
   - **Value:** `SG.xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` (your API key)
4. Click **"Add"**
5. Railway will automatically redeploy

### Step 5: Keep Existing Variables

Keep these variables (they're still used):
- ✅ `RECEIVER_EMAIL=maciezbylina@gmail.com`
- ✅ `MAIL_USERNAME=maciezbylina@gmail.com` (used as sender email)
- ✅ `MAIL_FROM_NAME=Website Form`

You can **remove** these (not needed with SendGrid):
- ❌ `MAIL_HOST` (not needed)
- ❌ `MAIL_PORT` (not needed)
- ❌ `MAIL_PASSWORD` (not needed)

---

## 🧪 Test

1. Submit the contact form on your website
2. Check your Gmail inbox (`maciezbylina@gmail.com`)
3. You should receive the email!

---

## 📊 SendGrid Dashboard

- **Monitor emails:** Dashboard → Activity
- **View statistics:** Dashboard → Stats
- **Check delivery:** Dashboard → Activity Feed

---

## 🔧 Troubleshooting

### "Email not received"
1. Check Railway logs for SendGrid errors
2. Verify sender email is verified in SendGrid
3. Check SendGrid Activity Feed for delivery status
4. Check spam folder

### "API Key Invalid"
1. Make sure you copied the full API key (starts with `SG.`)
2. Check for extra spaces in Railway variable
3. Regenerate API key if needed

### "Sender not verified"
1. Go to SendGrid → Settings → Sender Authentication
2. Make sure `maciezbylina@gmail.com` shows as "Verified"
3. Resend verification email if needed

---

## 💡 Benefits of SendGrid

- ✅ Works on Railway (no SMTP blocking issues)
- ✅ Better deliverability
- ✅ Email analytics and tracking
- ✅ Free tier: 100 emails/day
- ✅ Easy to scale later

---

## 🎯 Quick Checklist

- [ ] Created SendGrid account
- [ ] Created API Key
- [ ] Verified sender email (`maciezbylina@gmail.com`)
- [ ] Added `SENDGRID_API_KEY` to Railway Variables
- [ ] Tested contact form
- [ ] Received email in inbox

Once you complete these steps, emails will work perfectly! 🎉
