# 🚀 Quick Setup: Resend API Key in Railway

## ✅ Step-by-Step Instructions

### Step 1: Go to Railway Dashboard

1. Open https://railway.app
2. Sign in to your account
3. Click on your project (the one with your website)

### Step 2: Open Variables Tab

1. In your project dashboard, click on the **"Variables"** tab (or **"Settings"** → **"Variables"**)
2. You'll see a list of existing environment variables

### Step 3: Add Resend API Key

1. Click the **"+ New Variable"** button (or **"Add Variable"**)
2. Fill in:
   - **Name:** `RESEND_API_KEY`
   - **Value:** `re_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` (your Resend API key - paste it here)
3. Click **"Add"** or **"Save"**

### Step 4: Verify Other Required Variables

Make sure these variables are also set:

- ✅ **`RECEIVER_EMAIL`** = `maciezbylina@gmail.com` (your email to receive form submissions)
- ✅ **`MAIL_USERNAME`** = `maciezbylina@gmail.com` (sender email address)
- ✅ **`MAIL_FROM_NAME`** = `Website Form` (or `Allyson Tech Solutions`)

### Step 5: Railway Auto-Redeploys

- Railway will **automatically detect** the new variable
- It will **automatically redeploy** your application
- Wait 1-2 minutes for the deployment to complete

### Step 6: Test

1. Go to your website
2. Submit the contact form
3. Check your email inbox (`maciezbylina@gmail.com`)
4. You should receive the email! ✅

---

## 📋 Complete Variable List

Here's what your Railway Variables should look like:

| Variable Name | Value | Required |
|--------------|-------|----------|
| `RESEND_API_KEY` | `re_xxxxxxxxxxxxxxxxxxxxxxxxxxxxxxxx` | ✅ Yes |
| `RECEIVER_EMAIL` | `maciezbylina@gmail.com` | ✅ Yes |
| `MAIL_USERNAME` | `maciezbylina@gmail.com` | ✅ Yes |
| `MAIL_FROM_NAME` | `Website Form` | ✅ Yes |

**Optional (can remove if using Resend):**
- ❌ `SENDGRID_API_KEY` (not needed if using Resend)
- ❌ `MAILGUN_API_KEY` (not needed if using Resend)
- ❌ `MAIL_HOST` (not needed with Resend)
- ❌ `MAIL_PORT` (not needed with Resend)
- ❌ `MAIL_PASSWORD` (not needed with Resend)

---

## 🎯 How It Works

The code automatically detects which email service to use based on your variables:

**Priority Order:**
1. **Resend** (if `RESEND_API_KEY` is set) ← **You're using this!**
2. Mailgun (if `MAILGUN_API_KEY` + `MAILGUN_DOMAIN` are set)
3. SendGrid (if `SENDGRID_API_KEY` is set)
4. SMTP (fallback, may not work on Railway)

Since you're setting `RESEND_API_KEY`, the code will **automatically use Resend**! 🎉

---

## 🔍 Verify It's Working

After adding the variable and Railway redeploys:

1. **Check Railway Logs:**
   - Go to Railway Dashboard → Your Service → **"Logs"** tab
   - Look for: `"Resend: Email sent successfully to: maciezbylina@gmail.com"`

2. **Test the Form:**
   - Submit the contact form on your website
   - Check your email inbox
   - You should receive the email within seconds!

---

## ❌ Troubleshooting

### "Email not received"
1. Check Railway logs for errors
2. Verify `RESEND_API_KEY` is correct (starts with `re_`)
3. Check spam folder
4. Verify `RECEIVER_EMAIL` is set correctly

### "Variable not found"
1. Make sure variable name is exactly: `RESEND_API_KEY` (case-sensitive)
2. Check for extra spaces
3. Make sure you clicked "Add" or "Save"

### "Still using SMTP"
1. Make sure `RESEND_API_KEY` is set
2. Check Railway logs - it should say "Resend:" not "SMTP:"
3. Remove other email service keys if you want to force Resend

---

## ✅ Quick Checklist

- [ ] Opened Railway Dashboard
- [ ] Went to Variables tab
- [ ] Added `RESEND_API_KEY` with your API key
- [ ] Verified `RECEIVER_EMAIL` is set
- [ ] Verified `MAIL_USERNAME` is set
- [ ] Verified `MAIL_FROM_NAME` is set
- [ ] Railway redeployed automatically
- [ ] Tested contact form
- [ ] Received email in inbox

**That's it! You're all set!** 🎉
