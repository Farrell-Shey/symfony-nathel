<?php

namespace App\Controller;

use App\Repository\MappoolRepository;
use App\Service\OsuApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="home")
     * @param OsuApiService $osuApiService
     * @return Response
     */
    public function index(OSuApiService $osuApiService, MappoolRepository $mappoolRepository): Response
    {  
        return $this->render('home/index.html.twig', [
            'mappools' => $mappoolRepository->findAll(),
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
