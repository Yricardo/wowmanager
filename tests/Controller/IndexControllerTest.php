<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class IndexControllerTest extends WebTestCase
{

    //we can optimize this test by using a data provider
    
    // data provider providing different user roles and expected redirects
    public function userRolesProvider(): array
    {
        return [
            ['ROLE_ADMIN', '/admin'],
            ['ROLE_USER', '/user'],
            [[], null], // no role, expect 403
        ];
    }

    /**
     * @dataProvider userRolesProvider
     */
    public function testIndexWithLoggedUser(string $userRole, ?string $expectedRedirect): void
    {
        $client = static::createClient();
        
        // Create a user with the specified role
        $user = $this->createUser([$userRole]);

        // Log in the user
        $client->loginUser($user);

        // Request the index page
        $client->request('GET', '/');

        // Check if the response is a redirect[]
        $this->assertTrue($client->getResponse()->isRedirect());

        // Check if the redirect location is correct
        if ($userRole) {
            $this->assertResponseRedirects($this->userRolesProvider()[0][1]);
        } else {
            $this->assertResponseStatusCodeSame(403);// not sure about this
        }

    }

    // user factory method, take roles as argument
    private function createUser(array $roles = []): User
    {
        $user = new User();
        $user->setUsername('testuser');
        $user->setPassword('password');
        $user->setRoles($roles);

        return $user;
    }

}
