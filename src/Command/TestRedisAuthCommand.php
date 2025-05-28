<?php 

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TestRedisAuthCommand extends Command
{
    protected static $defaultName = 'app:test:redis-auth';

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        try {
            // Test connection without auth (should fail in production)
            $redis = new \Redis();
            $redis->connect('127.0.0.1', 6379);
            
            try {
                $redis->ping();
                $output->writeln('<comment>WARNING: Redis allows unauthenticated connections!</comment>');
            } catch (\Exception $e) {
                $output->writeln('<info>✓ Redis requires authentication (good!)</info>');
            }
            
            // Test connection with auth
            $password = $_ENV['REDIS_PASSWORD'] ?? '';
            if ($password) {
                $redis->auth($password);
                $pong = $redis->ping();
                $output->writeln('<info>✓ Authenticated Redis connection successful</info>');
            }
            
        } catch (\Exception $e) {
            $output->writeln('<error>Redis connection failed: ' . $e->getMessage() . '</error>');
            return Command::FAILURE;
        }
        
        return Command::SUCCESS;
    }
}