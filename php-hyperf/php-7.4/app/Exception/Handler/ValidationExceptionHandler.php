<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Constants\ErrorCode;
use App\Constants\RequestConstant;
use App\Util\Logger;
use Hyperf\Di\Annotation\Inject;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Hyperf\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Throwable;

class ValidationExceptionHandler extends ExceptionHandler
{
    /**
     * @Inject()
     *
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    protected $request;

    /**
     * handle.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        $this->stopPropagation();

        /** @var \Hyperf\Validation\ValidationException $throwable */
        $body = $throwable->validator->errors()->first();

        $data = [
            'code' => ErrorCode::INVALID_PARAMS,
            'message' => $body,
            'request_id' => $this->request->getHeaderLine(RequestConstant::HEADER_REQUEST_ID),
        ];

        Logger::info(
            '<-- 由于参数错误, 业务处理被中断',
            [
                'result' => $data,
                'url' => $this->request->fullUrl(),
                'inputs' => $this->request->all(),
            ]
        );

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
        return $throwable instanceof ValidationException;
    }
}
