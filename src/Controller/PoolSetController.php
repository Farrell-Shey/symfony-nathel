<?php

namespace App\Controller;

use App\Entity\Contributor;
use App\Entity\PoolSet;
use App\Entity\Tag;
use App\Form\PoolSetType;
use App\Repository\BeatmapRepository;
use App\Repository\BeatmapsetRepository;
use App\Repository\ContributorRepository;
use App\Repository\MappoolMapRepository;
use App\Repository\MappoolRepository;
use App\Repository\PoolSetRepository;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use http\Client\Curl\User;
use phpDocumentor\Reflection\Types\Collection;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\RangeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
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

        dd($collections);




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
                $this->saveCollection($data, $background_path);
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
     * @param int $id
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
     * @Route("/{id}/edit", name="edit_collection", methods={"GET", "POST"})
     */
    public function  edit(int $id, BeatmapsetRepository $bmsr, MappoolMapRepository $mmr, BeatmapRepository $br, MappoolRepository $mr, UserRepository $ur, EntityManagerInterface $em, ContributorRepository $cr , Request $request,  TagRepository $tr, PoolSetRepository $pr){

        // Vérification 1. Connecté, 2. Id valide


        // Instanciation de la collection, des tags et des contributors
        $user = $this->security->getUser();
        $poolset = $pr->findById($id);
        $tags = $tr->findByPoolset($id);
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
        foreach ($mappools as $mappool) {
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
            $mappool->maps = $maps ;
        }

        dd($mappools);

    }








    // Fonctions sans routes

    public function saveCollection($data, $background){


        $em = $this->getDoctrine()->getManager();
        $tg = $this->getDoctrine()->getRepository(Tag::class);
        $user = $this->security->getUser();

        $tag_rank = $tg->findOneBy(['name' => $data['range_min'] . "_" . $data['range_max']]);
        //Save des tags de RANK SI BESOIN

        if(empty($tag_rank))
        {
            $tag_rank = new Tag();
            $tag_rank->setName($data['range_min'] . "_" . $data['range_max']);
            $tag_rank->setType('rank');
            $em->persist($tag_rank);
            $em->flush();
        }


        //Save de la collection en BDD
        $collection = new PoolSet();
        $collection->setName($data['title']);
        $collection->setThumbnail($background);
        $collection->setUpdatedAt(new \DateTime('now'));
        $collection->setCreatedAt(new \DateTime('now'));

        // Save des relations TAG - COLLECTION

        $collection->addTag($tag_rank);

        foreach($data as $data_name => $data_value){
            if ($data_name != 'title'
                && $data_name != 'range_min'
                && $data_name != 'range_max'
                && $data_name != 'image'
                && $data_value == True){
                $tmp = str_replace('_', ' ', $data_name);

                $tmp = $tg->findOneBy(['name' => $tmp]);

                $collection->addTag($tmp);

            }
        }
        $em->persist($collection);
        $em->flush();

        //Save du contributor
        $contributor = new Contributor();
        $contributor->setUser($user);
        $contributor->setPoolSet($collection);
        $contributor->setIsCreator(True);
        $em->persist($contributor);
        $em->flush();







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






}
