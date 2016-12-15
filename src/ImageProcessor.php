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
    /**
     * @var int
     */
    protected $maxSideSize;

    /**
     * @var string
     */
    protected $tmpPath;

    /**
     * ImageProcessor constructor.
     * @param int $maxSideSize
     * @param string $tmpPath
     */
    public function __construct($maxSideSize = 1800, $tmpPath = __DIR__ . '/../temp/')
    {
        $this->maxSideSize = $maxSideSize;
        $this->tmpPath = $tmpPath;
    }

    /**
     * @param string $url
     * @param string $outPath
     * @param int $quality
     */
    public function processAndStoreImageFromURL($url, $outPath, $quality = 60)
    {
        $tmpPath = $this->tmpPath . md5($url) . strrchr($url, '.');
        $this->downloadImage($url, $tmpPath);
        $this->processImage($tmpPath, $outPath, $quality);
        unlink($tmpPath);
    }

    /**
     * @param string $url
     * @param string $outPath
     */
    public function downloadImage($url, $outPath)
    {
        $imageData = @file_get_contents($url);
        if ($imageData === false) {
            throw new \RuntimeException('Unable to download image');
        }
        if (@file_put_contents($outPath, $imageData) === false) {
            throw new \RuntimeException('Unable to save image to file');
        }
    }

    /**
     * @param string $inPath
     * @param string $outPath
     * @param int $quality
     */
    public function processImage($inPath, $outPath, $quality = 60)
    {
        $img = $this->imageFromFile($inPath);
        if ($img === false) {
            throw new \RuntimeException('Unable to create image from file');
        }

        $resized = $this->resizeImage($img);
        if ($resized === false) {
            throw new \RuntimeException('Unable to resize image');
        }
        $resized = $this->fixOrientation($resized, @exif_read_data($inPath));

        $this->saveImage($resized, $outPath, 60);
        imagedestroy($resized);
        if ($resized !== $img) {
            imagedestroy($img);
        }
    }

    private function saveImage($image, $path, $quality)
    {
        $extension = strrchr($path, '.');
        $extension = strtolower($extension);

        switch ($extension) {
            case '.jpg':
            case '.jpeg':
                if (imagetypes() & IMG_JPG) {
                    imagejpeg($image, $path, $quality);
                }
                break;
            case '.gif':
                if (imagetypes() & IMG_GIF) {
                    imagegif($image, $path);
                }
                break;
            case '.png':
                $scaleQuality = round(($quality / 100) * 9);
                $invertScaleQuality = 9 - $scaleQuality;
                if (imagetypes() & IMG_PNG) {
                    imagepng($image, $path, $invertScaleQuality);
                }
                break;
            default:
                throw new \RuntimeException('Unable to save image to file');
        }
    }

    private function imageFromFile($path)
    {
        $imageExtension = strtolower(strrchr($path, '.'));
        switch ($imageExtension) {
            case '.jpg':
            case '.jpeg':
                $img = @imagecreatefromjpeg($path);
                break;
            case '.gif':
                $img = @imagecreatefromgif($path);
                break;
            case '.png':
                $img = @imagecreatefrompng($path);
                break;
            default:
                $img = false;
                break;
        }

        return $img;
    }

    /**
     * @param $img
     *
     * @return resource
     */
    private function resizeImage($img)
    {
        $originalWidth = imagesx($img);
        $originalHeight = imagesy($img);


        if (min($originalHeight, $originalWidth) < $this->maxSideSize) {
            return $img;
        }
        if ($originalHeight > $originalWidth) {
            $optimalHeight = $this->maxSideSize;
            $optimalWidth = $optimalHeight / $originalHeight * $originalWidth;
        } else {
            $optimalWidth = $this->maxSideSize;
            $optimalHeight = $optimalWidth / $originalWidth * $originalHeight;
        }

        $resized = imagecreatetruecolor($optimalWidth, $optimalHeight);
        imagecopyresampled($resized, $img, 0, 0, 0, 0, $optimalWidth, $optimalHeight, $originalWidth, $originalHeight);

        return $resized;
    }

    /**
     * @param $img
     * @param $exif
     *
     * @return resource
     */
    private function fixOrientation($img, $exif)
    {
        if (!isset($exif['Orientation'])) {
            return $img;
        }
        switch ($exif['Orientation']) {
            case 3:
                $angle = 180;
                break;
            case 6:
                $angle = -90;
                break;
            case 8:
                $angle = 90;
                break;
            default:
                $angle = 0;
        }
        if ($angle !== 0) {
            $img = imagerotate($img, $angle, 0);
        }

        return $img;
    }
}