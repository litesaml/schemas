<?php

namespace Tests;

use LiteSaml\Schema;
use LiteSaml\UnexpectedSchemaException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    #[Test]
    public function can_have_expected_schema() : void
    {
        $schemaName = 'saml-schema-metadata-2.0.xsd';

        $schema = Schema::get($schemaName);

        $this->assertNotEmpty($schema);
    }

    #[Test]
    public function cant_have_unexpected_schema() : void
    {
        $wrongSchemaName = 'unexpected.xsd';

        $this->expectException(UnexpectedSchemaException::class);
        $this->expectExceptionMessage('Invalid schema specified: unexpected.xsd');

        Schema::get($wrongSchemaName);
    }
}