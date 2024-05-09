<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/image/view')]
class ImageViewController extends AbstractController
{

    #[Route('/', name: 'app_image_upload_view')]
    public function view()
    {
        return $this->render('upload.html.twig');
    }



}
