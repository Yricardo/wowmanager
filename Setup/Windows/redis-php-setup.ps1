Write-Host "Install Redis and PHP Redis extension"
choco install redis-64 -y

# Install/Enable PHP Redis extension
Write-Host "Configuring PHP Redis extension..."

# Get php.ini path
$phpIniPath = (php --ini 2>$null | Select-String "Loaded Configuration File").ToString().Split(':')[1].Trim()

if (Test-Path $phpIniPath) {
    # Read current php.ini content
    $content = Get-Content $phpIniPath -Raw
    
    # Enable redis extension
    $content = $content -replace ';extension=redis', 'extension=redis'
    
    # Add extension line if it doesn't exist
    if ($content -notmatch 'extension=redis') {
        $content += "`nextension=redis"
    }
    
    # Write back to php.ini
    Set-Content $phpIniPath $content
    
    Write-Host "Redis extension enabled in php.ini"
} else {
    Write-Warning "Could not find php.ini file at: $phpIniPath"
}

Write-Host "Redis installation completed!"