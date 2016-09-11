<?php
declare(strict_types = 1);

namespace Tests\Fon60\URIParser;

use Fon60\URIParser\InvalidURIException;
use Fon60\URIParser\Parser;
use Fon60\URIParser\URI;
use Fon60\URIParser\User;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    private $exampleUri = 'http://www.example.com';
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
    public function shouldThrowExceptionOnParsingInvalidURI(string $invalidURI)
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
    public function shouldReturnURIObjectOnParsingValidURIs(string $validUri)
    {
        $currentURI = $this->parser->parse($validUri);

        $this->assertInstanceOf(URI::class, $currentURI);
    }

    public function validURIs()
    {
        return [
            [$this->exampleUri],
            [$this->exampleUri . '/path'],
            [$this->exampleUri . '?query'],
            [$this->exampleUri . '#fragment'],
            [$this->exampleUri . '/path?query'],
            [$this->exampleUri . '/path#fragment'],
            [$this->exampleUri . '/path?query=value'],
            [$this->exampleUri . '/path?query=value#fragment'],
            ['ftp://user@example.com/path'],
            ['ftp://user:pass@example.com/path'],
            ['ftp://user:pass@example.com'],
            ['urn:example:com'],
        ];
    }

    /**
     * @test
     */
    public function shouldReturnURIWithSchemeNullIfURIIsPartial()
    {
        $partialURIString = '../partial';
        $uri = $this->parser->parse($partialURIString);
        $this->assertNull($uri->getScheme());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithSchemePartFilled()
    {
        $schema = 'http';
        $uriString = $this->exampleUri;
        $uri = $this->parser->parse($uriString);

        $this->assertEquals($schema, $uri->getScheme());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithUserNullIfNotPresentInURIString()
    {
        $uriString = $this->exampleUri;
        $uri = $this->parser->parse($uriString);

        $this->assertNull($uri->getUser());
    }

    /**
     * @test
     * @dataProvider URIsWithUsers
     */
    public function shouldReturnURIWithUserPartFilled(string $uriString, User $expectedUser)
    {
        $uri = $this->parser->parse($uriString);

        $this->assertEquals($expectedUser, $uri->getUser());
    }

    public function URIsWithUsers()
    {
        return [
            ['urisString' => 'http://user@example.com', 'user' => new User('user')],
            ['urisString' => 'http://user:pass@example.com', 'user' => new User('user', 'pass')]
        ];
    }

    /**
     * @test
     */
    public function shouldReturnURIWithPathAsNullIfNotPresentInURIString()
    {
        $uriString = $this->exampleUri;
        $uri = $this->parser->parse($uriString);

        $this->assertNull($uri->getPath());
    }

    /**
     * @test
     * @dataProvider URIsWithPath
     */
    public function shouldReturnURIWithPathPartFilled(string $uriString, string $expectedPath)
    {
        $uri = $this->parser->parse($uriString);
        $this->assertEquals($expectedPath, $uri->getPath());
    }

    public function URIsWithPath()
    {
        return [
            ['uriString' => '/path', '/path'],
            ['uriString' => '../path', 'expectedPath' => '../path'],
            ['uriString' => 'path', 'expectedPath' => 'path'],
            ['uriString' => 'example.com/path', 'expectedPath' => 'example.com/path'],
            ['uriString' => 'http://example.com/path', 'expectedPath' => '/path'],
        ];
    }

    /**
     * @test
     */
    public function shouldReturnURIWhtiHostNullIfIsMissingInURIString()
    {
        $partialUri = 'example.com/path';
        $uri = $this->parser->parse($partialUri);
        $this->assertNull($uri->getHost());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithHostPartFilled()
    {
        $uriStringWithHost = $this->exampleUri . '/path';
        $uri = $this->parser->parse($uriStringWithHost);
        $expectedHost = 'www.example.com';
        $this->assertEquals($expectedHost, $uri->getHost());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithPortNullIfNotPresentInURIString()
    {
        $uriString = $this->exampleUri;
        $uri = $this->parser->parse($uriString);
        $this->assertNull($uri->getPort());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithPortPartFilled()
    {
        $expectedPort = 89;
        $uriString = $this->exampleUri . ':' . $expectedPort;
        $uri = $this->parser->parse($uriString);
        $this->assertSame($expectedPort, $uri->getPort());
    }

    /**
     * @test
     */
    public function shouldReturnUriWithNullQueryIfNotPresentInURIString()
    {
        $uriString = $this->exampleUri;
        $uri = $this->parser->parse($uriString);
        $this->assertNull($uri->getQuery());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithQueryPartFilled()
    {
        $expectedQuery = 'query';
        $uriString = $this->exampleUri . '?' . $expectedQuery;
        $uri = $this->parser->parse($uriString);

        $this->assertEquals($expectedQuery, $uri->getQuery());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithFragmentNullIfNotPresentInURIString()
    {
        $uri = $this->parser->parse($this->exampleUri);
        $this->assertNull($uri->getFragment());
    }

    /**
     * @test
     */
    public function shouldReturnURIWithFragmentPartFilled()
    {
        $expectedFragment = 'fragment';
        $uriString = $this->exampleUri . '#' . $expectedFragment;
        $uri = $this->parser->parse($uriString);

        $this->assertEquals($expectedFragment, $uri->getFragment());
    }
}
