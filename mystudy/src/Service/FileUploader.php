<?php
/**
 * Created by PhpStorm.
 * User: ASUS 553
 * Date: 24.08.2018
 * Time: 23:06
 */

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    private $photosDir;
    private $imagesDir;

    public function __construct($photosDir, $imagesDir)
    {
        $this->photosDir = $photosDir;
        $this->imagesDir = $imagesDir;
    }

    public function uploadPhotos(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getPhotosDir(), $fileName);

        return $fileName;
    }

    public function uploadImages(UploadedFile $file)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();

        $file->move($this->getImagesDir(), $fileName);

        return $fileName;
    }

    public function getPhotosDir()
    {
        return $this->photosDir;
    }

    /**
     * @return mixed
     */
    public function getImagesDir()
    {
        return $this->imagesDir;
    }
}