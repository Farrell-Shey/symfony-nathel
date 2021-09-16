<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Form\MappoolType;
use App\Repository\TagRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
     * @return Response
     * @Route("/collection/search", name="search_collection", methods={"GET", "POST"})
     */
    public function SearchCollectionPage(TransformedFinder $poolsetFinder, Request $request, TagRepository $tr): Response
    {
        $search = Util::escapeTerm("test");

        $result = $poolsetFinder->findHybrid($search, 10);

        dd($result);

        // Search

        $form = $this->createFormBuilder()
            ->add('title', TextType::class,
                ['label' => 'Collection Title']
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
            ->add('image', FileType::class)
            ->add('submit', SubmitType::class, ['label' => 'Save Collection']);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ){
            $data = $form->getData();
            // Check


        }

        return $this->render('search/index.html.twig',
            ['formulaire' => $form->createView()
            ]);

    }
}
