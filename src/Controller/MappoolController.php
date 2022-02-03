<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\ContributorRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MappoolController extends AbstractController
{


    /**
     * @Route("add_mappool", name="add_mappool", methods={"GET", "POST"})
     */
    public function  addMappool(EntityManagerInterface $em, Request $request, PoolSetRepository $pr): JsonResponse
    {
        $data = $request->request;
        $title = $data->get('form')['title'];
        $poolset = $pr->findOneBy(['id' => $data->get('form')['id']]);

        $mappool = new Mappool();

        $mappool->setName($title);
        $mappool->setCreatedAt(new \DateTime('now'));
        $mappool->setUpdatedAt(new \DateTime('now'));
        $mappool->setPoolSet($poolset);
        $mappool->setFollow(0);
        $em->persist($mappool);
        $em->flush();

        $arrData = [];
        $arrData['title'] = $mappool->getName();
        $arrData['id'] = $mappool->getId();

        return new JsonResponse($arrData);

    }

    /**
     * @Route("save_mappools", name="save_mappools", methods={"GET", "POST"})
     */
    public function  SaveMappools(EntityManagerInterface $em, Request $request, MappoolRepository $mr): JsonResponse
    {

        $request = $request->request->get('form');


        $arrData = [];
        $arrData['title'] = $request['title'];
        $arrData['id'] = $request['id'];
        $arrData['background'] = 'later for background I guess';

        $this->editMappool($arrData, $mr, $em);

        return new JsonResponse($arrData);

    }

    public function editMappool($data, MappoolRepository $mr, EntityManagerInterface $em){
        $mappool = $mr->findOneBy(['id' => $data['id']]);

        $mappool->setName($data['title']);
        $mappool->setThumbnail($data['background']);
        $mappool->setUpdatedAt(new \DateTime('now'));
        $em->persist($mappool);
        $em->flush();


    }

    /**
     * @Route("delete_pool", name="delete_pool", methods={"GET", "POST"})
     */
    public function  deletePool(EntityManagerInterface $em, Request $request, MappoolRepository $mr, MappoolMapRepository $mmr): JsonResponse
    {

        $id = preg_replace('/[^0-9.]+/', '', $request->getContent());
        $arrData = [];
        $arrData['delete'] = true;
        $arrData['id'] = $id;
        $pool = $mr->findOneById($id);
        foreach($mmr->findBy(['mappool' => $pool]) as $mmap){

            $em->remove($mmap);
        }
        $em->remove($pool);
        $em->flush();

        return new JsonResponse($arrData);

    }
}
