<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ZoneTestController extends AbstractController
{
    /**
     * @Route("/zone/test", name="zone_test")
     */
    public function index(): Response
    {
        return $this->render('zone_test/index.html.twig', [
            'controller_name' => 'ZoneTestController',
        ]);
    }
}
