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
    public function configureCache(int $framesPerSecond, string $fqnClassCache): void
    {
        $this->cache = new $fqnClassCache(1 / $framesPerSecond * 1000);
        if (!is_a($this->cache, ICache::class))
        {
            throw new \InvalidArgumentException(self::class . ': "' . $fqnClassCache . '" does not implement ' . ICache::class);
        }
        $this->framesPerSecond = $framesPerSecond;
    }

    /**
     * @return int
     */
    public function getFramesPerSecond(): int
    {
        return $this->framesPerSecond;
    }

}