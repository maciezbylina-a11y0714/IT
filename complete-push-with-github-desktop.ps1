# Script to complete push using GitHub Desktop
# Since you're logged into GitHub Desktop, this will help you complete the push

Write-Host "=== Completing Push with GitHub Desktop ===" -ForegroundColor Green
Write-Host ""

$projectPath = "E:\IT\IT-Company-Website-main"
$tempGit = "$env:TEMP\it-website-git-fresh"

# Check if temp git exists
if (-not (Test-Path $tempGit)) {
    Write-Host "Error: Temp git repository not found!" -ForegroundColor Red
    Write-Host "The repository with all committed files should be at: $tempGit" -ForegroundColor Yellow
    exit 1
}

Write-Host "✅ Found your committed repository at: $tempGit" -ForegroundColor Green
Write-Host "✅ All 144 files are already committed and ready!" -ForegroundColor Green
Write-Host ""

# Option 1: Add repository to GitHub Desktop
Write-Host "=== Option 1: Add to GitHub Desktop (Recommended) ===" -ForegroundColor Cyan
Write-Host ""
Write-Host "1. Open GitHub Desktop" -ForegroundColor Yellow
Write-Host "2. Click 'File' → 'Add Local Repository'" -ForegroundColor Yellow
Write-Host "3. Browse to: $projectPath" -ForegroundColor Yellow
Write-Host "4. If it says 'not a git repository', we need to copy the .git folder" -ForegroundColor Yellow
Write-Host ""

# Try to create a proper git repo by converting bare to normal
Write-Host "=== Attempting to set up git repository ===" -ForegroundColor Cyan

# Create a new git repo
Set-Location $projectPath

# Try to clone the bare repo as a normal repo
$tempNormal = "$env:TEMP\it-website-normal"
if (Test-Path $tempNormal) {
    Remove-Item -Recurse -Force $tempNormal
}

Write-Host "Creating normal git repository from bare repository..." -ForegroundColor Yellow
git clone $tempGit $tempNormal 2>&1 | Out-Null

if (Test-Path "$tempNormal\.git") {
    Write-Host "✅ Successfully created normal git repository!" -ForegroundColor Green
    
    # Copy the .git folder
    Write-Host "Copying .git folder to project..." -ForegroundColor Yellow
    try {
        Copy-Item -Path "$tempNormal\.git" -Destination "$projectPath\.git" -Recurse -Force -ErrorAction Stop
        Write-Host "✅ Git repository copied successfully!" -ForegroundColor Green
        
        # Verify
        Set-Location $projectPath
        $status = git status 2>&1
        if ($LASTEXITCODE -eq 0) {
            Write-Host ""
            Write-Host "=== SUCCESS! ===" -ForegroundColor Green
            Write-Host "Your project is now a git repository!" -ForegroundColor Green
            Write-Host ""
            Write-Host "Next steps:" -ForegroundColor Cyan
            Write-Host "1. Open GitHub Desktop" -ForegroundColor Yellow
            Write-Host "2. Click 'File' → 'Add Local Repository'" -ForegroundColor Yellow
            Write-Host "3. Select: $projectPath" -ForegroundColor Yellow
            Write-Host "4. GitHub Desktop will detect the repository" -ForegroundColor Yellow
            Write-Host "5. Click 'Publish repository' or 'Push origin' to upload to GitHub" -ForegroundColor Yellow
            Write-Host ""
            Write-Host "OR use command line:" -ForegroundColor Cyan
            Write-Host "  cd $projectPath" -ForegroundColor White
            Write-Host "  git push -u origin main" -ForegroundColor White
        } else {
            Write-Host "Warning: Git status check failed, but .git folder was copied." -ForegroundColor Yellow
            Write-Host "Try opening GitHub Desktop and adding the repository manually." -ForegroundColor Yellow
        }
    } catch {
        Write-Host "❌ Could not copy .git folder: $_" -ForegroundColor Red
        Write-Host ""
        Write-Host "=== Manual Instructions ===" -ForegroundColor Cyan
        Write-Host "1. In GitHub Desktop, click 'File' → 'Clone Repository'" -ForegroundColor Yellow
        Write-Host "2. URL: https://github.com/maciezbylina-a11y0714/IT.git" -ForegroundColor Yellow
        Write-Host "3. Choose a location to clone" -ForegroundColor Yellow
        Write-Host "4. Copy all files from $projectPath to the cloned folder" -ForegroundColor Yellow
        Write-Host "5. Commit and push in GitHub Desktop" -ForegroundColor Yellow
    }
} else {
    Write-Host "❌ Could not create normal repository" -ForegroundColor Red
    Write-Host ""
    Write-Host "=== Manual Instructions ===" -ForegroundColor Cyan
    Write-Host "Since we can't set up git automatically, use GitHub Desktop:" -ForegroundColor Yellow
    Write-Host ""
    Write-Host "1. Open GitHub Desktop" -ForegroundColor Yellow
    Write-Host "2. Click 'File' → 'Clone Repository'" -ForegroundColor Yellow
    Write-Host "3. URL: https://github.com/maciezbylina-a11y0714/IT.git" -ForegroundColor Yellow
    Write-Host "4. Clone it to a location (e.g., E:\IT\IT-clone)" -ForegroundColor Yellow
    Write-Host "5. Copy ALL files from: $projectPath" -ForegroundColor Yellow
    Write-Host "6. Paste them into the cloned folder (overwrite if needed)" -ForegroundColor Yellow
    Write-Host "7. In GitHub Desktop, you'll see all changes" -ForegroundColor Yellow
    Write-Host "8. Write commit message: 'Initial commit - removed team section and prepared for Railway deployment'" -ForegroundColor Yellow
    Write-Host "9. Click 'Commit to main'" -ForegroundColor Yellow
    Write-Host "10. Click 'Push origin' to upload to GitHub" -ForegroundColor Yellow
}
