<?php

use Symfony\Component\HttpKernel\HttpKernelInterface;

class TestCase extends Illuminate\Foundation\Testing\TestCase
{
    /**
     * Creates the application.
     */
    public function createApplication(): HttpKernelInterface
    {
        $unitTesting = true;

        $testEnvironment = 'testing';

        return require __DIR__.'/../../bootstrap/start.php';
    }
}
