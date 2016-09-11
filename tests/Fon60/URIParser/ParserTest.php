<?php
declare(strict_types=1);

namespace Tests\Fon60\URIParser;

use Fon60\URIParser\InvalidURIException;
use Fon60\URIParser\Parser;
use Fon60\URIParser\URI;

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

    /**
     * @test
     * @dataProvider validURIs
     */
    public function shouldReturnURIObjectOnParsingValidURIs($validUri)
    {
        $currentURI = $this->parser->parse($validUri);

        $this->assertInstanceOf(URI::class, $currentURI);
    }

    public function validURIs()
    {
        return [
            ['http://example.com'],
            ['http://example.com/path'],
            ['http://example.com?query'],
            ['http://example.com#fragment'],
            ['http://example.com/path?query'],
            ['http://example.com/path#fragment'],
            ['http://example.com/path?query=value'],
            ['ftp://example.com/path?query=value#fragment'],
            ['ftp://user@example.com/path'],
            ['ftp://user:pass@example.com/path'],
            ['ftp://user:pass@example.com'],
        ];
    }
}
