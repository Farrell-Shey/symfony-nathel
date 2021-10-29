<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MyCollectionController extends AbstractController
{
    /**
     * @Route("/my/collection", name="my_collection")
     */
    public function index(): Response
    {
        return $this->render('/page/my-collection.html.twig', [
            'controller_name' => 'MyCollectionController',
        ]);
    }
}
