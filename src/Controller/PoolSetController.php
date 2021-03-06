<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Entity\Invitation;
use App\Entity\MappoolFollowed;
use App\Entity\PoolSet;
use App\Entity\Tag;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\ContributorRepository;
use App\Repository\InvitationRepository;
use App\Repository\MappoolFollowedRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use function Sodium\randombytes_random16;

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
     * @param int $id
     * @param EntityManagerInterface $em
     * @param ContributorRepository $cr
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @param MappoolRepository $mr
     * @return Response
     * @Route("/view/{id}", name="index_collection", methods={"GET", "POST"})
     */

    public function index(int $id, TagRepository $tr, Request $request, PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr): Response
    {

        $col = $this->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);
        $col['tag_names']['rank'] = "#".$col['tag_names']['rank_min'] . " - " ."#". $col['tag_names']['rank_max'];
        $nb_maps = 0;
        foreach($col['mappools'] as $pool ){
            $nb_maps+= count($pool->maps);
        }
        $col['nb_maps'] = $nb_maps;







        return $this->render('/page/collection-page.html.twig', ['col' => $col, 'deletecontributor' => true]);

    }








    /**
     * @param EntityManagerInterface $em
     * @param ContributorRepository $cr
     * @param Request $request
     * @param TagRepository $tr
     * @param PoolSetRepository $pr
     * @return Response
     * @Route("/manage", name="manage_collection", methods={"GET", "POST"})
     */
    public function manage(EntityManagerInterface $em, InvitationRepository $ir, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr, MappoolRepository $mr): Response
    {
        // V??rif connexion, ou renvoi directe sur page connexion.
        $user = $this->security->getUser();

        if ($user === null){
            return $this->redirect('https://osu.ppy.sh/oauth/authorize?response_type=code&client_id=8955&redirect_uri=https://nathel.wip/connexion&scope=public');
        }
        // MANAGE MY POOLS PART

        // Instanciation des collections



        $contributors = $cr->findBy(['user' => $user]);

        $collections = [];
        $invitation = $ir->findBy(['user' => $user]);

        foreach($contributors as $contributor){
            $id = $contributor->getPoolSet()->getId();
            $poolset = $pr->findOneBy(['id' =>$id]);

            $tags = $this->adaptTags($tr->findByPoolset($id));

            $tmp_user = [];
            $tmp_user['id'] = $user->getOsuid();
            $tmp_user['cover'] = $user->getThumbnail();
            $tmp_user['creator'] = $contributor->getIsCreator();


            $collection = ['poolset' => $poolset,
                'tags' => $tags,
                'contributors' => $cr->findBy(['poolSet' => $poolset]),
                'nb_pools' => count($mr->findBy(['poolSet' => $poolset])),
                'user'=>$tmp_user
            ];

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
            ->add('rank_min', HiddenType::class, ['data' =>'0'])
            ->add('rank_max', HiddenType::class, ['data' =>'500000'])
            ->add('image', FileType::class, ['required' => false])
            ->add('submit', SubmitType::class);
        $form = $form->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() ){


            $data = $form->getData();
            // Check
            if (gettype($data['title']) == 'string' || strlen($data['title'] <=50)){
                $background_path = $this->uploadBackground($data['image']);
                $this->saveCollection($data, $background_path, $tr, $pr);
                return $this->redirect($request->getUri());

                }
            else{
                $error = 'Erreur de donn??e pour le champ title ou Collection';
                dd($error);
            }

        }

        return $this->render('page/my-collection.html.twig',
            ['formulaire' => $form->createView(), 'collections' => $collections,'invitations' => $invitation]);


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
     * @Route("_edit_save", name="edit_save", methods={"GET", "POST"})
     */
    public function  editSave(BeatmapsetRepository $bmsr, MappoolMapRepository $mmr, BeatmapRepository $br, MappoolRepository $mr, UserRepository $ur, EntityManagerInterface $em, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr): JsonResponse
    {


        $data = $request->request->get('form');
        $data['image'] = $request->files->get('form')['image'];


        // On rajoute ?? False les tags suppr par le form traitement
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
        if ($data['image'] != null){
            $arrData['image'] = $this->uploadBackground($data['image']);
        }else{
            $arrData['image'] = null;
        }



        // On save la collection
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
     * @throws \Exception
     */
    public function editView(int $id, TagRepository $tr, Request $request, PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr): Response
    {
        // INSTANCIATION DE LA COLLECTION

        $collection = $this->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr);

        // Verification in contributors
        $user = $this->security->getUser();
        $creator = False;
        if($user !== null){
            $start = false;
            $user = $ur->findOneBy(['id' => $user->getId()]);
            $contributors = $cr->findBy(['poolSet' => $collection['poolset']]);
            $users = [];
            foreach ($contributors as $contributor){
                if ($user->getId() == $contributor->getUser()->getId()){
                    $start = true;
                    $creator = $contributor->getIsCreator();
                }
            }
            if ($start == false){
                return $this->redirectToRoute('app_home');
            }
        }else{
            return $this->redirectToRoute('app_home');
        }




        // CREATION DU FORMULAIRE

        $form = $this->createFormBuilder()
            ->setMethod('POST')
            //->setAction('/owo')
            ->add('title', TextType::class,
                ['label' => false, 'required' => False, 'data' => $collection['poolset']->getName()]
            );

        $mods = $tr->findBy(['type' => 'gamemod']);
        foreach ($mods as $mod){
            $name = str_replace(' ', '_', $mod->getName());
            if (in_array($name, $collection['tag_names']['mod'])){
                $form = $form->add($name, CheckboxType::class, ['label' => false, 'required' => False, 'attr' => ['checked' => true]]);
            }else{
                $form = $form->add($name, CheckboxType::class, ['label' => false, 'required' => False]);
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
        if($collection['tag_names']['rank_min'] == null){
            $collection['tag_names']['rank_min'] = 1;
        }


        $form = $form
            ->add('range_min', HiddenType::class, ['attr'=> ['value' => $collection['tag_names']['range_min']]])
            ->add('range_max', HiddenType::class, ['attr'=> ['value' =>$collection['tag_names']['range_max']]])
            ->add('rank_min', HiddenType::class, ['attr'=> ['value' =>$collection['tag_names']['rank_min']]])
            ->add('rank_max', HiddenType::class, ['attr'=> ['value' =>$collection['tag_names']['rank_max']]])
            ->add('id', HiddenType::class, ['attr' => ['value'=> $id]])
            ->add('image', FileType::class, ['required' => false])
            ;
        $form = $form->getForm();


        $form->handleRequest($request);


// MAPPOOL ADD FORM

        $form_add_mappool = $this->createFormBuilder();
        $form_add_mappool->setMethod('POST')
            ->setAttribute('name', 'add')
            ->add('id', HiddenType::class, ['attr' => ['value'=> $id]])
            ->add('title', TextType::class,
                ['label' => 'Mappool Title', 'required' => False])
            ->add('submit', SubmitType::class, ['label' => 'Add Mappool'])
        ;
        $form_add_mappool = $form_add_mappool->getForm();
        $form_add_mappool->handleRequest($request);


// MAPPOOL FORMS
        $forms = ['form' => $form->createView(), 'add' => $form_add_mappool->createView()];
        $maps = [];
        foreach ($collection['mappools'] as $mappool){


            $form_mappool = $this->createFormBuilder(null, ['attr'=> ['id'=> 'pool']]);
            $form_mappool->setMethod('POST')
                ->add('id', HiddenType::class, ['attr' => ['value' => $mappool->getId()] ])
                ->add('title', TextType::class,
                ['label' => 'Mappool Title', 'required' => False, 'data' => $mappool->getName()])
                ->add('delete', ButtonType::class, ['label' => 'Delete'])
            // AJOUTER UNE MAP

                ->add('addmap', TextType::class,
                    ['label' => 'Map Link', 'required' => False, 'data' => ""]);


            foreach($mappool->maps as $map){
                $form_mappool->add('map_link_'.$map['map']->getId(), TextType::class, ['data'=> $map['map']->getUrl(), 'label' => 'Map link', 'attr' => ['poolid' => $mappool->getId()]])
                    ->add('map_mode_'.$map['map']->getId(), ChoiceType::class, [
                        'choices'  => [
                            'NM' => 'NM',
                            'DT' => 'DT',
                            'HR' => 'HR',
                            'HD' => 'HD',
                            'FM' => 'FM',
                            'TB' => 'TB'
                        ]
                        ,
                        'label' => ' ',
                        'data' => $map['mode'],
                        'attr' => ['class'=>'select mode',
                            'poolid' => $mappool->getId(),
                            'mapid' => $map['map']->getId()
                        ]
                    ]);
            }
            $form_mappool = $form_mappool->getForm();
            $form_mappool->handleRequest($request);

            $forms[rand(0,9999999)] = $form_mappool->createView();
            //$forms['map_'.rand(0,9999999)] = $add_map->createView();
            $maps[$mappool->getId()] = $mappool->maps;

        }

        $forms['poolset_data'] = ['title' => $collection['poolset']->getName(), 'thumbnail' => $collection['poolset']->getThumbnail(),'contributors'=> $contributors, 'pool_id' => $collection['poolset']->getId()];
        $forms['maps'] = $maps;
        $forms['creator'] = $creator;
        $forms['creator_id'] = $this->security->getUser()->getId();

        return $this->render('/page/edit-collection.html.twig', $forms);
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
        $tag_names = ['mod' => [], 'category' => [], 'rank_min' => '0', 'rank_max' => '100', 'range_min' => '0', 'range_max' => '100'];

        foreach($tags as $tag){
            if ($tag->getType() == 'gamemod'){
                array_push($tag_names['mod'], $tag->getName());
            }else if ($tag->getType() == 'category'){
                array_push($tag_names['category'], $tag->getName());
            }else if ($tag->getType() == 'rank_min'){
                if ($tag->getName() == "false" || $tag->getName() == null){
                    $tag_names['rank_min'] = '0';
                }else{
                    $tag_names['rank_min'] = $tag->getName();
                }
            }else if ($tag->getType() == 'rank_max'){

                if ($tag->getName() == "false"|| $tag->getName() == null){
                    $tag_names['rank_max'] = '100';
                }else{
                    $tag_names['rank_max'] = $tag->getName();
                }
            }else if ($tag->getType() == 'range_min'){
                if ($tag->getName() == "false"|| $tag->getName() == null){
                    $tag_names['range_min'] = '0';
                }else{
                    $tag_names['range_min'] = $tag->getName();
                }
            }else if ($tag->getType() == 'range_max'){
                if ($tag->getName() == "false"|| $tag->getName() == null){
                    $tag_names['range_max'] = '100';
                }else{
                    $tag_names['range_max'] = $tag->getName();

                }
            }

        }
        $tags = $this->adaptTags($tr->findByPoolset($id));



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
                array_push($maps,['name ' =>$name, 'map' =>$map, 'mode' =>$pool_map->getMode(), 'cover' => $beatmapset->getCover()]);
            }

            $mappools[$key]->maps = $maps ;

        }
        return ['poolset' => $poolset, 'tags' => $tags, 'contributors' => $contributors, 'mappools' => $mappools, 'tag_names' => $tag_names];
    }







    // Fonctions sans routes

    public function saveCollection($data, $background, TagRepository $tr, PoolSetRepository $pr,$edit=false){


        $em = $this->getDoctrine()->getManager();
        $tg = $this->getDoctrine()->getRepository(Tag::class);

        $user = $this->security->getUser();

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
                $em->flush();
            }

        }



        if ($tr->findByTypeAndValue('rank_min', (int) ltrim(str_replace(' ', '', $data['rank_min']), '#')) == null){
            $tag_rank_min = new Tag();
            $tag_rank_min->setName((int) ltrim(str_replace(' ', '', $data['rank_min']), '#'));
            $tag_rank_min->setType('rank_min');
            $em->persist($tag_rank_min);
            $em->flush();
        }else{
            $tag_rank_min = $tr->findByTypeAndValue('rank_min', (int) ltrim(str_replace(' ', '', $data['rank_min']), '#'))[0];
        }
        if ($tr->findByTypeAndValue('rank_max', (int) ltrim(str_replace(' ', '', $data['rank_max']), '#')) == null){
            $tag_rank_max = new Tag();
            $tag_rank_max->setName((int) ltrim(str_replace(' ', '', $data['rank_max']), '#'));
            $tag_rank_max->setType('rank_max');
            $em->persist($tag_rank_max);
            $em->flush();
        }else{
            $tag_rank_max = $tr->findByTypeAndValue('rank_max', (int) ltrim(str_replace(' ', '', $data['rank_max']), '#'))[0];
        }

        $collection->setName($data['title']);

        if ($background !=null){
            $collection->setThumbnail($background);
        }else{
            //$collection->setThumbnail('default');
        }

        $collection->setUpdatedAt(new \DateTime('now'));


        // Save des relations TAG - COLLECTION

        $collection->addTag($tag_rank_min);
        $collection->addTag($tag_rank_max);
        //$collection->addTag($tag_range_min);
       // $collection->addTag($tag_range_max);


        foreach($data as $data_name => $data_value){
            if ($data_name != 'title'
                && $data_name != 'range_min'
                && $data_name != 'range_max'
                && $data_name != 'rank_min'
                && $data_name != 'rank_max'
                && $data_name != 'image'
                && $data_name != 'id'
                && $data_value == true){
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

        // Si pas d'images donn??e
        if ($background == null || $background->getClientOriginalName() == null){
            return False;
        }

        #Contraintes attendues :
        $dossier = './collection_covers/';
        $taille_maxi = (2**20)*10;
        $extensions = array('.png', '.gif', '.jpg', '.jpeg');


        #Variables du fichier :
        $extension = strrchr($background->getClientOriginalName(), '.');

        $taille = $background->getSize();
        $fichier = basename($background->getClientOriginalName());
        $name = self::random(30) . $extension;


        //V??rifications de s??curit??

        if(!in_array($extension, $extensions)) //extension verif
        {
            $erreur = 'probl??me d\'extension fr??rot';
            dd($erreur);
        }
        if($taille>$taille_maxi) // size verif
        {
            $erreur = 'Le fichier est ob??se....';
            dd($erreur);
        }

        if(!isset($erreur)) //S'il n'y a pas d'erreur, on upload
        {
            //dd(scandir($dossier));


            if($background->move($dossier)) // Upload
            {
                //echo "l'upload a march?? trop bien ";
                rename($dossier . $background->getBasename(),$dossier . $name );
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


    /**
     * * @Route("/delete_collection", name="delete_collection", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
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
    public function deleteCollection(EntityManagerInterface $em,TagRepository $tr, Request $request, PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr): Response
    {
        $id = $request->getContent();
        $pools = $this->getCollection($id, $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr)['mappools'];
        foreach ($pools as $mappool){
            $pool = $mr->findOneById($mappool->getId());
            foreach($mmr->findBy(['mappool' => $pool]) as $mmap){
                $em->remove($mmap);
            }
            $em->remove($pool);
            $em->flush();
        }
        $collection = $pr->findOneBy(['id' => $id]);
        $em->remove($collection);
        $em->flush();
        return new JsonResponse(['true']);
    }

    public function adaptTags($tatags){
        $tags_tmp = $tatags;
        $tags = [];
        foreach($tags_tmp as $tag){
            if ($tag->getType() == 'rank_min'){
                $rank_min = $tag->getName();
            }elseif($tag->getType() == 'rank_max'){
                $rank_max = $tag->getName();
            }elseif($tag->getType() == 'range_min'){
                $range_min = $tag->getName();
            }elseif($tag->getType() == 'range_max'){
                $range_max = $tag->getName();
            }elseif($tag->getType() =='rank'){

            }else {
                array_push($tags, ['name' => $tag->getName(), 'type' => $tag->getType()]);
            }
        }
        if(count($tags) >0){
            if(isset($range_min) && isset($range_max)){
                array_push($tags,['name' => $range_min . " - " . $range_max, 'type' => 'range' ]);
            }
            if(isset($rank_min) && isset($rank_max)){
                array_push($tags,['name' => '#'.$rank_min . " - " .'#'. $rank_max, 'type' => 'rank' ]);
            }
        }
        return $tags;
    }

    /**
     * * @Route("/delete_contributor", name="delete_contributor", methods={"GET", "POST"})
     * @param Request $request
     * @param ContributorRepository $cr
     * @param EntityManagerInterface $em
     */
    public function deleteContributor(Request $request, ContributorRepository $cr, EntityManagerInterface $em){
        $data = (array) json_decode($request->getContent());
        $user = $this->security->getUser();

        if ($user->getId() == $data['test']){
            $contributor = $cr->findByPoolsetAndUser($data['poolset'], $data['user'])[0];
            $em->remove($contributor);
            $em->flush();
        }
        return new JsonResponse([True]);



    }

    /**
     * * @Route("/add_contributors", name="add_contributor", methods={"GET", "POST"})
     * @param TagRepository $tr
     * @param Request $request
     * @param PoolSetRepository $pr
     * @param UserRepository $ur
     * @param ContributorRepository $cr
     * @param MappoolRepository $mr
     * @param MappoolMapRepository $mmr
     * @param BeatmapRepository $br
     * @param BeatmapsetRepository $bmsr
     */
    public function sendInvitation(TagRepository $tr, EntityManagerInterface $em, Request $request, InvitationRepository $ir,PoolSetRepository $pr, UserRepository $ur, ContributorRepository $cr, MappoolRepository $mr, MappoolMapRepository $mmr, BeatmapRepository $br, BeatmapsetRepository $bmsr)
    {
        $data = json_decode($request->getContent());
        $add_users = [];

        foreach($data->users as $info){

            //v??rifie si d??j?? contributor dans ce pool
            $poolset = $pr->findOneBy(['id'=>$data->id]);
            $user = $ur->findOneBy(['id'=>$info->id]);
            $add_user = [];
            $add_user['name'] = $user->getName();
            $add_user['id'] = $user->getId();
            $add_user['thumbnail'] = $user->getThumbnail();
            $add_user['country'] = $user->getcountry();
            $verif_contributors = $cr->findByPoolsetAndUser($poolset,$user);
            //$verif_contributors = [];
            if (count($verif_contributors) == 0){
                //v??rifie si invitation denied existe, qu'il y a plus de 48h
                $invitation = $ir->findByPoolsetAndUser($poolset,$user);
                if (count($invitation) > 0){
                    if ($invitation[0]->getIsAccept() === false){
                        //on regarde le deleted_at
                        $now = new \DateTime('now');
                        $deleted = $invitation[0]->getDeletedAt();
                        $deleted->modify('+2 day');
                        if ($deleted <= $now){
                            $invitation[0]->setIsAccept(null);
                            $invitation[0]->setCreatedAt(new \DateTime('now'));
                            $em->persist($invitation[0]);
                            $em->flush();
                            array_push($add_users,$add_user);
                            return new JsonResponse($add_users);
                        }
                    } elseif($invitation[0]->getIsAccept() === true){
                        // On repasse en null
                        $invitation[0]->setIsAccept(null);
                        $invitation[0]->setCreatedAt(new \DateTime('now'));
                        $em->persist($invitation[0]);
                        $em->flush();
                        array_push($add_users,$add_user);
                        return new JsonResponse($add_users);
                    }
                }else{
                    // On instancie la relation d'invitation
                    $invit = new Invitation();
                    $invit->setUser($user);
                    $invit->setPoolset($poolset);
                    $invit->setCreatedAt(new \DateTime('now'));
                    $em->persist($invit);
                    $em->flush();
                    array_push($add_users,$add_user);
                    return new JsonResponse($add_users);
                }
            }
        }

        return new JsonResponse([False]);

    }

    /**
     * * @Route("/accept_invitation", name="accept_invitation", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param InvitationRepository $ir
     * @param ContributorRepository $cr
     * @return JsonResponse
     */
    public function acceptInvitation(EntityManagerInterface $em, Request $request, InvitationRepository $ir, ContributorRepository $cr): JsonResponse
    {
        $id = $request->getContent();
        $invitation = $ir->findOneBy(['id' => $id]);
        $invitation->setDeletedAt(new \DateTime('now'));
        $invitation->setIsAccept(true);
        $em->persist($invitation);
        if (count($cr->findByPoolsetAndUser($invitation->getPoolset(), $invitation->getUser())) == 0){
            $contributor = new Contributor();
            $contributor->setUser($invitation->getUser());
            $contributor->setPoolSet($invitation->getPoolset());
            $contributor->setIsCreator(false);
            $em->persist($contributor);
        }
        $em->flush();
        return new JsonResponse([true]);
    }
    /**
     * * @Route("/decline_invitation", name="decline_invitation", methods={"GET", "POST"})
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param InvitationRepository $ir
     * @return JsonResponse
     */
    public function declineInvitation(EntityManagerInterface $em, Request $request, InvitationRepository $ir): JsonResponse
    {
        $id = $request->getContent();
        $invitation = $ir->findOneBy(['id' => $id]);
        $invitation->setDeletedAt(new \DateTime('now'));
        $invitation->setIsAccept(false);
        $em->persist($invitation);
        $em->flush();
        return new JsonResponse([true]);
    }

    /**
     * * @Route("/followall", name="followall", methods={"GET", "POST"})
     * @param Request $request
     * @param MappoolFollowedRepository $mfr
     * @param EntityManagerInterface $em
     * @param PoolSetRepository $pr
     * @param BeatmapsetRepository $bmsr
     * @param MappoolMapRepository $mmr
     * @param BeatmapRepository $br
     * @param MappoolRepository $mr
     * @param UserRepository $ur
     * @param ContributorRepository $cr
     * @param TagRepository $tr
     * @return JsonResponse
     */
    public function followAll(Request $request, MappoolFollowedRepository $mfr, EntityManagerInterface $em, PoolSetRepository $pr, BeatmapsetRepository $bmsr, MappoolMapRepository $mmr, BeatmapRepository $br, MappoolRepository $mr, UserRepository $ur,ContributorRepository $cr, TagRepository $tr): JsonResponse
    {
        $id = (int) $request->getContent();
        $col = $pr->findOneBy(['id' => $id]);
        $user = $this->security->getUser();
        $mappools = $this->getCollection($col->getId(), $tr, $request, $pr, $ur, $cr, $mr, $mmr, $br, $bmsr)['mappools'];
        $follows = [];
        foreach ($mappools as $mappool){
            $tmp = $mfr->findBy(['user' => $user]);
            $follow = true;
            foreach($tmp as $tm){
                if ($tm->getMappool() === $mappool){
                    $follow = false;
                }
            }
            if ($follow == true){
                $mf = new MappoolFollowed();
                $mf->setUser($user);
                $mf->setMappool($mappool);
                $em->persist($mf);
                $mappool->setFollow($mappool->getFollow() + 1);
                $em->flush();
            }
            array_push($follows, $mappool->getFollow());
        }

        return new JsonResponse([$follows]);
    }

}
