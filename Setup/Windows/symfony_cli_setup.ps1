# Symfony CLI Installation Script for PowerShell
# Supports Windows

Write-Host "Checking for Symfony CLI..." -ForegroundColor Yellow

# Check if symfony command exists
try {
    $symfonyVersion = symfony version 2>$null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Symfony CLI is already installed." -ForegroundColor Green
        Write-Host $symfonyVersion
        exit 0
    }
} catch {
    # Command not found, continue with installation
}

Write-Host "Symfony CLI not found. Installing..." -ForegroundColor Yellow

# Check if Scoop is available (recommended method)
try {
    scoop --version 2>$null | Out-Null
    if ($LASTEXITCODE -eq 0) {
        Write-Host "Installing via Scoop..." -ForegroundColor Cyan
        scoop install symfony-cli
        
        # Verify installation
        Write-Host "Verifying installation..." -ForegroundColor Yellow
        try {
            $symfonyVersion = symfony version
            Write-Host "✅ Symfony CLI installed successfully via Scoop!" -ForegroundColor Green
            Write-Host $symfonyVersion
            exit 0
        } catch {
            Write-Host "❌ Scoop installation failed, will install Scoop and try again..." -ForegroundColor Red
        }
    }
} catch {
    Write-Host "Scoop not found, installing Scoop first..." -ForegroundColor Yellow
    
    # Install Scoop
    try {
        Write-Host "Installing Scoop package manager..." -ForegroundColor Cyan
        
        # Check if execution policy allows installation
        $executionPolicy = Get-ExecutionPolicy
        if ($executionPolicy -eq "Restricted") {
            Write-Host "Setting execution policy to RemoteSigned for current user..." -ForegroundColor Yellow
            Set-ExecutionPolicy -ExecutionPolicy RemoteSigned -Scope CurrentUser -Force
        }
        
        # Install Scoop
        Invoke-RestMethod -Uri https://get.scoop.sh | Invoke-Expression
        
        # Verify Scoop installation
        scoop --version 2>$null | Out-Null
        if ($LASTEXITCODE -eq 0) {
            Write-Host "✅ Scoop installed successfully!" -ForegroundColor Green
            
            # Now try to install Symfony CLI via Scoop
            Write-Host "Installing Symfony CLI via Scoop..." -ForegroundColor Cyan
            scoop install symfony-cli
            
            # Verify Symfony CLI installation
            Write-Host "Verifying Symfony CLI installation..." -ForegroundColor Yellow
            try {
                $symfonyVersion = symfony version
                Write-Host "✅ Symfony CLI installed successfully via Scoop!" -ForegroundColor Green
                Write-Host $symfonyVersion
                exit 0
            } catch {
                Write-Host "❌ Symfony CLI installation via Scoop failed, trying alternative method..." -ForegroundColor Red
            }
        } else {
            Write-Host "❌ Scoop installation failed, shut installation..." -ForegroundColor Red
            exit 0;
        }
    } catch {
        Write-Host "❌ Failed to install Scoop: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "Trying alternative installation method..." -ForegroundColor Yellow
    }
}

$input = Read-Host "Do you want to try downloading Symfony CLI from GitHub (experimental)" -MaskInput

if($input -eq 'y' -or $input -eq 'yes')
{
    # Create temp directory
    $tempDir = Join-Path $env:TEMP "symfony-cli"
    if (Test-Path $tempDir) {
        Remove-Item $tempDir -Recurse -Force
    }
    New-Item -ItemType Directory -Path $tempDir -Force | Out-Null

    try {
        # Get latest release info
        $releases = Invoke-RestMethod -Uri "https://api.github.com/repos/symfony-cli/symfony-cli/releases/latest"
        $windowsAsset = $releases.assets | Where-Object { $_.name -like "*windows_amd64*" -and $_.name -like "*.zip" }
        
        if (-not $windowsAsset) {
            throw "Could not find Windows release asset"
        }
        
        $downloadUrl = $windowsAsset.browser_download_url
        $zipPath = Join-Path $tempDir "symfony-cli.zip"
        
        Write-Host "Downloading from: $downloadUrl" -ForegroundColor Cyan
        Invoke-WebRequest -Uri $downloadUrl -OutFile $zipPath
        
        # Extract ZIP
        Write-Host "Extracting..." -ForegroundColor Cyan
        Expand-Archive -Path $zipPath -DestinationPath $tempDir -Force
        
        # Find the symfony.exe file
        $symfonyExe = Get-ChildItem -Path $tempDir -Name "symfony.exe" -Recurse | Select-Object -First 1
        if (-not $symfonyExe) {
            throw "Could not find symfony.exe in downloaded archive"
        }
        
        # Create installation directory
        $installDir = Join-Path $env:LOCALAPPDATA "symfony-cli"
        if (-not (Test-Path $installDir)) {
            New-Item -ItemType Directory -Path $installDir -Force | Out-Null
        }
        
        # Copy executable
        $symfonyExePath = Join-Path $tempDir $symfonyExe
        $targetPath = Join-Path $installDir "symfony.exe"
        Copy-Item $symfonyExePath $targetPath -Force
        
        # Add to PATH if not already there
        $currentPath = [Environment]::GetEnvironmentVariable("PATH", "User")
        if ($currentPath -notlike "*$installDir*") {
            Write-Host "Adding to PATH..." -ForegroundColor Cyan
            $newPath = "$currentPath;$installDir"
            [Environment]::SetEnvironmentVariable("PATH", $newPath, "User")
            
            # Update current session PATH
            $env:PATH = "$env:PATH;$installDir"
            
            Write-Host "Added $installDir to user PATH. You may need to restart your terminal." -ForegroundColor Yellow
        }
        
        # Clean up temp files
        Remove-Item $tempDir -Recurse -Force
        
        # Verify installation
        Write-Host "Verifying installation..." -ForegroundColor Yellow
        try {
            $symfonyVersion = & "$targetPath" version
            Write-Host "✅ Symfony CLI installed successfully!" -ForegroundColor Green
            Write-Host $symfonyVersion
        } catch {
            Write-Host "❌ Installation verification failed: $($_.Exception.Message)" -ForegroundColor Red
            exit 1
        }
        
    } catch {
        Write-Host "❌ Installation failed: $($_.Exception.Message)" -ForegroundColor Red
        Write-Host "Please try installing manually:" -ForegroundColor Yellow
        Write-Host "1. Install Scoop: https://scoop.sh/" -ForegroundColor Yellow
        Write-Host "2. Run: scoop install symfony-cli" -ForegroundColor Yellow
        Write-Host "Or visit: https://symfony.com/download" -ForegroundColor Yellow
        exit 1
    }
}
else 
{
    Write-Host "❌ Installation failed: $($_.Exception.Message)" -ForegroundColor Red
    Write-Host "Please try installing manually:" -ForegroundColor Yellow
    Write-Host "1. Install Scoop: https://scoop.sh/" -ForegroundColor Yellow
    Write-Host "2. Run: scoop install symfony-cli" -ForegroundColor Yellow
    Write-Host "Or visit: https://symfony.com/download" -ForegroundColor Yellow
    exit 1
}
