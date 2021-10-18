<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

use Kflynns\JpegStreamer\Proxy\IProxy;

class File extends AbstractCache
{

    /** @var false|resource  */
    protected $handle;

    /** @var int */
    protected $bufferSize;

    /**
     * @param IProxy $proxy
     */
    public function __construct(IProxy $proxy)
    {
        parent::__construct($proxy);
        $this->handle = fopen(sys_get_temp_dir() . '/kflynns_mjpeg-streamer_' . $this->getProxySourceId() . '_cache', 'w+');
        $this->bufferSize = 0;
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    /**
     * @return string|null
     */
    protected function getValue(): ?string
    {
        if ($this->bufferSize === 0)
        {
            return null;
        }
        fseek($this->handle, SEEK_SET);
        return fread($this->handle, $this->bufferSize);
    }

    /**
     * @param string $value
     */
    protected function setValue(string $value): void
    {
        fseek($this->handle, SEEK_SET);
        $this->bufferSize = fwrite($this->handle, $value);
    }

}