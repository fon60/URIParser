<?php
declare(strict_types = 1);

namespace Fon60\URIParser;

class Parser
{
    const REGEX_DELIMITER = '~';
    const ALFA = 'a-zA-Z';
    const NUM = '0-9';
    const UNRESERVED = '\-_\.\!\~\*\'\(\)';
    const RESERVED = ';/?:@&=+$,#';
    const ESCAPE_INDICATOR = '%';
    private $validCharacters = self::ALFA . self::NUM . self::UNRESERVED . self::RESERVED . self::ESCAPE_INDICATOR;

    public function parse(string $URIString): URI
    {
        $regExp = self::REGEX_DELIMITER . '^[' . $this->validCharacters . ']+$' . self::REGEX_DELIMITER;
        if (!preg_match($regExp, $URIString)) {
            throw new InvalidURIException($URIString);
        }

        return new URI(parse_url($URIString));
    }
}
