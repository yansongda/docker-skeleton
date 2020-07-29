<?php

declare(strict_types=1);

return [
    'handler' => [
        'http' => [
            App\Exception\Handler\HttpExceptionHandler::class,
            App\Exception\Handler\ValidationExceptionHandler::class,
            App\Exception\Handler\ApiExceptionHandler::class,
            App\Exception\Handler\UnknownExceptionHandler::class,
        ],
    ],
];
