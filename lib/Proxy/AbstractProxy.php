<?php

namespace Kflynns\JpegStreamer\Proxy;

use Kflynns\JpegStreamer\Proxy\Cache\ICache;

class AbstractProxy implements IProxy
{

    /** @var int  */
    protected $framesPerSecond;

    /** @var ICache */
    protected $cache;

    /** @var string */
    protected $cachedContentId;

    /**
     * Overwrite this in the child class.
     * @return string
     * @throws \Exception
     */
    protected function getFrame(): ?string
    {
        throw new \Exception(
            self::class . ': "' . get_class($this) . '" must implement method "getFrame".'
        );
    }

    /**
     * Overwrite this in the child class.
     * @return string
     * @throws \Exception
     */
    public function getProxySourceId(): string
    {
        throw new \Exception(
            self::class . ': "' . get_class($this) . '" must implement method "getSourceId".'
        );
    }

    /**
     *
     * @return ?string
     * @throws \Exception
     */
    public function getCachedFrame(): ?string
    {
        $frame = $this->cache->get();
        if (null === $frame)
        {
            $frame = $this->getFrame();
            $this->cache->set($frame);
            $this->cachedContentId = hash('crc32', $frame);
        }
        return $frame;
    }

    /**
     * Set the cache class that should be instantiated and the fps used at the stream source.
     * @param string $fqnClassCache
     * @param int $framesPerSecond
     */
    public function configureCache(string $fqnClassCache, int $framesPerSecond): void
    {
        $this->framesPerSecond = $framesPerSecond;
        $this->cache = new $fqnClassCache($this);
        if (!is_a($this->cache, ICache::class))
        {
            throw new \InvalidArgumentException(
                self::class . ': "' . $fqnClassCache . '" does not implement ' . ICache::class
            );
        }
    }

    /**
     * Frames per second that should be requested from the stream source.
     * @return int
     */
    public function getFramesPerSecond(): int
    {
        return $this->framesPerSecond;
    }

    /**
     * Identifier for actual cached content.
     * @return string
     */
    public function getCachedContentId(): ?string
    {
        return $this->cachedContentId;
    }


}