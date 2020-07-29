<?php

declare(strict_types=1);

namespace App\Aspect;

use App\Annotation\DbTransaction;
use Exception;
use Hyperf\DbConnection\Db;
use Hyperf\Di\Annotation\Aspect;
use Hyperf\Di\Aop\AbstractAspect;
use Hyperf\Di\Aop\ProceedingJoinPoint;

/**
 * @Aspect(priority=1)
 */
class DbTransactionAspect extends AbstractAspect
{
    /**
     * annotations.
     *
     * @var array
     */
    public $annotations = [
        DbTransaction::class,
    ];

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
        try {
            Db::beginTransaction();

            $result = $proceedingJoinPoint->process();

            Db::commit();
        } catch (Exception $e) {
            Db::rollBack();

            throw $e;
        }

        return $result;
    }
}
