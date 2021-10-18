<?php

use Kflynns\JpegStreamer\Proxy\Cache\File as Cache;
use Kflynns\JpegStreamer\Proxy\FileArray;
use Kflynns\JpegStreamer\Stream;

require __DIR__ . '/vendor/autoload.php';


$proxy = new FileArray([
    __DIR__ . '/example/frame_1.jpg',
    __DIR__ . '/example/frame_2.jpg',
    __DIR__ . '/example/frame_3.jpg',
    __DIR__ . '/example/frame_4.jpg',
    __DIR__ . '/example/frame_5.jpg',
    __DIR__ . '/example/frame_6.jpg',
    __DIR__ . '/example/frame_7.jpg',
    __DIR__ . '/example/frame_8.jpg',
    __DIR__ . '/example/frame_9.jpg',
    __DIR__ . '/example/frame_10.jpg',
]);

$proxy->configureCache(Cache::class, 4);


$stream = new Stream($proxy);
$stream->start();