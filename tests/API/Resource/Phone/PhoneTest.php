<?php
declare(strict_types=1);

namespace App\Tests\API\Resource\Phone;

use App\Entity\Administrator;
use App\Entity\Phone;
use App\Entity\Reseller;
use App\Tests\API\JwtSecuredApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class PhoneTest extends JwtSecuredApiTestCase
{

    public function testCollectionFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/phones');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testItemFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/phones/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }
     ############
    ## Reseller ##
    ############

    public function testConnectedResellerCanSeeCollection()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/phones');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedResellerCanSeeCollectionWithPagination()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/phones?page=2');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedResellerCanSeeItem()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/phones/1');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedResellerCannotAddPhone()
    {
        $body = ['json' => [
            'builder' => 'TestBuilder',
            'model' => 'Test Model',
            'description' => 'Test Description',
            'color' => 'Test Color',
            'price' => 40000,
        ]];

        $this->createClientWithCredentials(Reseller::class)->request('POST', 'https://localhost/api/phones', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);

    }

    public function testConnectedResellerCannotUpdatePhone()
    {
        $body = ['json' => [
            'builder' => 'TestUpdated',
            'model' => 'Test Updated model',
            'description' => 'Test updated Description',
            'color' => 'Test updated Color',
            'price' => 45000,
        ]];

        $this->createClientWithCredentials(Reseller::class)->request('PUT', 'https://localhost/api/phones/1', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    public function testConnectedResellerCannotDeletePhone()
    {
        $iri = $this->findIriBy(Phone::class, ['id' => 97]);
        $this->createClientWithCredentials(Reseller::class)->request('DELETE', 'https://localhost'.$iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
        $this->assertNotNull(
            self::getContainer()->get('doctrine')->getRepository(Phone::class)->findOneBy(['id' => 97])
        );
    }

     #########
    ## Admin ##
    #########
    public function testConnectedAdminCanSeeCollection()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/phones');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedAdminCanSeeCollectionWithPagination()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/phones?page=2');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedAdminCanSeeItem()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/phones/1');
        $this->assertResponseIsSuccessful();
    }

    public function testConnectedAdminCanAddNewPhone()
    {
        $body = ['json' => [
            'builder' => 'TestBuilder',
            'model' => 'Test Model',
            'description' => 'Test Description',
            'color' => 'Test Color',
            'price' => 40000,
        ]];

        $response = $this->createClientWithCredentials(Administrator::class)->request('POST', 'https://localhost/api/phones', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Phone',
            '@type' => 'Phone',
            'builder' => 'TestBuilder',
            'model' => 'Test Model',
            'description' => 'Test Description',
            'color' => 'Test Color',
            'price' => 40000,
        ]);
        $this->assertMatchesRegularExpression('~^/api/phones/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Phone::class);
    }

    public function testConnectedAdminCanUpdatePhone()
    {
        $body = ['json' => [
            'builder' => 'TestUpdated',
            'model' => 'Test Updated model',
            'description' => 'Test updated Description',
            'color' => 'Test updated Color',
            'price' => 45000,
        ]];

        $response = $this->createClientWithCredentials(Administrator::class)->request('PUT', 'https://localhost/api/phones/1', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Phone',
            '@type' => 'Phone',
            'builder' => 'TestUpdated',
            'model' => 'Test Updated model',
            'description' => 'Test updated Description',
            'color' => 'Test updated Color',
            'price' => 45000,
        ]);
        $this->assertMatchesRegularExpression('~^/api/phones/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Phone::class);
    }

    public function testConnectedAdminCanDeletePhone()
    {
        $iri = $this->findIriBy(Phone::class, ['id' => 99]);
        $this->createClientWithCredentials(Administrator::class)->request('DELETE', 'https://localhost'.$iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(Phone::class)->findOneBy(['id' => 99])
        );

    }

}
