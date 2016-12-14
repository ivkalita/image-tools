<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 15.12.16
 * Time: 1:08
 */

namespace Kaduev13\ImageTools;


class ImageProcessor implements ImageProcessorInterface
{
    public function processAndStoreImageFromURL($url, $outPath)
    {
        // TODO: Implement processAndStoreImageFromURL() method.
    }

    /**
     * @param string $url
     * @param string $outPath
     *
     * @return bool
     */
    public function downloadImage($url, $outPath)
    {
        $imageData = @file_get_contents($url);
        if ($imageData === false) {
            return false;
        }
        if (file_put_contents($outPath, $imageData) === false) {
            return false;
        }

        return true;
    }

    public function processImage($inPath, $outPath)
    {
        // TODO: Implement processImage() method.
    }
}