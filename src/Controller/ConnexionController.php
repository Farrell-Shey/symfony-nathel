<?php

namespace App\Controller;

use App\Service\OsuApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     * @param OsuApiService $osuApiService
     * @return Response
     */
    public function index(OsuApiService $osuApiService): Response
    {

        dd($osuApiService->getBeatmapInfo(951304));

        return $this->render('connexion/index.html.twig', [
            'controller_name' => 'ConnexionController',
        ]);
    }
}
