@echo off
setlocal enabledelayedexpansion

echo Checking if Chocolatey is installed...

:: Check if chocolatey is installed by trying to run choco command
choco --version >nul 2>&1
if %errorlevel% equ 0 (
    echo Chocolatey is already installed.
    choco --version
    goto :end
)

echo Chocolatey not found. Installing Chocolatey...

:: Check if running as administrator
net session >nul 2>&1
if %errorlevel% neq 0 (
    echo ERROR: This script must be run as Administrator to install Chocolatey.
    echo Please right-click and select "Run as administrator"
    pause
    exit /b 1
)

:: Install Chocolatey using PowerShell
echo Installing Chocolatey via PowerShell...
powershell -NoProfile -InputFormat None -ExecutionPolicy Bypass -Command ^
"[System.Net.ServicePointManager]::SecurityProtocol = 3072; ^
iex ((New-Object System.Net.WebClient).DownloadString('https://community.chocolatey.org/install.ps1'))"

if %errorlevel% equ 0 (
    echo Chocolatey installation completed successfully!
    
    :: Refresh environment variables
    call refreshenv
    
    :: Verify installation
    choco --version
    if %errorlevel% equ 0 (
        echo Chocolatey is now ready to use.
    ) else (
        echo Warning: Chocolatey may need a new command prompt to work properly.
        echo Please close this window and open a new command prompt as administrator.
    )
) else (
    echo ERROR: Chocolatey installation failed.
    echo Please check your internet connection and try again.
    exit /b 1
)

:end
echo Script completed.
pause