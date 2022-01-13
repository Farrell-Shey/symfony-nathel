<?php

namespace App\Controller;

use App\Entity\PoolSet;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\ContributorRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Service\OsuApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Collection;

class HomeController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="app_home")
     * @param OsuApiService $osuApiService
     * @param BeatmapsetRepository $bmsr
     * @param MappoolMapRepository $mmr
     * @param BeatmapRepository $br
     * @param MappoolRepository $mr
     * @param UserRepository $ur
     * @param EntityManagerInterface $em
     * @param ContributorRepository $cr
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @return Response
     */
    public function index(OSuApiService $osuApiService, BeatmapsetRepository $bmsr, MappoolMapRepository $mmr, BeatmapRepository $br, MappoolRepository $mr, UserRepository $ur, EntityManagerInterface $em, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr): Response
    {

        /*
         * Retourne les 5 mappools les plus populaires, les 5 mappools les plus récents, ainsi que leurs maps respectifs ET le mode
         */

        $popular_mappools = $mr->findByPopularity();
        $recent_mappools = $mr->findByMostRecent();
        $mappools = [$popular_mappools, $recent_mappools];
        $psc = new PoolSetController($this->security);

        $collections  = [];
        for ($i = 0; $i <= 1; $i++) {
            $col = [];
            foreach ($mappools[$i] as $mappool) {
                $id = $mappool->getPoolSet()->getId();
                $collection = $psc->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);

                foreach($collection['mappools'] as $pool){

                    if ($pool->getId() === $mappool->getId()){

                        $collection['pool'] = $pool;
                    }
                }
                array_push($col, $collection);
            }
            array_push($collections, $col);
        }



        return $this->render('/page/home.html.twig', [
            'popular_mappool' => $collections[0],
            'recent_mappool' => $collections[1]
        ]);
    }


}
