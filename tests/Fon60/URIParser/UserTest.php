<?php
declare(strict_types = 1);

namespace Tests\Fon60\URIParser;


use Fon60\URIParser\User;

class UserTest extends \PHPUnit_Framework_TestCase
{
    private $userName = 'user';

    /**
     * @test
     */
    public function shouldReturnUserWithUserName()
    {
        $user = User::fromArray(
            ['user'=> $this->userName]
        );
        $this->assertEquals($this->userName, $user->getName());
    }

    /**
     * @test
     * @dataProvider passwords
     */
    public function shouldReturnPasswordIfGiven($password)
    {
        $user = User::fromArray(
            [
                'user'=> $this->userName,
                'pass' => $password
            ]
        );

        $this->assertEquals($password, $user->getPassword());
    }

    public function passwords()
    {
        return [
            [null],
            [''],
            ['password']
        ];
    }
}
