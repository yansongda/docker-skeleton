<?php

declare(strict_types=1);

namespace App\Constants\Security;

class AccessTokenConstant
{
    /**
     * 所需 scope.
     */
    public const SCOPE = 'openid';

    /**
     * 存储在 redis 中的 access_token key.
     */
    public const ACCESS_TOKEN_CACHE = 'access_token:%s';

    /**
     * 存储在 redis 中的 ttl.
     */
    public const ACCESS_TOKEN_TTL = 10 * 60 * 60;
}
