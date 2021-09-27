<?php

namespace Kflynns\JpegStreamer\Proxy;

interface IProxy
{

    /**
     * @param int $framesPerSecond
     */
    public function setFramesPerSecond($framesPerSecond): void;

    /**
     * @return int
     */
    public function getFramesPerSecond(): int;

    /**
     * @return string
     */
    public function getCachedFrame(): ?string;

    /**
     * @param string $fqnCacheClass
     * @param int $ttl
     */
    public function setCache(string $fqnCacheClass, int $ttl): void;

}