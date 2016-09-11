<?php
declare(strict_types=1);

namespace Fon60\URIParser;

class URI
{
    /** @var  User */
    private $user;
    private $data;

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * @return null|string
     */
    public function getScheme()
    {
        return $this->data['scheme'] ?? null;
    }

    /**
     * @return User|null
     */
    public function getUser()
    {
        if (!$this->user && isset($this->data['user'])) {
            $this->user = User::fromArray(
                array_intersect_key(
                    $this->data,
                    array_flip(['user', 'pass'])
                )
            );
        }
        return $this->user ?? null;
    }

    /**
     * @return string|null
     */
    public function getPath()
    {
        return $this->data['path'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getHost()
    {
      return $this->data['host'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getPort()
    {
        return $this->data['port'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getQuery()
    {
        return $this->data['query'] ?? null;
    }

    /**
     * @return string|null
     */
    public function getFragment()
    {
        return $this->data['fragment'] ?? null;
    }
}