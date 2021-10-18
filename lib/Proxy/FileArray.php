<?php

namespace Kflynns\JpegStreamer\Proxy;

class FileArray extends AbstractProxy
{

    /** @var array  */
    protected $frames;

    /** @var int */
    protected $frame;

    /** @var int */
    protected $count;

    /** @var string */
    protected $proxySourceId;

    /**
     * @param array $imagePaths
     */
    public function __construct(array $imagePaths)
    {
        $this->frames = [];
        // load to ram
        foreach (array_reverse($imagePaths) as $path)
        {
            $this->proxySourceId = hash('md5', $this->proxySourceId . $path);
            $handle = fopen($path, 'r');
            fseek($handle, SEEK_SET);
            $buffer = '';
            while (!feof($handle))
            {
                $buffer .= fread($handle, 1024);
            }
            $this->frames[] = $buffer;
            fclose($handle);
        }
        $this->frame = 0;
        $this->count = count($this->frames);
    }


    /**
     * Return an identifier for the used source.
     * @return string
     */
    public function getProxySourceId(): string
    {
        return $this->proxySourceId;
    }

    /**
     * @return string|null
     */
    public function getFrame(): ?string
    {
        if ($this->frame >= $this->count)
        {
            $this->frame = 0;
        }
        return $this->frames[$this->frame++];
    }


}