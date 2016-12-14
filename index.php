<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kaduev13\ImageTools\ImageProcessor;

$testImage = 'http://shop.scavino.it/ReadyPro/files/scavino_Files/Foto/14848_63265_20160511_171727.jpg';
$imageProcessor = new ImageProcessor();
$imageProcessor->downloadImage($testImage, './images/img.jpg');