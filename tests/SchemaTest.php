<?php

namespace Tests;

use LiteSaml\Schema;
use LiteSaml\UnexpectedSchemaException;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

class SchemaTest extends TestCase
{
    #[Test]
    public function can_validate_expected_schema() : void
    {
        $schemaName = 'saml-schema-metadata-2.0.xsd';

        $xml = <<<EOF
<EntityDescriptor ID="_2240bd9c-30c4-4d2a-ab3e-87a94ea334fd" entityID="http://some.entity.id"
        xmlns="urn:oasis:names:tc:SAML:2.0:metadata">
    <SPSSODescriptor WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"
                Location="https://my.site/saml/acs" index="0"/>
    </SPSSODescriptor>
</EntityDescriptor>
EOF;

        $errorBag = Schema::validate($xml, $schemaName);

        $this->assertFalse($errorBag->hasErrors());
    }

    #[Test]
    public function can_have_errors_on_wrong_xml(): void
    {
        $schemaName = 'saml-schema-metadata-2.0.xsd';

        $xml = <<<EOF
<EntityDescriptor ID="_2240bd9c-30c4-4d2a-ab3e-87a94ea334fd"
        xmlns="urn:oasis:names:tc:SAML:2.0:metadata">
    <SPSSODescriptor WantAssertionsSigned="true" protocolSupportEnumeration="urn:oasis:names:tc:SAML:2.0:protocol">
        <AssertionConsumerService Binding="urn:oasis:names:tc:SAML:2.0:bindings:HTTP-POST"
                Location="https://my.site/saml/acs"/>
    </SPSSODescriptor>
</EntityDescriptor>
EOF;

        $errorBag = Schema::validate($xml, $schemaName);

        $this->assertTrue($errorBag->hasErrors());

        $this->assertCount(2, $errorBag->getErrors());

        $errors = $errorBag->getErrors();

        $this->assertEquals("DOMDocument::schemaValidate(): Element '{urn:oasis:names:tc:SAML:2.0:metadata}EntityDescriptor': The attribute 'entityID' is required but missing.", $errors[0]->message);
        $this->assertEquals("DOMDocument::schemaValidate(): Element '{urn:oasis:names:tc:SAML:2.0:metadata}AssertionConsumerService': The attribute 'index' is required but missing.", $errors[1]->message);
    }

    #[Test]
    public function cant_validated_unexpected_schema() : void
    {
        $wrongSchemaName = 'unexpected.xsd';

        $this->expectException(UnexpectedSchemaException::class);
        $this->expectExceptionMessage('Invalid schema specified: unexpected.xsd');

        Schema::validate('...', $wrongSchemaName);
    }
}