<?php
declare(strict_types = 1);

namespace Fon60\URIParser;

class Parser
{
    public function parse($invalidURI)
    {
        throw new InvalidURIException($invalidURI);
    }
}
