<?php

namespace Kflynns\JpegStreamer\Proxy\Cache;

class File extends AbstractCache
{

    /** @var false|resource  */
    protected $handle;

    /** @var int */
    protected $bufferSize;

    /**
     * @param int $ttl
     */
    public function __construct(int $ttl)
    {
        $this->handle = fopen(tempnam(sys_get_temp_dir(), 'kflynns_jpegstreamer_'), 'w+');
        $this->bufferSize = 0;
        parent::__construct($ttl);
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