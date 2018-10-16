<?php

namespace Despark\GraphQLBundle;


/**
 * Class GraphQLService
 * @package Despark\GraphQLBundle
 */
interface GraphQLServiceInterface
{
    /**
     * @param string $query
     * @param array $variables
     * @param null|string $operationName
     * @return array
     * @throws \Digia\GraphQL\Error\InvariantException
     */
    public function executeQuery(string $query, array $variables, ?string $operationName): array;
}