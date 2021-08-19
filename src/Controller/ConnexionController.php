<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpClient\NativeHttpClient;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\OsuApiService;


/**
 * Class ConnexionController
 * @package App\Controller
 * Controller redirigeant la connexion API (traitement des données) vers l'authentification symfony, pour ensuite renvoyer sur la page d'origine
 */
class ConnexionController extends AbstractController
{


    /**
     * @Route("/test", name="app_login")
     */
    public function authenticate() // Check of log in (Returns error if not authenticated, and redirect to appropriate routes if authenticated)
    {


    }

    /**
     * @Route("/connexion", name="connexion")
     */
    public function LoginTemp(): RedirectResponse
    {
        session_start();
        $_SESSION['user'] = $this->login(); // get du user

        return $this->redirectToRoute('test');
    }

//    /**
//     *
//     * @return object
//     */
//    public function login(): object // Returns $user object
//    {
//        $osuApiService->getToken($osuApiService->getCode());
//        dd($osuApiService->getBeatmapInfo(954692));
//
//        # Method called during the connexion
//        $client = new NativeHttpClient();
//        $osuApiService = new OsuApiService($client);
//        $osuApiService->connexion();
//
//        return $this->loadSession($osuApiService);
//    }



    public function loadSession(OsuApiService $osu_api): ?object
    {
        // Load de Doctrine
        $em = $this->getDoctrine()->getManager();
        $ur = $this->getDoctrine()->getRepository(User::class);

        // Get des informations API
        $token = $osu_api->user_token;
        $api = $osu_api->getOwnUserInfo();
        $osu_id = $api['id'];


        // Instanciation $user
        $user = $ur->findOneBy(['osu_id' => $osu_id]);
        //TODO: update token

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
            //$user->setPassword($this->passwordHasher->hashPassword(
              //  $new_user,
                //'password'
            //));
            $new_user->setUpdatedAt(new \DateTime('now'));
            $new_user->setCreatedAt(new \DateTime('now'));
            // executes the queries

            $em->persist($new_user);
            $em->flush();
            // Instanciation User
            $user = $ur->findOneBy(['osu_id' => $osu_id]);

        }

        return $user;
    }




}
