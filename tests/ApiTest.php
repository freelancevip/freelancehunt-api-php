<?php
use Freelancevip\FreelancehuntApi\Api;

class ApiTest extends \PHPUnit\Framework\TestCase
{

    public function testThreads()
    {
        $opts    = include './private_options.php';
        $threads = new Api($opts['app_token'], $opts['app_secret']);
        $this->assertInternalType('array', $threads->threads());
    }

}