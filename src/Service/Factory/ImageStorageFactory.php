<?php

namespace App\Service\Factory;

use App\Service\AzureStorageService;
use App\Service\LocalStorageService;
use App\Service\ImageStorageInterface;

class ImageStorageFactory
{
    private $azureStorageService;
    private $localStorageService;

    public function __construct(AzureStorageService $azureStorageService, LocalStorageService $localStorageService)
    {
        $this->azureStorageService = $azureStorageService;
        $this->localStorageService = $localStorageService;
    }

    public function createStorageService(string $environment): ImageStorageInterface
    {
        if ($environment === 'prod') {
            return $this->azureStorageService;
        } else {
            return $this->localStorageService;
        }
    }
}
