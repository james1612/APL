<?php

namespace App\Service;

use App\Entity\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;

class LocalStorageService implements ImageStorageInterface
{
    public function __construct(private string $projectDir, private EntityManagerInterface $em)
    {}

    public function uploadImage($image, $fileName)
    {
        try {
            $path = $this->projectDir . '/public/uploads/';
            $image->move($path, $fileName);

            $upload = new ImageUpload();
            $upload->setType('local');
            $upload->setPath($path . $fileName);
            $upload->setCreatedOn(new \DateTime());

            $this->em->persist($upload);
            $this->em->flush();
        } catch (\Exception $exception) {
            dd($exception->getMessage());
            throw new \Exception('error uploading image to local storage'); // make custom Exception maybe?
        }
    }
}