<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use ApiPlatform\State\Pagination\ArrayPaginator;
use App\ApiResource\PermissionResource;
use App\Manager\PermissionManager;
use App\Model\Permission;

class PermissionProvider implements ProviderInterface
{

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $list = [];
        /** @var Permission $p */
        foreach (PermissionManager::getInstance()->getPermissions() as $p) {
            $list[] = PermissionResource::fromModel($p);
        }

        $args = $context['args'] ?? [];
        $filters = $context['filters'] ?? [];
        $itemsPerPage = (int) ($args['itemsPerPage'] ?? $filters['itemsPerPage'] ?? 30);
        $page = (int) ($args['page'] ?? $filters['page'] ?? 1);
        $offset = max(0, ($page - 1) * $itemsPerPage);

        return new ArrayPaginator($list, $offset, $itemsPerPage);
    }
}
