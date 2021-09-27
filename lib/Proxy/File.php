<?php

namespace Kflynns\JpegStreamer\Proxy;

class File extends AbstractProxy
{

    /** @var false|resource  */
    protected $handle;

    /**
     * @param array $imagePaths
     */
    public function __construct(string $imagePath)
    {
        $this->handle = fopen($imagePath, 'r');
    }

    public function __destruct()
    {
        fclose($this->handle);
    }

    /**
     * @return string|null
     */
    protected function getFrame(): ?string
    {
        $buffer = '';
        fseek($this->handle, \SEEK_SET);
        while (!feof($this->handle))
        {
            $buffer .= fread($this->handle, 1024);
        }
        return $buffer;
    }

}