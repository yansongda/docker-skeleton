<?php

declare(strict_types=1);

namespace App\Model\Entity;

use Hyperf\DbConnection\Model\Model;
use Yansongda\Supports\Arr;

abstract class AbstractEntity extends Model
{
    /**
     * new.
     *
     * @author yansongda <me@yansongda.cn>
     *
     * @return static
     */
    public static function new()
    {
        return new static();
    }

    /**
     * toCamelKeyArray.
     *
     * 前端需要驼峰形式
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function toCamelKeyArray(): array
    {
        return Arr::camelCaseKey($this->toArray());
    }
}
