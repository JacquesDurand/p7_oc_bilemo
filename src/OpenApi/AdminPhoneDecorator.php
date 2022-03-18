<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use Symfony\Component\Security\Core\Security;

class AdminPhoneDecorator implements OpenApiFactoryInterface
{
    public function __construct(
        private OpenApiFactoryInterface $decorated,
        private Security $security,
    ) {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = ($this->decorated)($context);
        $user = $this->security->getUser();

        if (null === $user || !in_array('ROLE_ADMIN', $user->getRoles())) {
            /** @var PathItem $path */
            foreach ($openApi->getPaths()->getPaths() as $key => $path) {
                // Hide Post
                if ($path->getPost() && 'hidden' === $path->getPost()->getSummary()) {
                    $path = $path->withPost(null);
                    $openApi->getPaths()->addPath($key, $path);
                }
                // Hide Put
                if ($path->getPut() && 'hidden' === $path->getPut()->getSummary()) {
                    $path = $path->withPut(null);
                    $openApi->getPaths()->addPath($key, $path);
                }
                // Hide Delete
                if ($path->getDelete() && 'hidden' === $path->getDelete()->getSummary()) {
                    $path = $path->withDelete(null);
                    $openApi->getPaths()->addPath($key, $path);
                }
            }
        }

        return $openApi;
    }
}
