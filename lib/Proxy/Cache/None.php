<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

class None implements ICache
{

    /** @var string  */
    protected $value;

    /**
     * @param int $ttl
     */
    public function __construct(int $ttl)
    {
        $this->value = null;
    }

    /**
     * @return ?string
     */
    public function get(): ?string
    {
        return $this->value;
    }

    /**
     * @param string $value
     */
    public function set(string $value): void
    {
        $this->value = $value;
    }

}