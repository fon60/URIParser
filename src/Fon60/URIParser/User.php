<?php
declare(strict_types = 1);

namespace Fon60\URIParser;

class User
{
    private $password;
    private $userName;

    /**
     * @param string $userName
     * @param string|null $password
     */
    public function __construct(string $userName, string $password = null)
    {
        $this->userName = $userName;
        $this->password = $password;
    }

    public static function fromArray(array $array)
    {
        return new static(
            $array['user'],
            $array['pass'] ?? null
        );
    }
}