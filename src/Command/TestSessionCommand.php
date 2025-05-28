<?php

// src/Command/TestSessionCommand.php
namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class TestSessionCommand extends Command
{
    protected static $defaultName = 'app:test:session';
    
    public function __construct(private SessionInterface $session)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        // Test session write
        $this->session->start();
        $this->session->set('test_key', 'test_value_' . time());
        $this->session->save();
        
        $output->writeln('Session test value set: ' . $this->session->get('test_key'));
        
        // If using Redis, we can check if the session data is actually in Redis
        $sessionId = $this->session->getId();
        $output->writeln('Session ID: ' . $sessionId);
        
        return Command::SUCCESS;
    }
}