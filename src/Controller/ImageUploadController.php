<?php

namespace App\Controller;

use App\Service\AzureStorageService;
use App\Service\LocalStorageService;
use App\Service\Factory\ImageStorageFactory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/image/upload')]
class ImageUploadController extends AbstractController
{

    const MAX_ALLOWED_HEIGHT = 1024;
    const MAX_ALLOWED_WIDTH = 1024;

    #[Route('/new', name: 'app_image_upload_new', methods: ['POST'])]
    public function new(
        Request $request, 
        LocalStorageService $localStorageService, 
        AzureStorageService $azureStorageService,
        ImageStorageFactory $storageFactory
        ): Response
    {
        $image = $request->files->get('image');

        $errors = $this->validateImage($image);
        if ($errors) {
            $jsonResponse = json_encode(['errors' => $errors]);
            return new Response($jsonResponse, Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

    
        try {
            $fileName = $image->getClientOriginalName();
            $environment = $this->getParameter('kernel.environment');
            $storageService = $storageFactory->createStorageService($environment);
    
            $storageService->uploadImage($image, $fileName);
        } catch (\Exception $exception) {
            return new Response($exception->getMessage(), Response::HTTP_BAD_REQUEST, ['Content-Type' => 'application/json']);
        }

        return new Response('', Response::HTTP_CREATED, ['Content-Type' => 'application/json']);

    }

    private function validateImage($image): array
    {
        $errors = [];

        if (!$image) {
            $errors[] = 'No image uploaded.';
            return $errors;
        }

        if (!in_array($image->guessExtension(), ['jpeg', 'jpg', 'png'])) {
            $errors[] = 'Invalid file format. Please upload JPG or PNG images.';
        }

        [$width, $height] = getimagesize($image->getPathname());
        if ($width > self::MAX_ALLOWED_WIDTH || $height > self::MAX_ALLOWED_HEIGHT) {
            $errors[] = 'Image dimensions exceed the maximum allowed (1024x1024).';
        }

        return $errors;
    }}
