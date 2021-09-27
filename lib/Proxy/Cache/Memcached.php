<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

class Memcached implements ICache
{

    /** @var int */
    protected $ttl;

    /** @var string  */
    protected $key;

    /**
     * @param int $ttl
     */
    public function __construct(int $ttl)
    {
        $this->ttl = $ttl;
        $this->memcached = new \Memcached();
        $this->key = 'Kflynns-JpegStreamer-' . bin2hex(random_bytes(2));
    }

    /**
     * @return string
     */
    public function get(): string
    {
        return $this->memcached->get($this->key);
    }

    /**
     * @param string $value
     */
    public function set(string $value): void
    {
        $this->memcached->set($this->key, $value, $this->ttl);
    }

}