<?php

declare(strict_types=1);

namespace App\Service\Http;

use GuzzleHttp\Client;
use Hyperf\Guzzle\HandlerStackFactory;
use Yansongda\Supports\Traits\HasHttpRequest;

class GeneralHttp
{
    use HasHttpRequest;

    /**
     * 基础uri.
     *
     * @var string
     */
    protected $baseUri = '';

    /**
     * @var int
     */
    private $poolMinConnections = 1;

    /**
     * @var int
     */
    private $poolMaxConnections = 100;

    /**
     * @var float
     */
    private $poolWaitTimeout = 3.0;

    /**
     * @var int
     */
    private $poolMaxIdleTime = 30;

    /**
     * getDefaultHttpClient.
     *
     * @author yansongda <me@yansongda.cn>
     */
    public function getHttpClient(): Client
    {
        return make(Client::class, [
            'config' => array_merge(
                [
                    'handler' => (new HandlerStackFactory())->create([
                        'min_connections' => $this->getPoolMinConnections(),
                        'max_connections' => $this->getPoolMaxConnections(),
                        'wait_timeout' => $this->getPoolWaitTimeout(),
                        'max_idle_time' => $this->getPoolMaxIdleTime(),
                    ]),
                ],
                $this->getOptions()
            ),
        ]);
    }

    public function getPoolMinConnections(): int
    {
        return $this->poolMinConnections;
    }

    public function setPoolMinConnections(int $poolMinConnections): GeneralHttp
    {
        $this->poolMinConnections = $poolMinConnections;

        return $this;
    }

    public function getPoolMaxConnections(): int
    {
        return $this->poolMaxConnections;
    }

    public function setPoolMaxConnections(int $poolMaxConnections): GeneralHttp
    {
        $this->poolMaxConnections = $poolMaxConnections;

        return $this;
    }

    public function getPoolWaitTimeout(): float
    {
        return $this->poolWaitTimeout;
    }

    public function setPoolWaitTimeout(float $poolWaitTimeout): GeneralHttp
    {
        $this->poolWaitTimeout = $poolWaitTimeout;

        return $this;
    }

    public function getPoolMaxIdleTime(): int
    {
        return $this->poolMaxIdleTime;
    }

    public function setPoolMaxIdleTime(int $poolMaxIdleTime): GeneralHttp
    {
        $this->poolMaxIdleTime = $poolMaxIdleTime;

        return $this;
    }
}
