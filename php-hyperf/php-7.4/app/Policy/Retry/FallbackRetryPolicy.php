<?php

declare(strict_types=1);

namespace App\Policy\Retry;

use Hyperf\Retry\Policy\BaseRetryPolicy;
use Hyperf\Retry\Policy\RetryPolicyInterface;
use Hyperf\Retry\RetryContext;
use Hyperf\Utils\ApplicationContext;
use Throwable;

class FallbackRetryPolicy extends BaseRetryPolicy implements RetryPolicyInterface
{
    /**
     * @var callable|string
     */
    private $fallback;

    /**
     * Bootstrap.
     *
     * @param $fallback
     */
    public function __construct($fallback)
    {
        $this->fallback = $fallback;
    }

    /**
     * end.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function end(RetryContext &$retryContext): bool
    {
        if (!isset($retryContext['retryExhausted'])) {
            return false;
        }

        if (!is_callable($this->fallback)) {
            return false;
        }

        $throwable = $retryContext['lastThrowable'] ?? null;
        $arguments = [$throwable];

        $retryContext['lastThrowable'] = $retryContext['lastResult'] = null;
        if (isset($retryContext['proceedingJoinPoint'])) {
            $arguments = array_merge($retryContext['proceedingJoinPoint']->getArguments(), $arguments);
        }

        try {
            if (!is_array($this->fallback)) {
                $retryContext['lastResult'] = call_user_func($this->fallback, ...$arguments);
            } else {
                $retryContext['lastResult'] = ApplicationContext::getContainer()->get($this->fallback[0])->{$this->fallback[1]}(...$arguments);
            }
        } catch (Throwable $throwable) {
            $retryContext['lastThrowable'] = $throwable;
        }

        return false;
    }
}
