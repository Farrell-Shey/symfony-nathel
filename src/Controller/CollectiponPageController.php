<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CollectiponPageController extends AbstractController
{
    /**
     * @Route("/collection", name="collectipon_page")
     */
    public function index(): Response
    {
        return $this->render('/page/collection-page.html.twig', [
            'controller_name' => 'CollectiponPageController',
        ]);
    }
}
