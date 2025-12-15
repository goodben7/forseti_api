<?php

namespace App\Provider;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class UserInfoProvider implements ProviderInterface
{
    public function __construct(private Security $security, private UserRepository $users)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $current = $this->security->getUser();

        if ($current instanceof User) {
            return $current;
        }

        if ($current instanceof UserInterface) {
            $username = $current->getUserIdentifier();
            $id = $username;
            if (\is_string($id) && str_starts_with($id, '/')) {
                $id = basename($id);
            } elseif (\is_string($id)) {
                $decoded = base64_decode($id, true);
                if ($decoded && str_contains($decoded, '/api/users/')) {
                    $id = basename($decoded);
                }
            }

            $entity = null;
            if (\is_string($id)) {
                $entity = $this->users->find($id);
            }

            if (!$entity) {
                $entity = $this->users->findByEmailOrPhone($username);
            }

            return $entity;
        }

        return null;
    }
}
