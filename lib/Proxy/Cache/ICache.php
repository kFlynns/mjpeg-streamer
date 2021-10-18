<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

use Kflynns\JpegStreamer\Proxy\IProxy;

interface ICache
{
    /**
     * @param IProxy $proxy
     */
    public function __construct(IProxy $proxy);

    /**
     * @return ?string
     */
    public function get(): ?string;

    /**
     * @param string $value
     */
    public function set(string $value): void;

}