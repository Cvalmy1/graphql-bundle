<?php

namespace Despark\GraphQLBundle;

use function Digia\GraphQL\graphql;
use Digia\GraphQL\Schema\Schema;

/**
 * Class GraphQLService
 * @package Despark\GraphQLBundle
 */
class GraphQLService implements GraphQLServiceInterface
{
    private $schema;

    /**
     * GraphQLService constructor.
     * @param \Digia\GraphQL\Schema\Schema $schema
     */
    public function __construct(Schema $schema)
    {
        $this->schema = $schema;
    }

    /**
     * @param string $query
     * @param array $variables
     * @param null|string $operationName
     * @return array
     * @throws \Digia\GraphQL\Error\InvariantException
     */
    public function executeQuery(string $query, array $variables, ?string $operationName): array
    {
        return graphql($this->schema, $query, null, null, $variables, $operationName);
    }

}