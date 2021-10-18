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
            fclose($handle);
        }
        $this->frame = 0;
        $this->count = count($this->frames);
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
        //file_put_contents(__DIR__ . '/a', $this->frame . "\n", FILE_APPEND);
        return $this->frames[$this->frame++];
    }


}