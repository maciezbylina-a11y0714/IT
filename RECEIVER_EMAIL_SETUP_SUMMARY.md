# ✅ Receiver Email Configuration - Complete

## 📧 Receiver Email Configuration
**Your Gmail Address (maciezbylina Gmail)**

This email will receive:
- ✅ Contact form submissions
- ✅ Career/Job application submissions (with resume attachments)

**Note:** Set `RECEIVER_EMAIL` environment variable in Railway to your Gmail address.

---

## 🔄 What Was Updated

### Files Modified:
1. ✅ `contactme.php` - Default receiver email set
2. ✅ `careers.php` - Default receiver email set

**Changes Made:**
- Both files now default to `allysondavid99@outlook.com` if no environment variable is set
- Still supports Railway environment variables for flexibility

---

## 🚂 Railway Configuration (Required)

### Quick Setup:

1. **Go to Railway Dashboard**
   - Select your project/service
   - Click **"Variables"** tab

2. **Add These Variables:**

```
RECEIVER_EMAIL=allysondavid99@outlook.com
MAIL_HOST=smtp-mail.outlook.com
MAIL_PORT=587
MAIL_USERNAME=your-email@outlook.com
MAIL_PASSWORD=your-outlook-app-password
MAIL_FROM_NAME=Website Form
```

3. **Get Outlook App Password:**
   - Visit: https://account.microsoft.com/security/app-passwords
   - Enable 2FA first (if not enabled)
   - Generate app password
   - Use it as `MAIL_PASSWORD`

4. **Redeploy:**
   - Railway will auto-redeploy after adding variables
   - Or click "Redeploy" manually

---

## 📋 Detailed Guides Created

I've created comprehensive guides for you:

1. **`EMAIL_CONFIGURATION.md`** - Complete email setup guide
2. **`RAILWAY_VARIABLES_QUICK_SETUP.md`** - Quick Railway setup
3. **`RAILWAY_DEPLOYMENT.md`** - Updated with receiver email

---

## ✅ Current Status

- ✅ Code updated with receiver email
- ✅ Default email: `allysondavid99@outlook.com`
- ✅ Environment variable support maintained
- ✅ Ready for Railway deployment

**Next Step:** Add environment variables in Railway and redeploy!

---

## 🧪 Testing

After Railway deployment:

1. **Test Contact Form:**
   ```
   - Fill out form on your website
   - Submit
   - Check: allysondavid99@outlook.com
   ```

2. **Test Career Form:**
   ```
   - Fill out application
   - Upload resume
   - Submit
   - Check: allysondavid99@outlook.com (with attachment)
   ```

---

## 📝 Summary

**Receiver Email:** Your Gmail address (maciezbylina Gmail) ✅

**Railway Setup:**
1. Add 6 environment variables (see above)
2. Set `RECEIVER_EMAIL` to your Gmail address (maciezbylina Gmail)
3. Set `MAIL_USERNAME` to the same Gmail address
4. Use app password: `xpggceyzrruvgfgu`
5. Redeploy

**That's it!** Your forms will send emails to your Gmail address 🎉
