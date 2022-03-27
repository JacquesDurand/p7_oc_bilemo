<?php
declare(strict_types=1);

namespace App\Tests\API;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\Client;
use App\Entity\Administrator;
use App\Entity\Reseller;

abstract class JwtSecuredApiTestCase extends ApiTestCase
{
    private ?string $token = null;

    public function setUp(): void
    {
        self::bootKernel();
    }

    protected function createClientWithCredentials(string $connectedClass, string $token = null): Client
    {
        $token = $token ?: $this->getToken($connectedClass);

        return static::createClient([], ['headers' => ['authorization' => 'Bearer '.$token]]);
    }

    protected function getToken(string $connectedClass, $body = []): string
    {
        if ($this->token) {
            return $this->token;
        }
        $plainPassword = 'admin';
        /** @var Administrator|Reseller $connected */
        $connected = match ($connectedClass) {
            Administrator::class => $this->getOrCreateAdministrator(),
            Reseller::class => $this->getOrCreateReseller(),
        };

        $response = static::createClient()->request('POST', 'http://localhost/api/login_check', ['json' => $body ?: [
            'username' => $connected->getUsername(),
            'password' => $plainPassword,
        ]]);

        $this->assertResponseIsSuccessful();
        $data = \json_decode($response->getContent());
        $this->token = $data->token;

        return $data->token;
    }

    protected function getOrCreateAdministrator(): Administrator
    {
        $admin = self::getContainer()
            ->get('doctrine')
            ->getRepository(Administrator::class)
            ->findOneBy(['email' => 'admin@admin.com'])
            ;
        if (null !== $admin) {
            return $admin;
        }

        return (new Administrator())
            ->setEmail('admin@admin.com')
            ->setPlainPassword('admin')
            ;

    }

    protected function getOrCreateReseller(): Reseller
    {
        $reseller = self::getContainer()
            ->get('doctrine')
            ->getRepository(Reseller::class)
            ->findOneBy(['storeName' => 'admin'])
        ;
        if (null !== $reseller) {
            return $reseller;
        }
        return (new Reseller())
            ->setStoreName('admin')
            ->setPlainPassword('admin')
            ;
    }
}
