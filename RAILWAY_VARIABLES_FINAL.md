# 🚂 Railway Environment Variables - Final Configuration

## 📧 Email Setup

**Receiver Email:** Your Gmail address (maciezbylina Gmail - receives all form submissions)  
**Sender Email:** Same Gmail account (sends the emails using app password)

---

## ✅ Copy & Paste These Variables in Railway

Go to **Railway Dashboard** → **Your Project** → **Variables Tab** → **Add:**

```
RECEIVER_EMAIL=your-gmail@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
```

**Important:** Replace `your-gmail@gmail.com` with your actual Gmail address (maciezbylina Gmail)!

---

## 📝 Step-by-Step Setup

### 1. Add Variables in Railway

1. Open **Railway Dashboard**
2. Click on your **project/service**
3. Go to **"Variables"** tab
4. Click **"+ New Variable"** for each:

| Variable | Value | Notes |
|----------|-------|-------|
| `RECEIVER_EMAIL` | `your-gmail@gmail.com` | **Your Gmail address** (where emails are sent) |
| `MAIL_HOST` | `smtp.gmail.com` | Gmail SMTP server |
| `MAIL_PORT` | `587` | Gmail SMTP port |
| `MAIL_USERNAME` | `your-gmail@gmail.com` | **Same Gmail address** (sender) |
| `MAIL_PASSWORD` | `xpggceyzrruvgfgu` | Your Gmail app password (no spaces) |
| `MAIL_FROM_NAME` | `Website Form` | Display name |

### 2. Important Notes

- **Gmail Address:** Use your maciezbylina Gmail address for both `RECEIVER_EMAIL` and `MAIL_USERNAME`
- **App Password:** `xpggceyzrruvgfgu` (already provided, no spaces)
- **Same Email:** Both receiver and sender use the same Gmail account

### 3. Redeploy

After adding variables:
- Railway will **auto-redeploy**
- Or click **"Redeploy"** manually
- Wait for deployment

---

## 📋 Complete Variable List

```
RECEIVER_EMAIL=your-gmail@gmail.com
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-gmail@gmail.com
MAIL_PASSWORD=xpggceyzrruvgfgu
MAIL_FROM_NAME=Website Form
```

**Replace `your-gmail@gmail.com` with your actual Gmail address (maciezbylina Gmail)!**

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

## ✅ What Happens

1. **User fills contact/career form** on your website
2. **Email is sent FROM:** Your Gmail account (using app password)
3. **Email is sent TO:** Your Gmail account (same address)
4. **You receive** the form submission in your Gmail inbox

---

## 🧪 Testing

After setup:

1. **Fill out contact form** on your website
2. **Submit**
3. **Check:** Your Gmail inbox
4. **Email should arrive** from your Gmail address to your Gmail address

---

## ⚠️ Important

- **Gmail Address Required:** Set `RECEIVER_EMAIL` to your Gmail address (maciezbylina Gmail)
- **Same for Sender:** `MAIL_USERNAME` should be the same Gmail address
- **App Password:** `xpggceyzrruvgfgu` (no spaces, already correct)
- **No Outlook:** We're using Gmail for both sending and receiving

---

## 🎯 Quick Checklist

- [ ] Add all 6 variables in Railway
- [ ] Set `RECEIVER_EMAIL` to your Gmail address (maciezbylina Gmail)
- [ ] Set `MAIL_USERNAME` to the same Gmail address
- [ ] Set `MAIL_PASSWORD` to `xpggceyzrruvgfgu`
- [ ] Redeploy
- [ ] Test contact form
- [ ] Verify email in your Gmail inbox

**Done!** 🎉
