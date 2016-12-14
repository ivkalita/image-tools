<?php
/**
 * Created by IntelliJ IDEA.
 * User: Ivan Kalita
 * Date: 15.12.16
 * Time: 1:10
 */

namespace Kaduev13\ImageTools;


interface ImageProcessorInterface
{
    /**
     * Downloads image from URL and process it
     *
     * @param string $url
     * @param string $outPath
     * @return bool
     */
    public function processAndStoreImageFromURL($url, $outPath);

    /**
     * Downloads image from URL and store it in $outPath
     *
     * @param string $url
     * @param string $outPath
     *
     * @return bool
     */
    public function downloadImage($url, $outPath);

    /**
     * Process image from $inPath and store it into $outPath
     *
     * @param string $inPath
     * @param string $outPath
     * @return bool
     */
    public function processImage($inPath, $outPath);
}