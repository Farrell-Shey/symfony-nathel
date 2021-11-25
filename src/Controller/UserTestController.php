<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserTestController extends AbstractController
{
    /**
     * @Route("/test/user", name="test_user")
     */
    public function index(): Response
    {
        return $this->render('page/user.html.twig', [
            'controller_name' => 'UserTestController',
        ]);
    }
}
