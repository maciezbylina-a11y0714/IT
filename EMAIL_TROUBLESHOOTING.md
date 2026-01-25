# 🔧 Email Troubleshooting Guide

## Issue: Emails Not Being Received

If contact form submissions are not reaching the receiver email, follow these steps:

---

## ✅ Step 1: Check Railway Environment Variables

Go to **Railway Dashboard** → **Your Project** → **Variables Tab** and verify these are set:

```
RECEIVER_EMAIL=your-gmail@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
```

**Important:**
- `RECEIVER_EMAIL` must be set to your Gmail address
- `MAIL_USERNAME` must be the same Gmail address
- `MAIL_PASSWORD` must be the Gmail app password (no spaces)
- All variables must be set correctly

---

## ✅ Step 2: Check Railway Logs

After submitting the form, check Railway logs for error messages:

1. Go to **Railway Dashboard** → **Your Service** → **Logs**
2. Look for error messages like:
   - "RECEIVER_EMAIL environment variable is not set!"
   - "MAIL_USERNAME or MAIL_PASSWORD environment variables are not set!"
   - "PHPMailer Error: ..."
   - "Failed to send email to: ..."

**Common Errors:**

### Error: "RECEIVER_EMAIL environment variable is not set!"
**Solution:** Add `RECEIVER_EMAIL` variable in Railway with your Gmail address

### Error: "MAIL_USERNAME or MAIL_PASSWORD environment variables are not set!"
**Solution:** Add both `MAIL_USERNAME` and `MAIL_PASSWORD` in Railway

### Error: "PHPMailer Error: SMTP connect() failed"
**Possible causes:**
- Wrong `MAIL_HOST` (should be `smtp.gmail.com` for Gmail)
- Wrong `MAIL_PORT` (should be `587` for Gmail)
- Wrong `MAIL_PASSWORD` (app password incorrect)
- Gmail account security settings blocking access

### Error: "PHPMailer Error: Authentication failed"
**Solution:**
- Verify `MAIL_USERNAME` is your full Gmail address
- Verify `MAIL_PASSWORD` is the correct app password (16 characters, no spaces)
- Make sure 2-Factor Authentication is enabled on Gmail
- Generate a new app password if needed

---

## ✅ Step 3: Verify Gmail App Password

1. Go to: https://myaccount.google.com/apppasswords
2. Make sure you have an app password generated
3. The app password should be 16 characters (no spaces)
4. Copy it exactly as shown and paste into Railway `MAIL_PASSWORD`

**Note:** Regular Gmail password won't work - you MUST use an App Password.

---

## ✅ Step 4: Test Email Configuration

After setting all variables in Railway:

1. **Redeploy** your service (Railway will auto-redeploy after adding variables)
2. **Submit a test form** on your website
3. **Check Railway logs** for any errors
4. **Check your Gmail inbox** (including spam folder)

---

## ✅ Step 5: Enable Detailed Logging (Temporary)

If emails still don't work, temporarily enable detailed SMTP debugging:

1. Edit `mailing/mailfunction.php`
2. Change line 14 from:
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
6. **Disable debug mode** after troubleshooting (set back to `SMTP::DEBUG_OFF`)

---

## 📋 Quick Checklist

- [ ] `RECEIVER_EMAIL` is set in Railway
- [ ] `MAIL_HOST` is set to `smtp.gmail.com`
- [ ] `MAIL_PORT` is set to `587`
- [ ] `MAIL_USERNAME` is set to your Gmail address
- [ ] `MAIL_PASSWORD` is set to Gmail app password (16 chars, no spaces)
- [ ] `MAIL_FROM_NAME` is set
- [ ] Gmail 2FA is enabled
- [ ] Gmail app password is generated
- [ ] Service has been redeployed after adding variables
- [ ] Checked Railway logs for errors
- [ ] Checked Gmail inbox and spam folder

---

## 🔍 Common Issues & Solutions

### Issue: Form submits but no email received
**Check:**
1. Railway logs for PHPMailer errors
2. Gmail spam folder
3. Environment variables are set correctly
4. Service was redeployed after adding variables

### Issue: "Error sending message!" appears
**Check:**
1. Railway logs for specific error message
2. `RECEIVER_EMAIL` is not empty
3. `MAIL_USERNAME` and `MAIL_PASSWORD` are set
4. Gmail app password is correct

### Issue: Regex pattern error in console
**Fixed:** The pattern has been updated to work properly. Clear browser cache and try again.

---

## 🎯 Most Common Problem

**Missing Environment Variables in Railway**

Make sure ALL 6 variables are set in Railway:
- `RECEIVER_EMAIL`
- `MAIL_HOST`
- `MAIL_PORT`
- `MAIL_USERNAME`
- `MAIL_PASSWORD`
- `MAIL_FROM_NAME`

After adding them, Railway will auto-redeploy. Wait for deployment to complete, then test again.

---

## 📧 Expected Behavior

When everything is configured correctly:
1. User submits contact form
2. Form data is sent to `contactme.php`
3. PHP reads environment variables from Railway
4. PHPMailer connects to Gmail SMTP
5. Email is sent from `MAIL_USERNAME` to `RECEIVER_EMAIL`
6. User sees "Thanks! We will contact you soon."
7. Email arrives in Gmail inbox

If any step fails, check Railway logs for the specific error.

---

## 🆘 Still Not Working?

1. **Check Railway logs** - Look for specific error messages
2. **Verify all environment variables** are set correctly
3. **Test with a simple email** - Make sure Gmail account works
4. **Check Gmail security** - Less secure apps might be blocked
5. **Try generating a new app password**

Good luck! 🚀
