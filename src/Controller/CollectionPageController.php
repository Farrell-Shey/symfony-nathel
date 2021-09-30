<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/collection", name="collection_")
 */
class CollectionPageController extends AbstractController
{
/**
 * @Route("/", name="index")
 */
    public function index(): Response
    {
        return $this->render('/page/collection-page.html.twig', []);
    }

    /**
     * @Route("/edit", name="edit")
     */
    public function edit(): Response
    {
        return $this->render('/page/edit-collection.html.twig', []);
    }
}
