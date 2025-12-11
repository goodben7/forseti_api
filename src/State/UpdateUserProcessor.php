<?php

namespace App\State;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\Manager\UserManager;
use App\Model\UpdateUserModel;

class UpdateUserProcessor implements ProcessorInterface
{
    public function __construct(private UserManager $manager)
    {
        
    }

    /**
     * @param \App\Dto\UpdateUserDto $data 
     */
    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = [])
    {
        $model = new UpdateUserModel(
            $data->email,
            $data->phone,
            $data->displayName
        );

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

        return $this->manager->updateFrom($id, $model);
    }
}
