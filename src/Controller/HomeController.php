<?php

namespace App\Controller;

use App\Repository\BeatmapRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Service\OsuApiService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{

    /**
     * @Route("/", name="app_home")
     * @param OsuApiService $osuApiService
     * @return Response
     */
    public function index(OSuApiService $osuApiService, MappoolRepository $mappoolRepository, MappoolMapRepository $mappoolMapRepository, BeatmapRepository $beatmapRepository): Response
    {

        /*
         * Retourne les 5 mappools les plus populaires, les 5 mappools les plus récents, ainsi que leurs maps respectifs ET le mode
         */

        $popular_mappools = $mappoolRepository->findByPopularity();
        $recent_mappools = $mappoolRepository->findByMostRecent();
        $mappools = [$popular_mappools, $recent_mappools];

        for ($i = 0; $i <= 1; $i++) {
            foreach ($mappools[$i] as $mappool) {
                $pool_maps = $mappoolMapRepository->findBy(['mappool' => $mappool]);
                $maps = [];
                foreach ($pool_maps as $pool_map) {
                    $id = $pool_map->getBeatmap()->getId();
                    array_push($maps,[$beatmapRepository->findBy(['id' => $id]), $pool_map->getMode()]);
                }
                $mappool->maps = $maps ;
            }
        }

        return $this->render('/page/home.html.twig', [
            'popular_mappool' => $mappools[0],
            'recent_mappool' => $mappools[1]
        ]);
    }


}
