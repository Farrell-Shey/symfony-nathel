<?php

namespace App\Controller;

use App\Repository\MappoolRepository;
use App\Service\CallApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/home", name="home")
     */
    public function index(CallApiService $callApiService): Response
    { 
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/popular", name="popular")
     */
    public function showPopularMappools(MappoolRepository $mappoolRepository)
    {
        $popularMappools = $mappoolRepository->findByPopularity();
        return $this->render('home/index.html.twig', [
            'popularMappools' => $popularMappools,

        ]);
    }

    /**
     * @Route("/recent", name="recent")
     */
    public function showRecentMappools(MappoolRepository $mappoolRepository)
    {
        $recentMappools = $mappoolRepository->findByMostRecent();
        return $this->render('home/index.html.twig', [
            'recentMappools' => $recentMappools,
        ]);
    }


}
