# Commit changes script
cd "E:\IT\IT-Company-Website-main"

# Check if git is initialized
if (-not (Test-Path .git\HEAD)) {
    Write-Host "Initializing git repository..."
    git init
}

# Add all changes
Write-Host "Adding all changes..."
git add -A

# Commit with message
Write-Host "Committing changes..."
git commit -m "Fix autoloader loading: Add diagnostics and automatic fallback to alternative email services"

Write-Host "Done! Changes committed successfully."
Write-Host "You can now push to GitHub using GitHub Desktop or: git push"
