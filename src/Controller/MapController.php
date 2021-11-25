<?php

namespace App\Controller;

use App\Entity\Beatmap;
use App\Entity\Beatmapset;
use App\Entity\Mappool;
use App\Entity\MappoolMap;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Service\OsuApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class MapController extends AbstractController
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
     * @Route("add_map", name="add_map", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @return false|JsonResponse
     */
    public function addMap(EntityManagerInterface $em, Request $request, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr)
    {
        $data = $request->request;
        $pool_id = $data->get('form')['id'];
        $mappool = $mr->findOneBy(['id' => $pool_id]);

        $user = $this->security->getUser();
        $link = $data->get('form')['link'];

        // Traitement pour récupérer l'ID de la map
        if (is_numeric($link)){
            $map_id = $link;
        }elseif (strpos($link, 'https') != False){
            $ch = strrev($link);
            $map_id = '';
            for($i = 0;is_numeric($ch[$i]) == true; $i++){
                $map_id = $map_id . (string) $ch[$i];
            }
            $map_id = strrev($map_id);

        }else{
            return new JsonResponse(['false']);
        }
        $map_data = $api->getBeatmapInfo(341072);

        $mapsets = $bmsr->findBy(['name' => $map_data['beatmapset']['title']]);
        foreach($mapsets as $value){
            if ($value->getCreator() == $map_data['beatmapset']['creator']){
                $mapset = $value;
                }
        }

        // Insert du beatmapset
        if (!isset($mapset)){
            $mapset = new Beatmapset();
            $mapset->setName($map_data['beatmapset']['title']);
            $mapset->setCreator($map_data['beatmapset']['creator']);
            $mapset->setArtist($map_data['beatmapset']['artist']);

            $em->persist($mapset);
            $em->flush();
        }


        $maps = $bmr->findBy(['beatmapset' => $mapset]);
        foreach ($maps as $value){
            if($value->getDifficulty() == $map_data['version']){
                $map = $value;
            }
        }
        //Insert du beatmap
        if (!isset($map)){
            $map = new Beatmap();
            $map->setAccuracy($map_data['accuracy']);
            $map->setAr($map_data['ar']);
            $map->setBpm($map_data['bpm']);
            $map->setCs($map_data['cs']);
            $map->setDifficulty($map_data['version']);
            $map->setDrain($map_data['drain']);
            $map->setHitLength($map_data['hit_length']);
            $map->setModeInt($map_data['mode_int']);
            $map->setUrl($map_data['url']);
            $map->setBeatmapset($mapset);

            $em->persist($map);
            $em->flush();
        }



        $mmaps = $mmr->findBy(['beatmap' => $map]);
        foreach ($mmaps as $value){
            if ($value->getMappool()->getId() === $mappool->getId()){
                $mmap = $value;
            }
        }
        // Insert de la relation Mappool - Map
        if (!isset($mmap)){
            $mmap = new MappoolMap();
            $mmap->setBeatmap($map);
            $mmap->setMappool($mappool);
            $mmap->setMode('NM');
            $mmap->setUser($user);
            $em->persist($mmap);
            $em->flush();
        }


        $arrData = [];
        $arrData['title'] = $mapset->getName();
        $arrData['creator'] = $mapset->getCreator();
        $arrData['artist'] = $mapset->getArtist();
        $arrData['url'] = $map->getUrl();
        $arrData['difficulty'] = $map->getDifficulty();
        $arrData['cs'] = $map->getCs();
        $arrData['bpm'] = $map->getBpm();
        $arrData['ar'] = $map->getAr();
        $arrData['drain'] = $map->getDrain();
        $arrData['accuracy'] = $map->getAccuracy();
        $arrData['hit_length'] = $map->getHitLength();
        $arrData['mode'] = $mmap->getMode();

        return new JsonResponse($arrData);
    }
}
