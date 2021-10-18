<?php

namespace Kflynns\JpegStreamer\Proxy;

interface IProxy
{

    /**
     * @param int $framesPerSecond
     * @param string $fqnCacheClass
     */
    public function configureCache(int $framesPerSecond, string $fqnCacheClass): void;

    /**
     * @return int
     */
    public function getFramesPerSecond(): int;

    /**
     * @return string
     */
    public function getCachedFrame(): ?string;

}