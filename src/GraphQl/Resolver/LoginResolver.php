<?php

namespace App\GraphQl\Resolver;

use App\ApiResource\AuthToken;
use App\Repository\UserRepository;
use ApiPlatform\GraphQl\Resolver\MutationResolverInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

final class LoginResolver implements MutationResolverInterface
{
    public function __construct(
        private UserRepository $users,
        private UserPasswordHasherInterface $hasher,
        private JWTTokenManagerInterface $jwtManager,
    ) {
    }

    public function __invoke(?object $item, array $context): ?object
    {
        $args = $context['args'] ?? [];
        $input = $args['input'] ?? $args;

        $username = $input['username'] ?? null;
        $password = $input['password'] ?? null;

        if (!$username || !$password) {
            throw new \RuntimeException('Identifiants manquants');
        }

        $user = $this->users->findByEmailOrPhone($username);
        if (!$user) {
            throw new \RuntimeException('Utilisateur introuvable');
        }

        if ($user->isDeleted() || $user->isLocked()) {
            throw new \RuntimeException('Compte inactif ou verrouillé');
        }

        if (!$this->hasher->isPasswordValid($user, $password)) {
            throw new \RuntimeException('Identifiants invalides');
        }

        $token = $this->jwtManager->create($user);

        $authToken = new AuthToken();
        $authToken->token = $token;
        return $authToken;
    }
}
