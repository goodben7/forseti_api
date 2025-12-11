<?php

namespace App\State;

use App\Manager\UserManager;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;

class ChangeUserPasswordProcessor implements ProcessorInterface
{
    public function __construct(private UserManager $manager)
    {
        
    }

    /**
     * @param \App\Dto\ChangePasswordDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
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

        return $this->manager->changePassword($id, $data->actualPassword, $data->newPassword);
    }

}
