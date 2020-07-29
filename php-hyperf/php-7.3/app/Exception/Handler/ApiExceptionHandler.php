<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Constants\RequestConstant;
use App\Exception\ApiException;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ApiExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject()
     *
     * @var \Psr\Http\Message\ServerRequestInterface
     */
    protected $request;

    /**
     * handle.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @param \Throwable|\App\Exception\ApiException $throwable
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();

        $data = [
            'code' => $throwable->getCode(),
            'message' => '' === $throwable->getMessage() ? ErrorCode::getMessage($throwable->getCode()) : $throwable->getMessage(),
            'request_id' => $this->request->getHeaderLine(RequestConstant::HEADER_REQUEST_ID),
        ];

        if (property_exists($throwable, 'raw') && !is_null($throwable->raw)) {
            $data['data'] = $throwable->raw;
        }

        return $response
            ->withStatus(200)
            ->withHeader('Content-type', 'application/json')
            ->withBody(new SwooleStream(json_encode($data)));
    }

    /**
     * isValid.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function isValid(Throwable $throwable): bool
    {
        return $throwable instanceof ApiException;
    }
}
