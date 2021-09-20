<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Form\MappoolType;
use App\Repository\ContributorRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use FOS\ElasticaBundle\Finder\TransformedFinder;
use Elastica\Util;


class SearchController extends AbstractController
{


    /**
     * @param TransformedFinder $poolsetFinder
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @param ContributorRepository $cr
     * @param UserRepository $ur
     * @param MappoolRepository $mr
     * @return JsonResponse|Response
     * @Route("/owo", name="owo")
     */
    public function owo(TransformedFinder $poolsetFinder, Request $request, TagRepository $tr, PoolSetRepository $pr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr)
    {
        // Normalize Table
        $data = (array) json_decode($request->getContent());
        foreach ($data as $key => $value){

            $key_tmp = str_replace('form[', '', $key);
            $key_tmp = str_replace(']', '', $key_tmp);
            $data[$key_tmp] = $value;
            unset($data[$key]);
        }

        // On rajoute à False les tags suppr par le form traitement
        $elements = ['title','std','taiko','mania','ctb','tournament','fun','training','challenge', 'pp_farm'];
        foreach ($elements as $element){
            if (!isset($data[$element])){
                if ($element == 'title'){
                    $data[$element] = '';
                }else{
                    $data[$element] = False;
                }

            }else{
                if ($element != 'title'){
                    $data[$element] = True;
                }
            }
        }


        $search = Util::escapeTerm($data['title']);

        $json_results = $poolsetFinder->find('*' . $search . '*');
        $collections = $this->getCollections($this->getCollectionIds($data, $json_results, $tr), $pr, $tr, $cr, $ur, $mr);
        $last_id = end($collections)['poolset']->getId();

        $arrData = [];
        $arrData['collections'] = $collections;
        $arrData['scrollable'] = False;
        if (count($collections) > 10){
            $arrData['scrollable'] = True;
            $arrData['last_id'] = $last_id;
        };

        return new JsonResponse($arrData);

    }

    /**
     * @param TransformedFinder $poolsetFinder
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @param ContributorRepository $cr
     * @param UserRepository $ur
     * @param MappoolRepository $mr
     * @return Response
     * @Route("/collection/search", name="search_collection", methods={"GET", "POST"})
     */
    public function SearchCollectionPage(TransformedFinder $poolsetFinder, Request $request, TagRepository $tr, PoolSetRepository $pr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr): Response
    {


        // Search

        $form = $this->createFormBuilder()
            ->setMethod('GET')
            //->setAction('/owo')
            ->add('title', TextType::class,
                ['label' => 'Collection Title', 'required' => False]
            );

        $mods = $tr->findBy(['type' => 'gamemod']);
        foreach ($mods as $mod){
            $name = str_replace(' ', '_', $mod->getName());
            $form = $form->add($name, CheckboxType::class, ['required' => False]);
        }

        $categories = $tr->findBy(['type'=> 'category']);

        foreach ($categories as $category){
            $name = str_replace(' ', '_', $category->getName());

            $form = $form->add($name, CheckboxType::class, ['required' => False]);
        }

        $form = $form
            ->add('range_min', HiddenType::class)
            ->add('range_max', HiddenType::class)
            ->add('rank_min', HiddenType::class)
            ->add('rank_max', HiddenType::class)
            ->add('submit', SubmitType::class, ['label' => 'Save Collection']);
        $form = $form->getForm();

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid() ){


            return $this->redirectToRoute('owo');

        }

        /**
        if ($form->isSubmitted() && $form->isValid() ){
            $data = $form->getData();
            dump($data);
            // Check
            $search = Util::escapeTerm($data['title']);

            $json_results = $poolsetFinder->find('*' . $search . '*');
            $collections = $this->getCollections($this->getCollectionIds($data, $json_results, $tr), $pr, $tr, $cr, $ur, $mr);
            $last_id = end($collections)['poolset']->getId();

        }**/

        return $this->render('search/index.html.twig',
            ['formulaire' => $form->createView()
            ]);

    }



    public function getCollectionIds($data, $json_results, TagRepository $tr){
        $i = 0;
        $ids = [];
        foreach($json_results as $result){
            $current_id = $result->getId();
            if ($i>=10){
                break;
            }

            $pool_tags = $tr->findByPoolset($current_id);
            $tags = [];

            foreach ($pool_tags as $pool_tag){
                array_push($tags, str_replace(' ', '_', $pool_tag->getName()));
            }



            foreach($data as $data_name => $data_value){
                $check_tag = True;


                if ($data_name != 'title'
                    && $data_name != 'range_min'
                    && $data_name != 'range_max'
                    && $data_name != 'rank_max'
                    && $data_name != 'rank_min'
                    && $data_name != 'image'
                    && $data_name != '_token'
                    && $data_value == True){

                    if (!in_array($data_name, $tags )){

                        $check_tag = False;

                        break;
                    }

                    if ($check_tag == False){

                        break;
                    }
                }


            }
            if ($check_tag == True){
                array_push($ids, $current_id);
                $i++;
            }


        }

        return $ids;
    }

    public function getCollections(array $ids, PoolSetRepository $pr, TagRepository $tr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr){
        $collections = [];
        foreach($ids as $id){
            // Instanciation de la collection, des tags et des contributors

            $poolset = $pr->findOneBy(['id' => $id]);
            $tags = $tr->findByPoolset($id);
            $contributors = $cr->findBy(['poolSet' => $poolset]);
            foreach ($contributors as $contributor) {
                if ($contributor->getIsCreator() == True){
                    [ 'info' => $contributors_info = $ur->findById($contributor->getId()), 'creator' => 1];
                }else{
                    $contributors_info = $ur->findById($contributor->getId());
                }
            }

            //Instanciation des mappools
            $mappools = $mr->findBy(['poolSet' => $poolset]);

            $collection = [
                'poolset' => $poolset,
                'tags' => $tags,
                'contributors' => $contributors,
                'mappools' => $mappools
            ];
            array_push($collections, $collection);


        }

        return $collections;

    }
}
