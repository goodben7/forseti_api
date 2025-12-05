<?php

namespace App\ApiResource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GraphQl\Mutation;

#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/auth/token/{token}',
            read: false,
        ),
    ],
    graphQlOperations: [
        new Mutation(
            name: 'login',
            resolver: \App\GraphQl\Resolver\LoginResolver::class,
            args: [
                'username' => ['type' => 'String!', 'description' => 'Email ou téléphone'],
                'password' => ['type' => 'String!', 'description' => 'Mot de passe'],
            ],
        ),
    ]
)]
class AuthToken
{
    #[ApiProperty(identifier: true)]
    public string $token = '';
}
