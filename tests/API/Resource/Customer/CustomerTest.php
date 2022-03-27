<?php
declare(strict_types=1);

namespace App\Tests\API\Resource\Customer;

use App\Entity\Administrator;
use App\Entity\Customer;
use App\Entity\Reseller;
use App\Tests\API\JwtSecuredApiTestCase;
use Symfony\Component\HttpFoundation\Response;

class CustomerTest extends JwtSecuredApiTestCase
{
    public function testCollectionFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/customers');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

    public function testItemFailsWhenNotLogged()
    {
        $this->createClient()->request('GET', 'https://localhost/api/customers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_UNAUTHORIZED);
    }

     ##########
    # Reseller #
    ##########

    public function testResellerCanSeeRelatedCollection()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/customers');
        $this->assertResponseIsSuccessful();
    }

    public function testResellerCanSeeRelatedItem()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/customers/8');
        $this->assertResponseIsSuccessful();
    }

    public function testResellerCanAddCustomer()
    {
        $body = ['json' => [
            'email' => 'new@customer.com',
            'password' => 'P@ssW0rd',
            'phoneNumber' => '060000000',
        ]];

        $response = $this->createClientWithCredentials(Reseller::class)->request('POST', 'https://localhost/api/customers', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Customer',
            '@type' => 'Customer',
            'email' => 'new@customer.com',
            'phoneNumber' => '060000000',
        ]);
        $this->assertMatchesRegularExpression('~^/api/customers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Customer::class);
    }

    public function testResellerCanUpdateRelatedCustomer()
    {
        $body = ['json' => [
            'email' => 'modified@customer.com',
            'password' => 'P@ssW0rd',
            'phoneNumber' => '060000000',
        ]];

        $iri = $this->findIriBy(Customer::class, ['id' => 8]);
        $response = $this->createClientWithCredentials(Reseller::class)->request('PUT', 'https://localhost'.$iri, $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Customer',
            '@type' => 'Customer',
            'email' => 'modified@customer.com',
            'phoneNumber' => '060000000',
        ]);
        $this->assertMatchesRegularExpression('~^/api/customers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Customer::class);
    }

    public function testResellerCanDeleteRelatedCustomer()
    {
        $iri = $this->findIriBy(Customer::class, ['id' => 8]);
        $this->createClientWithCredentials(Reseller::class)->request('DELETE', 'https://localhost'.$iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(Customer::class)->findOneBy(['id' => 8])
        );
    }

    public function testResellerCannotGetNonRelatedCustomer()
    {
        $this->createClientWithCredentials(Reseller::class)->request('GET', 'https://localhost/api/customers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testResellerCannotUpdateNonRelatedCustomer()
    {
        $body = ['json' => [
            'email' => 'modified@customer.com',
            'password' => 'P@ssW0rd',
            'phoneNumber' => '060000000',
        ]];

        $this->createClientWithCredentials(Reseller::class)->request('PUT', 'https://localhost/1', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    public function testResellerCannotDeleteNonRelatedCustomer()
    {
        $this->createClientWithCredentials(Reseller::class)->request('DELETE', 'https://localhost/api/customers/1');
        $this->assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

     #######
    # Admin #
    #######

    public function testAdminCanSeeCollection()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/customers');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCanSeeItem()
    {
        $this->createClientWithCredentials(Administrator::class)->request('GET', 'https://localhost/api/customers/8');
        $this->assertResponseIsSuccessful();
    }

    public function testAdminCanAddCustomer()
    {
        $body = ['json' => [
            'email' => 'new@customer.com',
            'password' => 'P@ssW0rd',
            'phoneNumber' => '060000000',
        ]];

        $response = $this->createClientWithCredentials(Administrator::class)->request('POST', 'https://localhost/api/customers', $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_CREATED);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Customer',
            '@type' => 'Customer',
            'email' => 'new@customer.com',
            'phoneNumber' => '060000000',
        ]);
        $this->assertMatchesRegularExpression('~^/api/customers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Customer::class);
    }

    public function testAdminCanUpdateCustomer()
    {
        $body = ['json' => [
            'email' => 'modified@customer.com',
            'password' => 'P@ssW0rd',
            'phoneNumber' => '060000000',
        ]];

        $iri = $this->findIriBy(Customer::class, ['id' => 8]);
        $response = $this->createClientWithCredentials(Administrator::class)->request('PUT', 'https://localhost'.$iri, $body);
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);
        $this->assertResponseHeaderSame('content-type', 'application/ld+json; charset=utf-8');
        $this->assertJsonContains([
            '@context' => '/api/contexts/Customer',
            '@type' => 'Customer',
            'email' => 'modified@customer.com',
            'phoneNumber' => '060000000',
        ]);
        $this->assertMatchesRegularExpression('~^/api/customers/\d+$~', $response->toArray()['@id']);
        $this->assertMatchesResourceItemJsonSchema(Customer::class);
    }

    public function testAdminCanDeleteCustomer()
    {
        $iri = $this->findIriBy(Customer::class, ['id' => 8]);
        $this->createClientWithCredentials(Administrator::class)->request('DELETE', 'https://localhost'.$iri);
        $this->assertResponseStatusCodeSame(Response::HTTP_NO_CONTENT);
        $this->assertNull(
            self::getContainer()->get('doctrine')->getRepository(Customer::class)->findOneBy(['id' => 8])
        );
    }
}
