<?php
namespace App\ApiResource;

use App\Model\Permission;
use App\Provider\PermissionProvider;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\GraphQl\QueryCollection;

#[ApiResource(
    shortName: "Permission",
    description: "Permissions exposées via HTTP et GraphQL",
    operations: [
        new GetCollection(
            provider: PermissionProvider::class,
        )
    ],
    graphQlOperations: [
        new QueryCollection(
            description: "Liste des permissions",
            provider: PermissionProvider::class,
            paginationType: 'page',
            paginationClientItemsPerPage: true,
        ),
    ]
)]
class PermissionResource {

    public function __construct(
        #[ApiProperty(identifier: true, description: "Identifiant de la permission (rôle)")]
        public string $role,
        #[ApiProperty(description: "Libellé lisible de la permission")]
        public string $label,
    )
    {

    }

    public static function fromModel(Permission $p): static {
        return new self($p->getPermissionId(), $p->getLabel());
    }
}
