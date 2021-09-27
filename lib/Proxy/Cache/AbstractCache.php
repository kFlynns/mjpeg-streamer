<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

class AbstractCache implements ICache
{

    /** @var int  */
    protected $ttl;

    /** @var float  */
    protected $lastTick;

    /**
     * @return float
     */
    protected function getTick(): float
    {
        return round(microtime(true) * 1000);
    }

    /**
     * @param int $ttl
     */
    public function __construct(int $ttl)
    {
        $this->ttl = $ttl;
        $this->lastTick = 0;
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