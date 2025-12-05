<?php

namespace App\Model;

use App\Entity\User;
use Symfony\Component\Validator\Constraints as Assert;

class NewUserFromOtpModel
{
    public function __construct(
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[Assert\Length(max: 15)]
        public string $phone,
        
        #[Assert\NotNull]
        #[Assert\NotBlank]
        #[Assert\Choice(callback: [User::class, 'getPersonTypesAsList'])]
        public ?string $personType = null,
    )
    {
    }
}