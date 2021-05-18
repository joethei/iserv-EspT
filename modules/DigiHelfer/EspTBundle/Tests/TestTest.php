<?php
declare(strict_types=1);

namespace DigiHelfer\EspT\Tests;

use PHPUnit\Framework\TestCase;

class TestTest extends TestCase {

    public function test(): void {

        $this->assertIsInt(15);
        $this->assertEquals("Test", "Test");
    }

}