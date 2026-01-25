# 🚂 Railway Environment Variables - Quick Setup

## Copy & Paste These Variables

Go to **Railway Dashboard** → **Your Project** → **Variables Tab** → **Add the following:**

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

---

## 📝 Step-by-Step

### 1. Gmail App Password

Your Gmail App Password is: `xpggceyzrruvgfgu`

**Note:** 
- Make sure 2-Factor Authentication is enabled on your Gmail
- The app password is already provided (no spaces needed)
- You still need to provide your Gmail address for `MAIL_USERNAME`

### 2. Add Variables in Railway

1. Open Railway dashboard
2. Click on your **project/service**
3. Go to **"Variables"** tab
4. Click **"+ New Variable"** for each:

| Variable | Value |
|----------|-------|
| `RECEIVER_EMAIL` | `allysondavid99@outlook.com` |
| `MAIL_HOST` | `smtp-mail.outlook.com` |
| `MAIL_PORT` | `587` |
| `MAIL_USERNAME` | `your-gmail@gmail.com` (your Gmail address) |
| `MAIL_PASSWORD` | `xpggceyzrruvgfgu` (your Gmail app password) |
| `MAIL_FROM_NAME` | `Website Form` |

### 3. Redeploy

After adding variables:
- Railway will **auto-redeploy**, OR
- Click **"Redeploy"** button manually
- Wait for deployment to complete

---

## ✅ Verification

1. **Test Contact Form:**
   - Visit your deployed website
   - Fill out contact form
   - Submit
   - Check your Gmail inbox (the address you set as `RECEIVER_EMAIL`)

2. **Test Career Form:**
   - Fill out career application
   - Upload resume
   - Submit
   - Check your Gmail inbox (with attachment)

---

## 📧 Current Configuration

**Using Gmail for both sending and receiving:**
- **Sender:** Your Gmail account (maciezbylina Gmail)
- **Receiver:** Your Gmail account (same address - maciezbylina Gmail)
- **App Password:** `xpggceyzrruvgfgu` (already provided)

---

## 📧 What Gets Sent Where

- **Contact Form** → Your Gmail address (set as `RECEIVER_EMAIL`)
- **Career Applications** → Your Gmail address (set as `RECEIVER_EMAIL`, with resume attachment)

**Sender:** Uses `MAIL_USERNAME` email address (same Gmail)

---

## 🎯 Quick Checklist

- [ ] Outlook App Password generated
- [ ] All 6 variables added in Railway
- [ ] Variables saved (Railway auto-redeploys)
- [ ] Deployment completed
- [ ] Tested contact form
- [ ] Tested career form
- [ ] Emails received at `allysondavid99@outlook.com`

**Done!** 🎉
