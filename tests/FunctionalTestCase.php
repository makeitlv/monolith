<?php

declare(strict_types=1);

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Zenstruck\Messenger\Test\Transport\TestTransport;

abstract class FunctionalTestCase extends KernelTestCase
{
    protected function setUp(): void
    {
        TestTransport::resetAll();

        parent::setUp();
    }
}
