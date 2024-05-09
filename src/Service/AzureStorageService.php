<?php

namespace App\Service;

use MicrosoftAzure\Storage\Blob\BlobRestProxy;
use MicrosoftAzure\Storage\Common\Exceptions\ServiceException;
use App\Entity\ImageUpload;
use Doctrine\ORM\EntityManagerInterface;

class AzureStorageService implements ImageStorageInterface
{
    private $blobClient;
    private $containerName;
    private $em;

    public function __construct(string $connectionString, string $containerName, EntityManagerInterface $em)
    {
        $this->blobClient = BlobRestProxy::createBlobService($connectionString);
        $this->containerName = $containerName;
        $this->em = $em;
    }

    public function uploadImage($image, $fileName)
    {
        try {
            $this->blobClient->createBlockBlob($this->containerName, $fileName, fopen($image->getPathname(), 'r'));

            $upload = new ImageUpload();
            $upload->setType('azure');
            $upload->setPath($this->containerName);
            $upload->setCreatedOn(new \DateTime());
    
            $this->em->persist($upload);
            $this->em->flush();
    
        } catch (ServiceException $e) {
            throw new \RuntimeException('An error occurred while uploading the image to Azure Blob Storage');
        } catch (\Exception $e) {
            throw new \RuntimeException('An unexpected error occurred');
        }
    }
}
