@echo off
echo Setting up Windows development environment...

@echo off
setlocal enabledelayedexpansion

echo Checking if Chocolatey is installed...

REM TODO CHECK IF PACKAGE MANAGER SUPPORTING REDIS AND 
./chocolatey-setup.ps1

REM Enabling redis on php todo fix weakness what if the line is not there
./redis-php-setup.ps1
Write-Host "Install Redis and PHP Redis extension"; choco install redis-64 -y; $ini = (php --ini | Select-String "Loaded Configuration File").ToString().Split(':')[1].Trim(); (Get-Content $ini -Raw) -replace ';extension=redis', 'extension=redis' | Set-Content $ini

REM Check if Composer is installed
where composer >nul 2>nul
if %ERRORLEVEL% == 0 (
    echo ✓ Composer is already installed
    composer --version
) else (
    echo Installing Composer via Chocolatey...
    choco install composer -y
    
    REM Refresh environment variables
    refreshenv
    
    REM Verify installation
    where composer >nul 2>nul
    if %ERRORLEVEL% == 0 (
        echo ✓ Composer installed successfully
        composer --version
    ) else (
        echo ✗ Composer installation failed
        exit /b 1
    )
)

echo install symfony cli if needed
.\symfony_cli_setup.ps1

REM Install Memurai (Redis for Windows)
choco install memurai -y

REM Start Memurai service
net start Memurai

REM Check if Memurai service is running
echo Checking Memurai service status...
sc query Memurai | find "RUNNING" >nul
if %ERRORLEVEL% == 0 (
    echo ✓ Memurai service is running
) else (
    echo ✗ Memurai service is not running
    echo Checking service status:
    sc query Memurai
    exit /b 1
)

REM Test Redis connection (Memurai uses port 6380 by default)
echo Pinging Redis...
redis-cli -p 6380 ping >nul 2>&1
if %ERRORLEVEL% == 0 (
    echo ✓ Redis connection successful
) else (
    echo ✗ Redis connection failed
    echo Is Memurai running on port 6380?
    exit /b 1
)