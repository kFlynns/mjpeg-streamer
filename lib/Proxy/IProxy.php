<?php

namespace Kflynns\JpegStreamer\Proxy;

interface IProxy
{

    /**
     * @param string $fqnCacheClass
     * @param int $framesPerSecond
     */
    public function configureCache(string $fqnCacheClass, int $framesPerSecond): void;

    /**
     * @return int
     */
    public function getFramesPerSecond(): int;

    /**
     * @return string
     */
    public function getCachedFrame(): ?string;

    /**
     * @return string
     */
    public function getCachedContentId(): ?string;

    /**
     * @return string
     */
    public function getProxySourceId(): string;


}