<?php

namespace Despark\GraphQLBundle\Controller;

use Despark\GraphQLBundle\GraphQLServiceManagerInterface;
use Despark\GraphQLBundle\GraphQLServiceInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class GraphQLController
 * @package Despark\GraphQLBundle\Controller
 */
class GraphQLController extends Controller
{

    /**
     * @var \Despark\GraphQLBundle\GraphQLServiceManagerInterface
     */
    private $serviceManager;

    /**
     * GraphQLController constructor.
     * @param \Despark\GraphQLBundle\GraphQLServiceManager $serviceManager
     */
    public function __construct(GraphQLServiceManagerInterface $serviceManager)
    {
        $this->serviceManager = $serviceManager;
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \Digia\GraphQL\Error\InvariantException
     */
    public function handle(Request $request)
    {
        if ($request->getMethod() === 'POST') {
            $service = $this->getCurrentService($request);

            if (!$service) {
                throw $this->createNotFoundException();
            }

            [
                'query' => $query,
                'variables' => $variables,
                'operationName' => $operationName,
            ] = $this->parseJson($request);

            $result = $service->executeQuery($query, $variables ?? [], $operationName);

            return new JsonResponse($result);
        } else {
            return new JsonResponse(['Hello 👋']);
        }
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array|null
     */
    private function parseJson(Request $request): ?array
    {
        $defaults = [
            'query' => '',
            'variables' => [],
            'operationName' => null,
        ];

        return array_merge($defaults, json_decode($request->getContent(), true));
    }

    /**
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return \Despark\GraphQLBundle\GraphQLServiceInterface|null
     */
    private function getCurrentService(Request $request): ?GraphQLServiceInterface
    {
        $schemaName = str_replace('graphql.', '', $request->get('_route'));

        return $this->serviceManager->getService($schemaName);
    }
}