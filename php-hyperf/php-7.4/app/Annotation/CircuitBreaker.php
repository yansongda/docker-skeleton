<?php

declare(strict_types=1);

namespace App\Annotation;

use App\Policy\Retry\FallbackRetryPolicy;

/**
 * @Annotation
 * @Target({"METHOD"})
 */
class CircuitBreaker extends \Hyperf\Retry\Annotation\CircuitBreaker
{
    /**
     * Bootstrap.
     *
     * @param null $value
     */
    public function __construct($value = null)
    {
        $this->policies[0] = FallbackRetryPolicy::class;

        parent::__construct($value);
    }
}
