<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\ReTestService;


//  checkLogged, uri, load API? dans Service
// Check pour le timestamp


class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     * @return object
     */
    public function login(): object
    {

        # Method called during the connexion
        $client = new NativeHttpClient();
        $osuApiService = new OsuApiService($client);


        $osuApiService->connexion();

        dd($this->loadSession($osuApiService));
    }

    /**
     *
     */
    public function authenticate()
    {


    }

    public function loadSession(OsuApiService $osu_api)
    {
        // Load de l'objet USER dans la session et le cookie

        $em = $this->getDoctrine()->getManager();
        $ur = $em->getDoctrine()->getRepository(User::class);


        $token = $osu_api->user_token;
        $api = $osu_api->getOwnUserInfo();
        $osu_id = $api['id'];
        // On test

        $user = $ur->findOneBy(['osu_id' => $osu_id]);
        //update token

        if (!isset($user)) {

            //make data user and store user
            $new_user = new User();
            $new_user->setOsuId($api['id']);
            $new_user->setName($api['username']);
            $new_user->setToken($token);
            $new_user->setThumbnail($api['avatar_url']);
            $new_user->setGameModeStd($api['statistics']['global_rank']);
            //TODO: think about adding other game_mod ranks later...
            $new_user->setCountry($api['country_code']);
            $new_user->setCover($api['cover_url']);
            $new_user->setToken($osu_api->user_token);
            $new_user->setUpdatedAt(new \DateTime('now'));
            $new_user->setCreatedAt(new \DateTime('now'));
            // executes the queries

            $em->persist($new_user);
            $em->flush();
            // On instancie
            $user = $ur->findOneBy(['osu_id' => $osu_id]);

        }


        // récupération mot de passe (ou génération)
        //


        // Création du cookie de sauvegarde
        //TODO: Think about hashage of the cookie later... note: système symfony hashage
        //setcookie('auth', $_SESSION['user']->getOsuId());
        return $user;
    }


    public function loadLastPage()
    {
        // On retourne sur la page où se trouvait l'utilisateur (

        if (isset($_SESSION['REQUEST_URI'])) {
            header('Location: ' . $_SESSION['REQUEST_URI']);
        } else {
            header('Location : /');

        }
    }

    public function checkLogged()
    {
        /* méthode de Récupération cookie si besoin */
        if (isset($_COOKIE['auth'])) {
            // Création de la session
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(User::class)->find($_COOKIE['auth']);
            $_SESSION['user'] = new User();

        }
    }


}
