#!/usr/bin/env php
<?php
// Cross-platform PHP setup script
//fonctions

function testRedisConnection($host = '127.0.0.1', $port = 6379) {
    try {
        if (!extension_loaded('redis')) {
            throw new Exception("Redis PHP extension not loaded");
        }
        
        $redis = new Redis();
        $connected = $redis->connect($host, $port, 5); // 5 second timeout
        
        if (!$connected) {
            throw new Exception("Could not connect to Redis at {$host}:{$port}");
        }
        
        $pong = $redis->ping();
        if ($pong !== true && $pong !== 'PONG') {
            throw new Exception("Redis ping failed");
        }
        
        $redis->close();
        return true;
        
    } catch (Exception $e) {
        echo "Redis connection failed: " . $e->getMessage() . "\n";
        return false;
    }
}

function testRedisOperations() {
    try {
        $redis = new Redis();
        $redis->connect('127.0.0.1', 6379);
        
        // Test basic set/get
        $redis->set('test_key', 'test_value', 10); // 10 second expiry
        $value = $redis->get('test_key');
        
        if ($value !== 'test_value') {
            throw new Exception("Redis set/get test failed");
        }
        
        // Test different databases (we use 0, 1, 2 for cache, sessions, messenger)
        $redis->select(1);
        $redis->set('session_test', 'session_value');
        
        $redis->select(2);  
        $redis->set('messenger_test', 'messenger_value');
        
        // Cleanup
        $redis->select(0);
        $redis->del('test_key');
        $redis->select(1);
        $redis->del('session_test');
        $redis->select(2);
        $redis->del('messenger_test');
        
        $redis->close();
        return true;
        
    } catch (Exception $e) {
        echo "Redis operations test failed: " . $e->getMessage() . "\n";
        return false;
    }
}

//fin fonctions
//script begin

echo "WoW Manager - Universal Setup\n";
echo "=============================\n\n";

function copyEnvironmentFile($source, $target) {
    if (file_exists($source)) {
        copy($source, $target);
        echo "Environment configured for your platform.\n";
    }
}

// Detect platform
$platform = PHP_OS_FAMILY;
echo "Detected platform: $platform\n\n";

switch ($platform) {
    case 'Windows':
        echo "Running Windows setup...\n";
        passthru($platform . '/setup.bat');
        copyEnvironmentFile('.env.windows', '.env.local');
        break;
        
    case 'Linux':
        echo "Running Linux setup...\n";
        passthru('chmod +x ' . $platform .'/setup-linux.sh && .'. $platform .'/setup-linux.sh');
        copyEnvironmentFile('.env.linux', '.env.local');
        break;
        
    case 'Darwin':
        echo "MacOs unsupported yet... installation failed\n";
        exit(1);
        
    default:
        echo "Unsupported platform: $platform\n";
        exit(1);
}

//todo manage errors in scripts... it should all have went fine for next steps to move on

// After platform-specific setup, verify Redis connection
echo "Final checks on Redis...\n";

// Test Redis connection based on platform
$redisPort = (PHP_OS_FAMILY === 'Windows') ? 6380 : 6379; // Memurai uses 6380

if (testRedisConnection('127.0.0.1', $redisPort)) {
    echo "✓ Redis enabled successfully \n";
} else {
    echo "✗ Redis connection failed\n";
    echo "Please check that Redis is running and accessible\n";
    exit(1);
}

//todo call powershell symfony CLI install if needed

$this->testRedisOperations();

echo "\nSetup complete! Run: php bin/console server:start\n";
?>