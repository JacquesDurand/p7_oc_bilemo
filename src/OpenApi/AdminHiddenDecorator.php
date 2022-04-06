<?php

declare(strict_types=1);

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;

class AdminHiddenDecorator implements OpenApiFactoryInterface
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
                $path = $this->hideRoute($path, $openApi, $key, Request::METHOD_GET);
                $path = $this->hideRoute($path, $openApi, $key, Request::METHOD_POST);
                $path = $this->hideRoute($path, $openApi, $key, Request::METHOD_PUT);
                $path = $this->hideRoute($path, $openApi, $key, Request::METHOD_DELETE);
            }
        }

        return $openApi;
    }

    private function hideRoute(PathItem $path, OpenApi $openApi, string $key, string $method): PathItem
    {
        $get = sprintf('get%s', ucfirst($method));
        $with = sprintf('with%s', ucfirst($method));
        if (!method_exists(PathItem::class, $get) || !method_exists(PathItem::class, $with)) {
            return $path;
        }
        if ($path->$get() && 'hidden' === $path->$get()->getSummary()) {
            $path = $path->$with(null);
            $openApi->getPaths()->addPath($key, $path);
        }

        return $path;
    }
}
