<?php

require_once __DIR__ . '/vendor/autoload.php';

use Kaduev13\ImageTools\ImageProcessor;

$testImage = 'http://shop.scavino.it/ReadyPro/files/scavino_Files/Foto/33776_110033_644417210.BMP.PNG';
$imageProcessor = new ImageProcessor();
$imageProcessor->processAndStoreImageFromURL($testImage, './images/img1.jpg');