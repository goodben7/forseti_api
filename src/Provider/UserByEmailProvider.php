<?php

namespace App\Provider;

use App\Repository\UserRepository;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProviderInterface;

final class UserByEmailProvider implements ProviderInterface
{
    public function __construct(private UserRepository $users)
    {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        $args = $context['args'] ?? [];
        $input = $args['input'] ?? $args;
        $email = $input['email'] ?? null;
        if (!$email) {
            return null;
        }
        return $this->users->findByEmailOrPhone($email);
    }
}
