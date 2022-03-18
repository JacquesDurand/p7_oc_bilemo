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
                    $openApi->getPaths()->addPath($key, $path->withPost(null));
                }
                // Hide Put
                if ($path->getPut() && 'hidden' === $path->getPut()->getSummary()) {
                    $openApi->getPaths()->addPath($key, $path->withPut(null));
                }
                // Hide Delete
                if ($path->getDelete() && 'hidden' === $path->getDelete()->getSummary()) {
                    $openApi->getPaths()->addPath($key, $path->withDelete(null));
                }
            }
        }

        return $openApi;
    }
}
