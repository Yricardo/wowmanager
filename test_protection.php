<?php

// Test script to verify super admin protection
require_once 'vendor/autoload.php';

use Symfony\Component\Dotenv\Dotenv;

$dotenv = new Dotenv();
$dotenv->bootEnv(__DIR__.'/.env');

use App\Kernel;
use Symfony\Component\HttpFoundation\Request;

$kernel = new Kernel($_SERVER['APP_ENV'], (bool) $_SERVER['APP_DEBUG']);
$kernel->boot();

$container = $kernel->getContainer()->get('test.service_container') ?? $kernel->getContainer();

$entityManager = $container->get('doctrine.orm.entity_manager');
$passwordHasher = $container->get('security.user_password_hasher');

echo "🔒 Testing Super Admin Protection...\n";

try {
    // Try to create a second super admin directly
    $user = new \App\Entity\User();
    $hashedPassword = $passwordHasher->hashPassword($user, 'testpassword');
    
    $user->setUsername('hackersuperadmin')
        ->setPassword($hashedPassword)
        ->setRoles(['ROLE_USER', 'ROLE_SUPER_ADMIN', 'ROLE_ADMIN', 'ROLE_MEMBER'])
        ->setTrustScore(100)
        ->setCreatedAt(new \DateTimeImmutable());
    
    echo "📝 Attempting to persist second super admin...\n";
    
    $entityManager->persist($user);
    $entityManager->flush();
    
    echo "❌ SECURITY BREACH: Second super admin was created!\n";
    exit(1);
    
} catch (\Exception $e) {
    echo "✅ Protection working: " . $e->getMessage() . "\n";
    exit(0);
}
