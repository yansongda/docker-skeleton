<?php

declare(strict_types=1);

namespace App\Listener;

use App\Util\Logger;
use Hyperf\Database\Events\QueryExecuted;
use Hyperf\Database\Events\TransactionBeginning;
use Hyperf\Database\Events\TransactionCommitted;
use Hyperf\Database\Events\TransactionRolledBack;
use Hyperf\Event\Annotation\Listener;
use Hyperf\Event\Contract\ListenerInterface;
use Hyperf\Utils\Arr;
use Hyperf\Utils\Str;

/**
 * @Listener
 */
class DbListener implements ListenerInterface
{
    /**
     * listen.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function listen(): array
    {
        return [
            TransactionBeginning::class,
            QueryExecuted::class,
            TransactionCommitted::class,
            TransactionRolledBack::class,
        ];
    }

    /**
     * @param QueryExecuted $event
     */
    public function process(object $event)
    {
        if ($event instanceof TransactionBeginning) {
            Logger::info('数据库事务开始', [], ['channel' => 'sql']);
        }

        if ($event instanceof QueryExecuted) {
            $sql = $event->sql;
            if (!Arr::isAssoc($event->bindings)) {
                foreach ($event->bindings as $key => $value) {
                    $sql = Str::replaceFirst('?', "'{$value}'", $sql);
                }
            }

            Logger::info(sprintf('[%s] %s', $event->time, $sql), [], ['channel' => 'sql']);
        }

        if ($event instanceof TransactionCommitted) {
            Logger::info('数据库事务已提交', [], ['channel' => 'sql']);
        }

        if ($event instanceof TransactionRolledBack) {
            Logger::info('数据库事务已回滚', [], ['channel' => 'sql']);
        }
    }
}
