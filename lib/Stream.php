<?php

namespace Kflynns\JpegStreamer;

use KFlynns\JpegStreamer\Proxy\IProxy;

class Stream
{

    /** @var IProxy  */
    protected $proxy;

    /** @var string  */
    protected $boundary;

    /** @var false|resource  */
    protected $stream;

    /** @var string */
    protected $lineEnd;

    /**
     * @param IProxy $proxy
     */
    public function __construct(IProxy $proxy)
    {
        $this->proxy = $proxy;
        $this->boundary = bin2hex(random_bytes(8));
        $this->stream = fopen('php://output', 'w');
        $this->lineEnd = chr(13) . chr(10);
    }

    function __destruct()
    {
        if ($this->stream)
        {
            fclose($this->stream);
        }
        exit();
    }

    /**
     * @throws \Exception
     */
    protected function sendMainHttpHeader(): void
    {
        if (headers_sent())
        {
            throw new \RuntimeException(self::class . ': could not start stream because output has already started.');
        }
        set_time_limit(0);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        ob_implicit_flush(1);
        header('Accept-Range: bytes');
        header('Connection: close');
        header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
        header('Pragma: no-cache');
        header('Transfer-Encoding: deflate');
        header('Content-type: multipart/x-mixed-replace; boundary=' . $this->boundary);
    }

    /**
     * @param string $data
     */
    protected function sendChunk($data): void
    {
        fwrite($this->stream, '--' . $this->boundary . $this->lineEnd);
        fwrite($this->stream, 'Content-Type: image/jpeg' . $this->lineEnd);
        fwrite($this->stream, 'Content-Length: ' . strlen($data) . $this->lineEnd . $this->lineEnd);
        fwrite($this->stream, $data);
    }

    /**
     * Start the mjpeg stream. Note that the connection will not be closed.
     * If the connection close unexpected then take a look in you webserver's config.
     */
    public function start(): void
    {
        $this->sendMainHttpHeader();
        do
        {
            $frame = $this->proxy->getCachedFrame();
            if (!$frame)
            {
                break;
            }
            usleep(1000000 * (1 / $this->proxy->getFramesPerSecond()));
            $this->sendChunk($frame);
        } while (true);
    }

}