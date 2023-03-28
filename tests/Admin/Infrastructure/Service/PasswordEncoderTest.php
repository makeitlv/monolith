<?php

declare(strict_types=1);

namespace App\Tests\Admin\Infrastructure\Service;

use App\Admin\Infrastructure\Service\PasswordEncoder;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\PasswordHasher\Hasher\PasswordHasherFactoryInterface;

final class PasswordEncoderTest extends KernelTestCase
{
    public function testEncoder(): void
    {
        $passwordHasherFactory = self::getContainer()->get(PasswordHasherFactoryInterface::class);

        $hashedPassword = (new PasswordEncoder($passwordHasherFactory))->encode($password = "password");

        self::assertNotEquals($password, $hashedPassword);
        self::assertEquals(60, strlen($hashedPassword));
    }
}
