<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\OsuApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


//  checkLogged, uri, load API? dans Abstract Controller

class ConnexionController extends AbstractController
{
    /**
     * @Route("/connexion", name="connexion")
     * @param OsuApiService $osuApiService
     * @param EntityManagerInterface $em
     * @return Response
     */

    public function connexion(OsuApiService $osuApiService, EntityManagerInterface $em)
    {
        $osuApiService->connexion();
        $_SESSION['Osu_api'] = $osuApiService; // On instancie ou on écrase

        $this->loadSession($em);
        $this->loadlastPage();
    }

    public function loadSession($em){

        $OsuApi = $_SESSION['Osu_api'];
        $token = $OsuApi->user_token;
        $api = $OsuApi->getOwnUserInfo();
        $id = $api['id'];
        // On test

        $user = $em->getRepository(User::class)->find($id);

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
            // executes the queries

            $em->persist($new_user);
            $em->flush();
            // On instancie
            $user = $em->getRepository(User::class)->find($id);
            dd($user);
        }


        // Création de la session du user
        $_SESSION['user'] = $user;

        // Création du cookie de sauvegarde
        //TODO: Think about hashage of the cookie later...
        setcookie('auth', $_SESSION['user']->osu_id);

    }


    public function loadLastPage(){
        // On retourne sur la page où se trouvait l'utilisateur (

        if (isset($_SESSION['REQUEST_URI'])){
            header('Location: '. $_SESSION['REQUEST_URI']);
        }else{
            header('Location : /');

        }
    }

    public function checkLogged(){
        /* méthode de Récupération cookie si besoin */
        if (isset($_COOKIE['auth'])) {
            // Création de la session
            $em = $this->getDoctrine()->getManager();
            $em->getRepository(User::class)->find($_COOKIE['auth']);
            $_SESSION['user'] = new User();

        }
    }

}
