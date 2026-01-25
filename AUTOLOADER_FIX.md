# Autoloader Issue Fix

## Problem
The Composer autoloader is being loaded, but classes like `GuzzleHttp\Client`, `Resend`, and `PHPMailer` are not being found at runtime on Railway, even though they were successfully installed during the build.

## Current Solution
The code has been updated to:

1. **Enhanced Diagnostics**: Added extensive logging to identify autoloader issues
2. **Automatic Fallback**: If Resend fails due to class loading issues, the system automatically falls back to:
   - Mailgun (if configured)
   - SendGrid (if configured)  
   - SMTP (as last resort)

3. **Manual Class Loading**: Added fallback logic to manually load GuzzleHttp classes if the autoloader fails

## What to Check

### 1. Railway Build Logs
Check if you see:
- `Generating autoload files` - This should appear during build
- `Generating optimized autoload files` - This should appear in the build step

### 2. Railway Runtime Logs
After deployment, check the logs when submitting the contact form. You should see:
- `Autoloader verified: PHPMailer found successfully` - If autoloader is working
- OR diagnostic messages about autoloader issues

### 3. Verify Email Service Configuration
Make sure you have at least ONE of these configured in Railway Variables:
- `RESEND_API_KEY` (preferred)
- `MAILGUN_API_KEY` + `MAILGUN_DOMAIN`
- `SENDGRID_API_KEY`
- `MAIL_USERNAME` + `MAIL_PASSWORD` (SMTP)

## If Autoloader Still Fails

The code will automatically try other email services. However, if you want to fix the autoloader issue permanently:

### Option 1: Regenerate Autoloader (Recommended)
Add this to your `railway.json` build command to ensure autoloader is regenerated:

```json
{
  "build": {
    "builder": "NIXPACKS",
    "buildCommand": "composer dump-autoload --optimize && composer update --no-interaction --no-scripts && composer install --no-dev --optimize-autoloader --no-scripts"
  }
}
```

### Option 2: Use Alternative Email Service
If Resend continues to have issues, configure Mailgun or SendGrid instead:
- Mailgun: Set `MAILGUN_API_KEY` and `MAILGUN_DOMAIN`
- SendGrid: Set `SENDGRID_API_KEY`

Both of these should work even if GuzzleHttp has autoloader issues, as they may use different HTTP clients.

## Next Steps

1. **Push the updated code** to GitHub
2. **Redeploy on Railway** - The new code will automatically fall back to other services
3. **Check Railway logs** after deployment to see which email service is being used
4. **Test the contact form** - It should work with one of the configured services

## Expected Behavior

- If Resend works: Email sent via Resend API
- If Resend fails but Mailgun is configured: Email sent via Mailgun
- If both fail but SendGrid is configured: Email sent via SendGrid  
- If all API services fail: Falls back to SMTP (may not work on Railway)

The system will log which service is being used, so you can verify in Railway logs.
