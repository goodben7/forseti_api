<?php

namespace App\State;

use App\Manager\UserManager;
use ApiPlatform\State\ProcessorInterface;

class DeleteUserProcessor implements ProcessorInterface
{
    public function __construct(private UserManager $manager)
    {   
    }

    public function process(mixed $data, \ApiPlatform\Metadata\Operation $operation, array $uriVariables = [], array $context = [])
    {
        $id = $uriVariables['id'] ?? ($context['args']['input']['id'] ?? null);

        if ($id === null) {
            throw new \InvalidArgumentException('Missing user identifier');
        }

        if (\is_string($id) && str_starts_with($id, '/')) {
            $id = basename($id);
        } elseif (\is_string($id)) {
            $decoded = base64_decode($id, true);
            if ($decoded && str_contains($decoded, '/api/users/')) {
                $id = basename($decoded);
            }
        }

        return $this->manager->delete($id);
    }
}
