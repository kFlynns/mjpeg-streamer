<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

use Kflynns\JpegStreamer\Proxy\IProxy;

class AbstractCache implements ICache
{

    /** @var int  */
    protected $ttl;

    /** @var float  */
    protected $lastTick;

    /** @var string */
    protected $proxySourceId;

    /**
     * @return float
     */
    protected function getTick(): float
    {
        return round(microtime(true) * 1000);
    }

    /**
     * @param IProxy $proxy
     */
    public function __construct(IProxy $proxy)
    {
        $this->ttl = 1 / $proxy->getFramesPerSecond() * 1000;
        $this->lastTick = 0;
        $this->proxySourceId = $proxy->getProxySourceId();
    }


    protected function setValue(string $value): void
    {
        throw new \Exception(self::class . ': "' . get_class($this) . '" must implement method "setValue".');
    }

    protected function getValue(): ?string
    {
        throw new \Exception(self::class . ': "' . get_class($this) . '" must implement method "getValue".');
    }

    /**
     * @return string
     */
    public function getProxySourceId(): string
    {
        return $this->proxySourceId;
    }

    /**
     * @return string|null
     * @throws \Exception
     */
    public function get(): ?string
    {
        if ($this->getTick() - $this->lastTick >= $this->ttl)
        {
            return null;
        }
        return $this->getValue();
    }

    /**
     * @param string $value
     */
    public function set(string $value): void
    {
        $this->lastTick = $this->getTick();
        $this->setValue($value);
    }





}