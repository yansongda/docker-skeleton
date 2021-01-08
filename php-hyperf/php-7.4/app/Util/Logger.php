<?php

declare(strict_types=1);

namespace App\Util;

use App\Constants\RequestConstant;
use Hyperf\Logger\LoggerFactory;
use Hyperf\Utils\ApplicationContext;
use Hyperf\Utils\Context;
use Psr\Container\ContainerInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

/**
 * @method static void emergency($message, array $context = [], array $logger = [])
 * @method static void alert($message, array $context = [], array $logger = [])
 * @method static void critical($message, array $context = [], array $logger = [])
 * @method static void error($message, array $context = [], array $logger = [])
 * @method static void warning($message, array $context = [], array $logger = [])
 * @method static void notice($message, array $context = [], array $logger = [])
 * @method static void info($message, array $context = [], array $logger = [])
 * @method static void debug($message, array $context = [], array $logger = [])
 * @method static void log($message, array $context = [], array $logger = [])
 */
class Logger
{
    /**
     * __callStatic.
     */
    public static function __callStatic(string $method, array $params)
    {
        $channel = $params[2][0] ?? $params[2]['channel'] ?? 'app';
        $config = $params[2][1] ?? $params[2]['config'] ?? 'default';
        $logger = Logger::get($channel, $config);

        if (method_exists($logger, $method)) {
            $params[1] = $params[1] ?? [];
            $request = Context::get(ServerRequestInterface::class);

            if (!is_null($request)) {
                $requestId = $request->getHeaderLine(RequestConstant::HEADER_REQUEST_ID);

                $params[1] = array_merge($params[1], ['request_id' => $requestId]);
            }

            $logger->{$method}($params[0], $params[1]);
        }
    }

    /**
     * __invoke.
     *
     * @return \Psr\Log\LoggerInterface
     */
    public function __invoke(ContainerInterface $container)
    {
        return Logger::get('sys', 'system');
    }

    /**
     * get.
     */
    public static function get(string $name = 'app', string $config = 'default'): LoggerInterface
    {
        return ApplicationContext::getContainer()->get(LoggerFactory::class)->get($name, $config);
    }
}
