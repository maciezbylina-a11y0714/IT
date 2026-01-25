# Fix GitHub Desktop Authentication Error

## Current Issue
GitHub Desktop is showing "Authentication failed" when trying to push.

## Solutions to Try

### Solution 1: Refresh Your GitHub Desktop Token

1. **Open GitHub Desktop**
2. **Click:** `File` → `Options` (or `Preferences` on Mac)
3. **Go to:** `Accounts` tab
4. **Click:** `Sign Out` (if you see your account)
5. **Click:** `Sign In` again
6. **Choose:** "Sign in with your browser" or "Sign in with token"
7. **Complete the authentication**
8. **Try pushing again**

### Solution 2: Use Personal Access Token

If you need to use a token:

1. **Go to GitHub:** https://github.com/settings/tokens
2. **Click:** "Generate new token" → "Generate new token (classic)"
3. **Name it:** "GitHub Desktop"
4. **Select scopes:**
   - ✅ `repo` (Full control of private repositories)
   - ✅ `workflow` (if you use GitHub Actions)
5. **Click:** "Generate token"
6. **Copy the token** (you won't see it again!)
7. **In GitHub Desktop:**
   - `File` → `Options` → `Accounts`
   - Sign out and sign in again
   - When prompted, use the token as your password

### Solution 3: Verify Repository Permissions

1. **Go to:** https://github.com/maciezbylina-a11y0714/IT
2. **Check if:**
   - The repository exists
   - You have write access
   - The repository is not archived
3. **If you don't have access:**
   - Make sure you're logged into the correct GitHub account
   - Check if `maciezbylina-a11y0714` is your account

### Solution 4: Remove and Re-add Repository

1. **In GitHub Desktop:**
   - `File` → `Remove Repository`
   - Remove the current repository
2. **Add it again:**
   - `File` → `Clone Repository`
   - URL: `https://github.com/maciezbylina-a11y0714/IT.git`
   - Choose location
   - Clone
3. **Copy your files** from `E:\IT\IT-Company-Website-main` to the cloned folder
4. **Commit and push**

### Solution 5: Use Command Line with Token

If GitHub Desktop continues to have issues, you can push via command line:

```powershell
cd C:\Users\aaa\AppData\Local\Temp\it-clone

# Set the remote with token
git remote set-url origin https://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@github.com/maciezbylina-a11y0714/IT.git

# Try pushing
git push -u origin main
```

**Note:** The token in the URL above is the one you provided earlier. If it's expired, generate a new one.

### Solution 6: Check Repository Settings

1. **Go to:** https://github.com/maciezbylina-a11y0714/IT/settings
2. **Check:**
   - Repository is not archived
   - Branch protection rules allow pushes
   - You have admin/write permissions

## Quick Fix (Most Common)

**Most likely solution:** Sign out and sign back into GitHub Desktop:

1. `File` → `Options` → `Accounts`
2. Click `Sign Out`
3. Click `Sign In` → Choose "Sign in with your browser"
4. Complete authentication in browser
5. Try pushing again

## After Fixing Authentication

Once authentication works:
- Your repository is ready at: `C:\Users\aaa\AppData\Local\Temp\it-clone`
- All 144 files are committed
- Just click "Push origin" in GitHub Desktop

Good luck! 🚀
