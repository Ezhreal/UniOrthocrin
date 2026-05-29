<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        $_SERVER['DB_CONNECTION'] = 'sqlite';
        $_SERVER['DB_DATABASE'] = ':memory:';
        $_SERVER['CACHE_STORE'] = 'array';
        $_SERVER['SESSION_DRIVER'] = 'array';
        $_SERVER['QUEUE_CONNECTION'] = 'sync';
        $_SERVER['MAIL_MAILER'] = 'array';

        $_ENV['DB_CONNECTION'] = 'sqlite';
        $_ENV['DB_DATABASE'] = ':memory:';
        $_ENV['CACHE_STORE'] = 'array';
        $_ENV['SESSION_DRIVER'] = 'array';
        $_ENV['QUEUE_CONNECTION'] = 'sync';
        $_ENV['MAIL_MAILER'] = 'array';

        putenv('DB_CONNECTION=sqlite');
        putenv('DB_DATABASE=:memory:');
        putenv('CACHE_STORE=array');
        putenv('SESSION_DRIVER=array');
        putenv('QUEUE_CONNECTION=sync');
        putenv('MAIL_MAILER=array');

        parent::setUp();
    }
}
