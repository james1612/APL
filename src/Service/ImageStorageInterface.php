<?php

namespace App\Service;

interface ImageStorageInterface
{
    public function uploadImage($image, $fileName);
}
