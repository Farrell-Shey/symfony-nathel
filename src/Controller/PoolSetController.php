<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Entity\PoolSet;
use App\Entity\Tag;
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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/collection")
 */
class PoolSetController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    // Fonction de routes :


    /**
     * @param EntityManagerInterface $em
     * @param ContributorRepository $cr
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @return Response
     * @Route("/manage", name="manage_collection", methods={"GET", "POST"})
     */
    public function manage(EntityManagerInterface $em, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr): Response
    {
        // Vérif connexion, ou renvoi directe sur page connexion.
        /*
        // TESTS
        $cc = $this->getDoctrine()->getRepository(PoolSet::class);
        $tg = $this->getDoctrine()->getRepository(Tag::class);
        $col = $cc->findOneBy(['name' => 'Juste un peu']);

        $tag = $tg->findByPoolset($col->getId());

        dd($tag);
        */


        // MANAGE MY POOLS PART

        // Instanciation des collections
        $user = $this->security->getUser();
        $contributors = $cr->findBy(['user' => $user]);
        $collections = [];
        foreach($contributors as $contributor){
            $id = $contributor->getPoolSet()->getId();
            $poolset = $pr->findById($id);
            $collection = ['poolset' => $poolset,
                'tags' => $tr->findByPoolset($id),
                'contributors' => $cr->findBy(['poolSet' => $poolset])];

            array_push($collections, $collection);
        }






        // MODAL PART

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
            ->add('image', FileType::class)
            ->add('submit', SubmitType::class, ['label' => 'Save Collection']);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ){
            $data = $form->getData();
            // Check
            if (gettype($data['title']) == 'string' || strlen($data['title'] <=50)){
                $background_path = $this->uploadBackground($data['image']);
                $this->saveCollection($data, $background_path, $tr, $pr);
                }
            else{
                $error = 'Erreur de donnée pour le champ title ou Collection';
                dd($error);
            }

        }

        return $this->render('pool_set/index.html.twig',
            ['formulaire' => $form->createView()
            ]);


    }

    /**
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
     * @return JsonResponse
     * @Route("edit_save", name="edit_save", methods={"GET", "POST"})
     * @Route("/{id}/edit", name="edit_collection", methods={"GET", "POST"})
     */
    public function  edit_save(BeatmapsetRepository $bmsr, MappoolMapRepository $mmr, BeatmapRepository $br, MappoolRepository $mr, UserRepository $ur, EntityManagerInterface $em, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr): JsonResponse
    {

        // Normalize Table
        $data = (array) json_decode($request->getContent());
        $data = (array) $data['input'];

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

        $id = $data['id'];


       $collection = $this->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);



        $arrData = [];
        $arrData['title'] = $data['title'];
        $arrData['id'] = $id;
        $arrData['rank_min'] = $data['rank_min'];
        $arrData['rank_max'] = $data['rank_max'];
        $arrData['range_min'] = $data['range_min'];
        $arrData['range_max'] = $data['range_max'];
        $arrData['std'] = $data['std'];
        $arrData['mania'] = $data['mania'];
        $arrData['taiko'] = $data['taiko'];
        $arrData['ctb'] = $data['ctb'];
        $arrData['fun'] = $data['fun'];
        $arrData['challenge'] = $data['challenge'];
        $arrData['tournament'] = $data['tournament'];
        $arrData['training'] = $data['training'];
        $arrData['pp_farm'] = $data['pp_farm'];
        $arrData['image'] = 'test';
        dd($data['image']);
        $this->saveCollection($arrData, $arrData['image'],$tr, $pr, true);

        return new JsonResponse($arrData);

    }

    /**
     * * @Route("/{id}/edit", name="edit_collection", methods={"GET", "POST"})
     * @param int $id
     * @param TagRepository $tr
     * @param Request $request
     * @param PoolSetRepository $pr
     * @param UserRepository $ur
     * @param ContributorRepository $cr
     * @param MappoolRepository $mr
     * @param MappoolMapRepository $mmr
     * @param BeatmapRepository $br
     * @param BeatmapsetRepository $bmsr
     * @return Response
     */
    public function editView(int $id, TagRepository $tr, Request $request, PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr): Response
    {

        // INSTANCIATION DE LA COLLECTION

        $collection = $this->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);

        // CREATION DU FORMULAIRE

        $form = $this->createFormBuilder()
            ->setMethod('GET')
            //->setAction('/owo')
            ->add('title', TextType::class,
                ['label' => 'Collection Title', 'required' => False, 'data' => $collection['poolset']->getName()]
            );

        $mods = $tr->findBy(['type' => 'gamemod']);
        foreach ($mods as $mod){
            $name = str_replace(' ', '_', $mod->getName());
            if (in_array($name, $collection['tag_names']['mod'])){
                $form = $form->add($name, CheckboxType::class, ['required' => False, 'attr' => ['checked' => true]]);
            }else{
                $form = $form->add($name, CheckboxType::class, ['required' => False]);
            }

        }

        $categories = $tr->findBy(['type'=> 'category']);

        foreach ($categories as $category){
            $name = str_replace(' ', '_', $category->getName());
            if (in_array($name, $collection['tag_names']['category'])){
                $form = $form->add($name, CheckboxType::class, ['required' => False, 'attr' => ['checked' => true]]);
            }else{
                $form = $form->add($name, CheckboxType::class, ['required' => False]);
            }
        }

        $form = $form
            ->add('range_min', HiddenType::class, ['data'=> $collection['tag_names']['range_min']])
            ->add('range_max', HiddenType::class, ['data'=> $collection['tag_names']['range_max']])
            ->add('rank_min', HiddenType::class, ['data'=> $collection['tag_names']['rank_min']])
            ->add('rank_max', HiddenType::class, ['data'=> $collection['tag_names']['rank_max']])
            ->add('id', HiddenType::class, ['data'=> $id])
            ->add('image', FileType::class, ['required' => false, 'attr' => ['value' => 'blaaabluh']])
            ->add('submit', SubmitType::class, ['label' => 'Save Collection']);
        $form = $form->getForm();

        $form->handleRequest($request);

        return $this->render('zone_test_nath/edit.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @param int $id
     * @param TagRepository $tr
     * @param Request $request
     * @param PoolSetRepository $pr
     * @param UserRepository $ur
     * @param ContributorRepository $cr
     * @param MappoolRepository $mr
     * @param MappoolMapRepository $mmr
     * @param BeatmapRepository $br
     * @param BeatmapsetRepository $bmsr
     * @return array
     */
    public function getCollection(int $id, TagRepository $tr, Request $request, PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr): array
    {

        // Instanciation de la collection, des tags et des contributors
        $user = $this->security->getUser();
        $poolset = $pr->findOneBy(['id' => $id]);

        $tags = $tr->findByPoolset($id);
        // GET LA LISTE DE NAME DES TAGS BRUH
        $tag_names = ['mod' => [], 'category' => [], 'rank_min' => null, 'rank_max' => null, 'range_min' => null, 'range_max' => null];
        foreach($tags as $tag){
            if ($tag->getType() == 'gamemod'){
                array_push($tag_names['mod'], $tag->getName());
            }else if ($tag->getType() == 'category'){
                array_push($tag_names['category'], $tag->getName());
            }else if ($tag->getType() == 'rank_min'){
                $tag_names['rank_min'] = $tag->getName();
            }else if ($tag->getType() == 'rank_max'){
                $tag_names['rank_max'] = $tag->getName();
            }else if ($tag->getType() == 'range_min'){
                $tag_names['range_min'] = $tag->getName();
            }else if ($tag->getType() == 'range_max'){
                $tag_names['range_max'] = $tag->getName();
            }

        }

        $contributors = $cr->findBy(['poolSet' => $poolset]);
        foreach ($contributors as $contributor) {
            if ($contributor->getIsCreator() == True){
                [ 'info' => $contributors_info = $ur->findById($contributor->getId()), 'creator' => 1];
            }else{
                $contributors_info = $ur->findById($contributor->getId());
            }
        }

        //Instanciation des mappools et des maps

        $mappools = $mr->findBy(['poolSet' => $poolset]);

        foreach ($mappools as $key => $mappool) {
            $pool_maps = $mmr->findBy(['mappool' => $mappool]);
            $maps = [];

            foreach ($pool_maps as $pool_map) {
                $id = $pool_map->getBeatmap()->getId();

                $map = $br->findOneBy(['id' => $id]);

                $beatmapset_id = $map->getBeatmapset()->getId();

                $beatmapset = $bmsr->findOneBy(['id' => $beatmapset_id]);
                $name = $beatmapset->getArtist() . ' - ' . $beatmapset->getName() . ' [' . $map->getDifficulty() . ']';
                array_push($maps,['name ' =>$name, 'map' =>$map, 'mode' =>$pool_map->getMode()]);
            }
            $mappools[$key]['maps'] = $maps ;
        }
        return ['poolset' => $poolset, 'tags' => $tags, 'contributors' => $contributors, 'mappools' => $mappools, 'tag_names' => $tag_names];
    }







    // Fonctions sans routes

    public function saveCollection($data, $background, TagRepository $tr, PoolSetRepository $pr,$edit=false){


        $em = $this->getDoctrine()->getManager();
        $tg = $this->getDoctrine()->getRepository(Tag::class);

        $user = $this->security->getUser();

        $tag_rank_min = $tg->findOneBy(['name' => $data['rank_min']]);
        $tag_rank_max = $tg->findOneBy(['name' => $data['rank_max']]);
        $tag_range_min = $tg->findOneBy(['name' => $data['range_min']]);
        $tag_range_max = $tg->findOneBy(['name' => $data['range_max']]);
        //Save des tags de RANK SI BESOIN
        if(empty($tag_rank_min))
        {
            $tag_rank_min = new Tag();
            $tag_rank_min->setName($data['rank_min']);
            $tag_rank_min->setType('rank_min');
            $em->persist($tag_rank_min);
            $em->flush();
        }
        if(empty($tag_rank_max))
        {
            $tag_rank_max = new Tag();
            $tag_rank_max->setName($data['rank_max']);
            $tag_rank_max->setType('rank_max');
            $em->persist($tag_rank_max);
            $em->flush();
        }
        // Save des tags de RANGE SI BESOIN
        if(empty($tag_range_min))
        {
            $tag_range_min = new Tag();
            $tag_range_min->setName($data['range_min']);
            $tag_range_min->setType('range_min');
            $em->persist($tag_range_min);
            $em->flush();
        }
        if(empty($tag_range_max))
        {
            $tag_range_max = new Tag();
            $tag_range_max->setName($data['range_max']);
            $tag_range_max->setType('range_max');
            $em->persist($tag_range_max);
            $em->flush();
        }


        //Save de la collection en BDD
        if ($edit == false){
            $collection = new PoolSet();
            $collection->setCreatedAt(new \DateTime('now'));
        }else{

            // Destruction des tags existants
            $tags = $tr->findByPoolset($data['id']);
            $collection = $pr->findOneBy(['id' => $data['id']]);

            foreach ($tags as $tag){
                $collection->removeTag($tag);
            }

        }

        $collection->setName($data['title']);
        $collection->setThumbnail($background);
        $collection->setUpdatedAt(new \DateTime('now'));


        // Save des relations TAG - COLLECTION

        $collection->addTag($tag_rank_min);
        $collection->addTag($tag_rank_max);
        $collection->addTag($tag_range_min);
        $collection->addTag($tag_range_max);

        foreach($data as $data_name => $data_value){
            if ($data_name != 'title'
                && $data_name != 'range_min'
                && $data_name != 'range_max'
                && $data_name != 'rank_min'
                && $data_name != 'rank_max'
                && $data_name != 'image'
                && $data_name != 'id'
                && $data_value == True){
                $tmp = str_replace(' ', '_', $data_name);

                $tmp = $tg->findOneBy(['name' => $tmp]);


                $collection->addTag($tmp);

            }

        }

        $em->persist($collection);

        $em->flush();

        if ($edit == false){
            //Save du contributor
            $contributor = new Contributor();
            $contributor->setUser($user);
            $contributor->setPoolSet($collection);
            $contributor->setIsCreator(True);
            $em->persist($contributor);
            $em->flush();
        }


    }

    public function uploadBackground($background)
    {
        #Contraintes attendues :
        $dossier = './build/images/user/';
        $taille_maxi = (2**20)*10;
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');

        #Variables du fichier :
        $extension = strrchr($background->getClientOriginalName(), '.');

        // Si pas d'images donnée
        if ($background->getClientOriginalName() == null){
            return False;
        }
        $taille = $background->getSize();
        $fichier = basename($background->getClientOriginalName());
        $name = self::random(30) . $extension;


        //Vérifications de sécurité

        if(!in_array($extension, $extensions)) //extension verif
        {
            $erreur = 'problème d\'extension frérot';
            dd($erreur);
        }
        if($taille>$taille_maxi) // size verif
        {
            $erreur = 'Le fichier est obèse....';
            dd($erreur);
        }

        if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
        {
            //dd(scandir($dossier));

            if($background->move($dossier . $name)) // Upload
            {
                //echo "l'upload a marché trop bien ";
                return $dossier . $name;
            }
            else //Sinon (la fonction renvoie FALSE).
            {
                //echo 'Echec de l\'upload !';
                return False;
            }
        }
        else
        {
            //echo $erreur;
            return False;
        }
    }


    public static function random($car): string
    {
        $string = "";
        $chaine = "abcdefghijklmnpqrstuvwxy";
        srand((double)microtime()*1000000);
        for($i=0; $i<$car; $i++) {
            $string .= $chaine[rand()%strlen($chaine)];
        }
        return $string;
    }


    public function saveb(EntityManagerInterface $em){


    }




}
