<?php

declare(strict_types=1);

namespace App\Aspect;

use App\Annotation\RecordRequestLogger;
use App\Exception\ApiException;
use App\Util\Logger;
use Exception;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Annotation\Inject;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * @Aspect(priority=2)
 */
class RecordRequestLoggerAspect extends AbstractAspect
{
    /**
     * annotations.
     *
     * @var array
     */
    public $annotations = [
        RecordRequestLogger::class,
    ];

    /**
     * request.
     *
     * @Inject()
     *
     * @var \Hyperf\HttpServer\Contract\RequestInterface
     */
    protected $request;

    /**
     * process.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function process(ProceedingJoinPoint $proceedingJoinPoint)
    {
        Logger::info(
            '--> 处理业务请求',
            [
                'url' => $this->request->fullUrl(),
                'inputs' => $this->request->all(),
            ]
        );

        $start_time = microtime(true);

        try {
            $result = $proceedingJoinPoint->process();
        } catch (Exception $e) {
            if ($e instanceof ApiException) {
                Logger::info('<-- 业务处理被中断', ['time' => microtime(true) - $start_time, 'code' => $e->getCode(), 'message' => $e->getMessage(), 'raw' => $e->raw]);
            }

            throw $e;
        }

        Logger::info('<-- 处理业务请求完毕', ['time' => microtime(true) - $start_time, 'result' => $result]);

        return $result;
    }
}
