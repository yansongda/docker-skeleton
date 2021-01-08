<?php

declare(strict_types=1);

namespace App\Api;

use App\Constants\RequestConstant;
use Hyperf\Di\Annotation\Inject;

abstract class AbstractApiController
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

    /**
     * Return success data to the client.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function success(?array $data = null): array
    {
        $result = [
            'code' => 0,
            'message' => 'success',
            'request_id' => $this->request->getHeaderLine(RequestConstant::HEADER_REQUEST_ID),
        ];

        if (!is_null($data)) {
            $result['data'] = $data;
        }

        return $result;
    }
}
