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
     * Stream constructor.
     * @param IProxy $proxy
     */
    public function __construct(IProxy $proxy)
    {
        $this->proxy = $proxy;
        $this->boundary = bin2hex(random_bytes(8));
        $this->stream = fopen('php://output', 'w');
        $this->lineEnd = chr(13) . chr(10);
        set_time_limit(0);
        @ini_set('zlib.output_compression', 0);
        @ini_set('implicit_flush', 1);
        ob_implicit_flush(1);
    }

    /**
     * Close output stream if opened.
     */
    function __destruct()
    {
        if ($this->stream)
        {
            fclose($this->stream);
        }
        exit();
    }

    /**
     * Send main HTTP header to the client and configure caching.
     * @throws \Exception
     */
    protected function sendMainHttpHeader(): void
    {
        if (headers_sent())
        {
            throw new \RuntimeException(self::class . ': could not start stream because output has already started.');
        }
        header('Accept-Range: bytes');
        header('Connection: close');
        header('Cache-Control: no-store, no-cache, must-revalidate, pre-check=0, post-check=0, max-age=0');
        header('Pragma: no-cache');
        header('Transfer-Encoding: deflate');
        header('Content-type: multipart/x-mixed-replace; boundary=' . $this->boundary);
    }

    /**
     * Send chunked content to client.
     * @param string $data
     */
    protected function sendHttpChunk($data): void
    {
        fwrite($this->stream, '--' . $this->boundary . $this->lineEnd);
        fwrite($this->stream, 'Content-Type: image/jpeg' . $this->lineEnd);
        fwrite($this->stream, 'Content-Length: ' . strlen($data) . $this->lineEnd . $this->lineEnd);
        fwrite($this->stream, $data);
    }


    /**
     * Sleep to reduce cpu time.
     */
    protected function sleep()
    {
        usleep(500000 * (1 / $this->proxy->getFramesPerSecond()));
    }

    /**
     * Start the mjpeg stream. Note that the connection will not be closed.
     * If the connection close unexpected,. then take a look in you webserver's configuration.
     */
    public function start(): void
    {

        $this->sendMainHttpHeader();
        $lastContent = 'FIRST_FRAME';

        do
        {

            $this->sleep();
            $frame = $this->proxy->getCachedFrame();
            $contentId = $this->proxy->getCachedContentId();

            // Send only actual content to the client.
            if ($contentId === $lastContent)
            {
                continue;
            }
            $lastContent = $contentId;

            if (!$frame)
            {
                continue;
            }
            $this->sendHttpChunk($frame);

        } while (true);

    }

}