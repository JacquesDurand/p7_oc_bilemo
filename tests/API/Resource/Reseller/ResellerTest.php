<?php
declare(strict_types=1);

namespace App\Tests\API\Resource\Reseller;

use App\Entity\Administrator;
use App\Entity\Customer;
use App\Entity\Reseller;
use App\Tests\API\JwtSecuredApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class ResellerTest extends JwtSecuredApiTestCase
{
    public function testCollectionFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/resellers');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testItemFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/resellers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testCollectionFailsWhenNotAdmin()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/resellers');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testItemFailsWhenNotAdmin()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/resellers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testAdministratorCanSeeCollection()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/resellers');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdministratorCanSeeItem()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/resellers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    public function testAdminCanAddReseller()
    {
        $body = ['json' => [
            'storeName' => 'newReseller',
            'password' => 'P@ssW0rd',
        ]];

        $response = $this->createClientWithCredentials(Administrator::class)->request('POST', 'https://localhost/api/resellers', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Reseller',
            '@type' => 'Reseller',
            'storeName' => 'newReseller',
        ]);
        $this->assertMatchesRegularExpression('~^/api/resellers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Reseller::class);
    }

    public function testAdminCanUpdateReseller()
    {
        $body = ['json' => [
            'storeName' => 'updatedReseller',
            'password' => 'P@ssW0rd',
        ]];

        $response = $this->createClientWithCredentials(Administrator::class)->request('PUT', 'https://localhost/api/resellers/1', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Reseller',
            '@type' => 'Reseller',
            'storeName' => 'updatedReseller',
        ]);
        $this->assertMatchesRegularExpression('~^/api/resellers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Reseller::class);
    }

    public function testAdminCanDeleteReseller()
    {
        $this->createClientWithCredentials(Administrator::class)->request('DELETE', 'https://localhost/api/resellers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(Reseller::class)->find(1)
        );
        $this->assertEmpty(
            self::getContainer()->get('doctrine')->getRepository(Customer::class)->findBy([
                'reseller' => 1
            ])
        );
    }
}
