<?php

namespace Kflynns\JpegStreamer\Proxy;

use Kflynns\JpegStreamer\Proxy\Cache\ICache;

class AbstractProxy implements IProxy
{

    /** @var int  */
    protected $framesPerSecond;

    /** @var ICache */
    protected $cache;

    /**
     * @return string
     * @throws \Exception
     */
    protected function getFrame(): ?string
    {
        throw new \Exception(self::class . ': "' . get_class($this) . '" must implement method "getFrame".');
    }

    /**
     *
     * @return ?string
     * @throws \Exception
     */
    public function getCachedFrame() : ?string
    {
        $frame = $this->cache->get();
        if (null === $frame)
        {
            $frame = $this->getFrame();
            $this->cache->set($frame);
        }
        return $frame;
    }

    /**
     * @param int $framesPerSecond
     */
    public function setFramesPerSecond($framesPerSecond): void
    {
        $this->framesPerSecond = $framesPerSecond;
    }

    /**
     * @return int
     */
    public function getFramesPerSecond(): int
    {
        return $this->framesPerSecond;
    }

    /**
     * @param string $fqnCacheClass
     * @param int $ttl
     * @throws \Exception
     */
    public function setCache(string $fqnCacheClass, int $ttl): void
    {
        $this->cache = new $fqnCacheClass($ttl);
        if (!is_a($this->cache, ICache::class))
        {
            throw new \Exception($fqnCacheClass . ' does not implement ICache interface.');
        }
    }
}