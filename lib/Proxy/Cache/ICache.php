<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

interface ICache
{
    /**
     * @param int $ttl
     */
    public function __construct(int $ttl);

    /**
     * @return ?string
     */
    public function get(): ?string;

    /**
     * @param string $value
     */
    public function set(string $value): void;

}