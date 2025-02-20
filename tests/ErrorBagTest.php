<?php

namespace Tests;

use LiteSaml\Error;
use LiteSaml\ErrorBag;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class ErrorBagTest extends TestCase
{
    #[Test]
    public function can_add_errors(): void
    {
        $errorBag = new ErrorBag();

        $errorBag->addError(3, 0, "Error occured");

        $errors = $errorBag->getErrors();

        $this->assertCount(1, $errors);
        $this->assertInstanceOf(Error::class, $errors[0]);
        $this->assertEquals("Error occured", $errors[0]->message);
    }

    #[Test]
    public function can_known_if_has_errors(): void
    {
        $errorBag = new ErrorBag();

        $this->assertFalse($errorBag->hasErrors());

        $errorBag->addError(3, 0, "Error occured");

        $this->assertTrue($errorBag->hasErrors());
    }
}