<?php

declare(strict_types=1);

namespace App\Exception;

use App\Constants\ErrorCode;
use Exception;
use Throwable;

class InternalException extends Exception
{
    /**
     * raw.
     *
     * @var array
     */
    public $raw;

    /**
     * Bootstrap.
     */
    public function __construct(int $code = ErrorCode::INTERNAL_PARAMS_ERROR, ?string $message = null, ?array $raw = null, Throwable $previous = null)
    {
        if (is_null($message)) {
            $message = ErrorCode::getMessage($code);
        }

        if (!is_null($raw)) {
            $this->raw = $raw;
        }

        parent::__construct($message, $code, $previous);
    }
}
