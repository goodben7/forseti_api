<?php

namespace App\Command;

use ApiPlatform\GraphQl\Type\SchemaBuilderInterface;
use ApiPlatform\Symfony\Bundle\Command\GraphQlExportCommand as BaseCommand;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'api:graphql:export')]
class ApiGraphqlExportCommand extends BaseCommand
{
    public function __construct(SchemaBuilderInterface $schemaBuilder)
    {
        parent::__construct($schemaBuilder);
    }
}

