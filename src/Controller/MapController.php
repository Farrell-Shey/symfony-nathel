<?php

namespace App\Controller;

use App\Entity\Beatmap;
use App\Entity\Beatmapset;
use App\Entity\Mappool;
use App\Entity\MappoolMap;
use App\Entity\Tag;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Service\OsuApiService;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
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
        // Fonction à n'utiliser qu'en dernier recours
    /**
     * @Route("update_sr", name="update_sr", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @param bool $replace
     * @return JsonResponse
     */
    public function updateSR(EntityManagerInterface $em, Request $request, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr, $replace = False)
    {

        $maps = $bmr->findAll();
        foreach($maps as $map){
            $ch = strrev($map->getUrl());
            $map_id = '';
            for($i = 0;is_numeric($ch[$i]) == true; $i++){
                $map_id = $map_id . (string) $ch[$i];
            }

            $map_id = strrev($map_id);

        try {
            $map_data = $api->getBeatmapInfo($map_id);

        } catch (Exception $e) {
            dump($e);
        }
        $map->setStarRating($map_data['difficulty_rating']);
        $em->persist($map);
        $em->flush();



        }
        dd('Update effectué !');

    }

    /**
     * @Route("add_map", name="add_map", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param PoolSetRepository $pr
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @param bool $replace
     * @return JsonResponse
     */
    public function addMap(EntityManagerInterface $em, Request $request, TagRepository $tr, PoolSetRepository $pr, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr, $replace = False)
    {

        $user = $this->security->getUser();
        if ($replace == False){
            $data = $request->request;
            $pool_id = $data->get('form')['id'];
            $mappool = $mr->findOneBy(['id' => $pool_id]);
            $link = $data->get('form')['addmap'];
        }
        else {
            $data = $request->getContent();
            $data = explode('§', $data);
            $pool_id = $data[0];
            $mappool = $mr->findOneBy(['id' => $pool_id]);
            $link = $data[1];

            $old_link = $data[2];


            //traitement du old link
            if (is_numeric($old_link)) {
                $map_id = $old_link;
            } elseif (str_contains($old_link, 'https') == true) {

                $ch = strrev($old_link);
                $map_id = '';
                for ($i = 0; is_numeric($ch[$i]) == true; $i++) {
                    $map_id = $map_id . (string)$ch[$i];
                }
                $map_id = strrev($map_id);

                $map_data = $api->getBeatmapInfo($map_id);

                $mapsets = $bmsr->findBy(['name' => $map_data['beatmapset']['title']]);

                foreach($mapsets as $value){
                    if ($value->getCreator() == $map_data['beatmapset']['creator']){
                        $bmmapset = $value;
                    }
                }

                if (!isset($bmmapset)){
                    return new JsonResponse(['false', 1]);
                }

                $maps = $bmr->findBy(['beatmapset' => $bmmapset]);
                foreach ($maps as $value){
                    if($value->getDifficulty() == $map_data['version']){
                        $tmp_map = $value;
                    }
                }

                $mmaps = $mmr->findBy(['beatmap' => $tmp_map]);
                foreach ($mmaps as $value){
                    if ($value->getMappool()->getId() === $mappool->getId()){
                        $old_mmap = $value;
                    }
                }


            } else {

                return new JsonResponse(['false', '2']);
            }
        }





        // Traitement pour récupérer l'ID de la map
        if (is_numeric($link)){
            $map_id = $link;
        }elseif (str_contains($link, 'https') == true){

            $ch = strrev($link);
            $map_id = '';
            for($i = 0;is_numeric($ch[$i]) == true; $i++){
                $map_id = $map_id . (string) $ch[$i];
            }
            $map_id = strrev($map_id);

        }else{

            return new JsonResponse(['false', '3']);
        }


        try {
            $map_data = $api->getBeatmapInfo($map_id);
        } catch (Exception $e) {
            return new JsonResponse(['false', '4']);
        }




        if ($replace == true && isset($map_data['beatmaps'])){

            return new JsonResponse(['false', '5']);
        }

        $mapsets = $bmsr->findBy(['name' => $map_data['beatmapset']['title']]);

        foreach($mapsets as $value){
            if ($value->getCreator() == $map_data['beatmapset']['creator']){
                $mapset = $value;
                }
        }


        // Insert du beatmapset
        if (!isset($mapset)){
            $mapset = new Beatmapset();
            $mapset->setCover($map_data['beatmapset']['covers']['cover']);
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

        if (count($mmr->findByMapAndMappool($map, $mappool)) > 0 ){
            return new JsonResponse(['false','7']);
        }

        // UPDATE SR AVERAGE
        $tmp_col = $mappool->getPoolSet();
        $tmp_pools = $mr->findBy(['poolSet' => $tmp_col]);
        $srs = [];

        foreach ($tmp_pools as $tmp_pool){
            $tmp_mmaps = $mmr->findBy(['mappool' => $tmp_pool]);

            foreach($tmp_mmaps as $tmp){

                array_push($srs, $tmp->getBeatmap()->getStarRating());
            }
        }
        if(count($srs)) {
            echo $average = round(array_sum($srs)/count($srs),2);
        }else{
            $average = 0;
        }
        $tags = $tr->findByPoolset($tmp_col->getId());
        foreach($tags as $tatag){
            if ($tatag->getType() == 'star_rating'){
                $tag = $tatag;
            }
        }
        if (isset($tag)){
            $tag->setName($average);
        }else{
            $tag = new Tag();
            $tag->setType('star_rating');
            $tag->setName($average);
            $tag->addPoolSet($tmp_col);
        }
        $em->persist($tag);
        $em->flush();
        // FIN UPDATE SR




        // Insert de la relation Mappool - Map
        if (!isset($mmap) && $replace == False){
            $mmap = new MappoolMap();
            $mmap->setBeatmap($map);
            $mmap->setMappool($mappool);
            $mmap->setMode('NM');
            $mmap->setUser($user);
            $em->persist($mmap);
            $em->flush();
        }elseif( $replace == true){


            if (!isset($old_mmap)){
                return new JsonResponse(['false','6']);
            }
            $old_mmap->setBeatmap($map);
            $em->persist($old_mmap);
            $em->flush();

            $beatmapset_id = $map->getBeatmapset()->getId();

            $beatmapset = $bmsr->findOneBy(['id' => $beatmapset_id]);
            $name = $beatmapset->getArtist() . ' - ' . $beatmapset->getName() . ' [' . $map->getDifficulty() . ']';
            $arrData = [];
            $arrData['name'] = $name;
            $arrData['creator'] = $beatmapset->getCreator();
            $arrData['cover'] = $beatmapset->getCover();
            $arrData['url'] = $map->getUrl();
            $arrData['cs'] = $map->getCs();
            $arrData['bpm'] = $map->getBpm();
            $arrData['ar'] = $map->getAr();
            $arrData['drain'] = $map->getDrain();
            $arrData['accuracy'] = $map->getAccuracy();
            $arrData['id'] = $map->getId();
            $arrData['poolid'] = $mappool->getId();
            return new JsonResponse($arrData);

        }

        $name = $mapset->getArtist() . ' - ' . $mapset->getName() . ' [' . $map->getDifficulty() . ']';
        $arrData = [];
        $arrData['name'] = $name;
        $arrData['creator'] = $mapset->getCreator();
        $arrData['cover'] = $mapset->getCover();
        $arrData['url'] = $map->getUrl();
        $arrData['cs'] = $map->getCs();
        $arrData['bpm'] = $map->getBpm();
        $arrData['ar'] = $map->getAr();
        $arrData['drain'] = $map->getDrain();
        $arrData['accuracy'] = $map->getAccuracy();
        $arrData['mode'] = $mmap->getMode();
        $arrData['id'] = $map->getId();
        $arrData['poolid'] = $mappool->getId();;
        return new JsonResponse($arrData);
    }


    /**
     * @Route("replace_map", name="replace_map", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @return JsonResponse
     */
    public function replaceMap(EntityManagerInterface $em,PoolSetRepository $pr, TagRepository $tr, Request $request, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr)
    {
        return $this->addMap($em, $request, $tr, $pr, $api, $mr, $bmsr,  $bmr, $mmr, true);
    }

    /**
     * @Route("refresh_mode", name="refresh_mode", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @return JsonResponse
     */
    public function refreshMode(EntityManagerInterface $em, Request $request, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr)
    {
        $data = $request->getContent();
        $data = explode('§', $data);
        $pool_id = $data[0];
        $mappool = $mr->findOneBy(['id' => $pool_id]);
        $mode = $data[1];
        $map_id = $data[2];
        $map = $bmr->findBy(['id' => $map_id]);

        $mmaps = $mmr->findBy(['beatmap' => $map]);
        foreach ($mmaps as $value){
            if ($value->getMappool()->getId() === $mappool->getId()){
                $mmap = $value;
                $mmap->setMode($mode);
                $em->flush();
            }
        }



        return new JsonResponse([True]);
    }

    /**
     * @Route("delete_map", name="delete_map", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param OsuApiService $api
     * @param MappoolRepository $mr
     * @param BeatmapsetRepository $bmsr
     * @param BeatmapRepository $bmr
     * @param MappoolMapRepository $mmr
     * @return JsonResponse
     */
    public function deleteMap(EntityManagerInterface $em, Request $request, OsuApiService $api, MappoolRepository $mr, BeatmapsetRepository $bmsr, BeatmapRepository $bmr, MappoolMapRepository $mmr){
        $data = $request->getContent();
        $data = explode('_',$data);
        $pool_id = $data[0];
        $map_id = $data[1];

        $pool = $mr->findBy(['id' => $pool_id]);

        $pool_maps = $mmr->findBy(['mappool' => $pool]);

        foreach ($pool_maps as $pool_map) {
            $id = $pool_map->getBeatmap()->getId();
            if ($id == $map_id){
                $map = $bmr->findOneBy(['id' => $id]);
                $mmaps = $mmr->findBy(['beatmap' => $map]);
                foreach ($mmaps as $value){

                    if ($value->getMappool()->getId() == $pool_id){

                        $em->remove($value);
                        $em->flush();
                        $arrData = [];
                        return new JsonResponse($arrData);

                    }
                }
            }
        }

        $arrData = [];
        return new JsonResponse($arrData);

    }
}
