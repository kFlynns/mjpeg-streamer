<?php

namespace Kflynns\JpegStreamer\Proxy;

class FileArray extends AbstractProxy
{

    /** @var array  */
    protected $frames;

    /**
     * @param array $imagePaths
     */
    public function __construct(array $imagePaths)
    {
        $this->frames = [];
        // load to ram
        foreach (array_reverse($imagePaths) as $path)
        {
            $handle = fopen($path, 'r');
            fseek($handle, SEEK_SET);
            $buffer = '';
            while (!feof($handle))
            {
                $buffer .= fread($handle, 1024);
            }
            $this->frames[] = $buffer;
            $this->frames[] = $buffer;
            $this->frames[] = $buffer;
            fclose($handle);
        }
    }

    /**
     * @return string|null
     */
    public function getFrame(): ?string
    {
        return array_pop($this->frames);
    }


}