<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Di\Annotation\Inject;

abstract class AbstractController
{
    /**
     * @Inject
     *
     * @var \Psr\Container\ContainerInterface
     */
    protected $container;

    /**
     * @Inject
     *
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    protected $request;

    /**
     * @Inject
     *
     * @var \Hyperf\HttpServer\Contract\ResponseInterface
     */
    protected $response;
}
