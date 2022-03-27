<?php
declare(strict_types=1);

namespace App\Tests\API\Login;

use App\Entity\Administrator;
use App\Entity\Reseller;
use App\Tests\API\JwtSecuredApiTestCase;
use Hautelook\AliceBundle\PhpUnit\ReloadDatabaseTrait;

class LoginTest extends JwtSecuredApiTestCase
{
    use ReloadDatabaseTrait;

    public function testAdminLoginSuccessful(): void
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/resellers');
        $this->assertResponseIsSuccessful();
    }

    public function testResellerLoginSuccessful(): void
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', '/api/phones');
        $this->assertResponseIsSuccessful();
    }

}
