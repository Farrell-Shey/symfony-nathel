<?php

namespace App\Controller;

use App\Entity\MappoolFollowed;
use App\Entity\User;
use App\Repository\MappoolFollowedRepository;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    /**
     * @Route("/user/{id}", name="user", methods={"GET","HEAD"})
     * @param int $id
     * @param UserRepository $ur
     * @param ScoreRepository $sc
     * @param MappoolFollowedRepository $mf
     * @return Response
     */
    public function index(int $id, UserRepository $ur, ScoreRepository $sc, MappoolFollowedRepository  $mf): Response
    {

        // Info jumbotron
        $user = $ur->findOneBy(['osu_id'=> $id]); //Info joueur
        $recent_activity = $this->getRecentActivity($user, $sc); // Info activité récente
        $mappools_followed = $this->getMappoolsFollowed($user, $mf);





        return $this->render('user/index.html.twig', [
            'user' => $user,
            'recent_activity' => $recent_activity
        ]);
    }

    public function getSubmittedCollections(User $user){

    }
    public function getMappoolsFollowed(User $user, MappoolFollowedRepository $mf){
        $mappool_followed = $mf->findBy(['user'=> $user]);
        foreach ($mappool_followed as $mappool){

        }
        return $mappool_followed;

    }
    public function getRecentActivity(User $user, ScoreRepository $sc){

        $user_scores = $sc->findBy(['user' => $user], ['updated_at' => 'ASC'], 10);
        $recent_activity = [];

        foreach ($user_scores as $score){
            $map = $score->getMappoolMap()->getBeatmap();
            $name = $map->getBeatmapset()->getArtist() . ' - ' . $map->getBeatmapset()->getName() . ' [' . $map->getDifficulty() . ']';
            array_push($recent_activity, [$score->getnote(), $score->getAcc(), $name, $score->getUpdatedAt(), $score->getUpdatedAt()] );
        }

        return $recent_activity;
    }
}