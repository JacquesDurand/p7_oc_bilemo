<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\OpenApi;
use Symfony\Component\Security\Core\Security;

class ResellerSchemaConcealerDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
        private Security $security,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openapi = ($this->decorated)($context);
        $user = $this->security->getUser();

        if (null === $user || !in_array('ROLE_ADMIN', $user->getRoles())) {
            $schemas = $openapi->getComponents()->getSchemas();
            if (isset($schemas['Reseller'])) {
                unset($schemas['Reseller']);
            }
            if (isset($schemas['Reseller.jsonld'])) {
                unset($schemas['Reseller.jsonld']);
            }
        }

        return $openapi;
    }
}
