<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Form\MappoolType;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\ContributorRepository;
use App\Repository\MappoolMapRepository;
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
     * @Route("/search_load_results", name="search_load_results")
     */
    public function search_load_results(TransformedFinder $poolsetFinder, $content, Request $request, TagRepository $tr, PoolSetRepository $pr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr, PoolSetController $psc)
    {
        // Normalize Table
        /**
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
        }**/
        $data = $content;


        $search = Util::escapeTerm($data['title']);

        $json_results = $poolsetFinder->find('*' . $search . '*');

        $collections = $this->getCollections($this->getCollectionIds($data, $json_results, $tr), $mmr, $br, $bmsr, $request,$psc, $pr, $tr, $cr, $ur, $mr);

        if (isset(end($collections)['poolset'])){
            $last_id = end($collections)['poolset']->getId();
        }


        $arrData = [];
        $arrData['collections'] = $collections;
        $arrData['scrollable'] = False;
        if (count($collections) > 10){
            $arrData['scrollable'] = True;
            $arrData['last_id'] = $last_id;
        };



        return $arrData;
        //return new JsonResponse($arrData);

    }

    /**
     * @param TransformedFinder $poolsetFinder
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @param ContributorRepository $cr
     * @param UserRepository $ur
     * @param MappoolRepository $mr
     * @return Response|array
     * @Route("/collection/search", name="search_collection", methods={"GET", "POST"})
     */
    public function SearchCollectionPage(TransformedFinder $poolsetFinder, Request $request, TagRepository $tr, PoolSetRepository $pr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr, PoolSetController $psc): Response
    {

        // SEARCH FORM PART

        $form = $this->createFormBuilder()
            ->setMethod('GET')
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
            ->add('rank_min', HiddenType::class, ['attr' =>['value'  => '0']])
            ->add('rank_max', HiddenType::class, ['attr' =>['value'  => '500000']]);
        $form = $form->getForm();

        $form->handleRequest($request);



        if ($form->isSubmitted() && $form->isValid() ){
            //return $this->redirectToRoute('search_load_results');

            $results = $this->search_load_results($poolsetFinder,$form->getData(),$request,$tr,$pr,$cr,$ur,$mr,$mmr, $br,$bmsr,$psc);
            return $this->render('page/search-page.html.twig',
                ['formulaire' => $form->createView(),
                'results' => $results,
                    'data' => $form->getData()]);
        }


        return $this->render('page/search-page.html.twig',
            ['formulaire' => $form->createView()
            ]);

    }



    public function getCollectionIds($data, $json_results, TagRepository $tr){
        $i = 0;
        $ids = [];

        foreach($json_results as $result){
            $check_tag = True;
            $current_id = $result->getId();
            if ($i>=10){
                break;
            }

            $pool_tags = $tr->findByPoolset($current_id);

            $tag_names = [];
            $ranks = [];
            $tag_true = [];
            //Get POOL TAG
            foreach ($pool_tags as $pool_tag){
                array_push($tag_names, str_replace(' ', '_', $pool_tag->getName()));
                if ($pool_tag->getType() == 'rank_min'){
                    array_push($ranks,(int) ltrim(str_replace(' ', '', $pool_tag->getName()), '#'));
                }else if ($pool_tag->getType() == 'rank_max'){
                    array_push($ranks,(int) ltrim(str_replace(' ', '', $pool_tag->getName()), '#'));
                }
            }


            // GET TAGS TRUE
            foreach($data as $data_name => $data_value){
                if($data_name !='title' && $data_name !='rank_min' && $data_name != 'rank_max'){
                    if($data_value == true){


                        array_push($tag_true,$data_name);
                    }
                }else if ($data_name =='rank_min'){

                    if(isset($ranks[0]) && isset($ranks[1])){
                        if ($ranks[0] > (int) ltrim(str_replace(' ', '', $data_value),'#')){


                            $check_tag = false;
                        }
                    }
                }else if ($data_name =='rank_max'){

                    if(isset($ranks[0]) && isset($ranks[1])){
                        if ($ranks[1] < (int) ltrim(str_replace(' ', '', $data_value),'#')){

                            $check_tag = false;
                        }
                    }
                }
            }



            foreach($tag_true as $true){
                if(!in_array($true,$tag_names)){
                    $check_tag = false;
                }
            }

            if ($check_tag == True){
                array_push($ids, $current_id);
                $i++;
            }

        }


        return $ids;
    }

    public function getCollections(array $ids, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr, Request $request, PoolSetController $psc, PoolSetRepository $pr, TagRepository $tr, ContributorRepository $cr, UserRepository $ur, MappoolRepository $mr){
        $collections = [];
        foreach($ids as $id){
            // Instanciation de la collection, des tags et des contributors
            $collection = $psc->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);
            if (isset($collection['mappools'][0])){
                $collection['pool'] = $collection['mappools'][0];
                array_push($collections, $collection);
            }else{
                $collection = [];
            }




        }

        return $collections;

    }
}
