<?php

namespace Tests\Fon60\URIParser;

use Fon60\URIParser\InvalidURIException;
use Fon60\URIParser\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /** @var Parser */
    private $parser;

    /**
     * @before
     */
    public function initializeParser()
    {
        $this->parser = new Parser();
    }

    /**
     * @test
     * @dataProvider invalidURIs
     */
    public function shouldThrowExceptionOnParsingInvalidURI($invalidURI)
    {
        $this->expectException(InvalidURIException::class);

        $this->parser->parse($invalidURI);
    }

    public function invalidURIs()
    {
        return [
            ['[some::invalid::uri'],
            [']some::invalid::uri'],
            ['\`some::invalid::uri'],
            ['"some::invalid::uri'],
            ['>some::invalid::uri'],
            ['<some::invalid::uri'],
            ['{some::invalid::uri'],
            ['}some::invalid::uri'],
            ['|some::invalid::uri'],
            ['\some::invalid::uri'],
            ['^some::invalid::uri']
        ];
    }
}
