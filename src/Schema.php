<?php

namespace LiteSaml;

class Schema
{
    private const PATH = __DIR__ . '/../resources/';

    /**
     * @throws \LiteSaml\UnexpectedSchemaException
     */
    public static function get(string $name): string
    {
        $filename = self::PATH . $name;

        if (! is_file($filename)) {
            throw new UnexpectedSchemaException('Invalid schema specified: ' . $name);
        }

        return file_get_contents($filename);
    }
}
