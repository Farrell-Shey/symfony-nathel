<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Entity\MappoolFollowed;
use App\Entity\User;
use App\Repository\ContributorRepository;
use App\Repository\MappoolFollowedRepository;
use App\Repository\MappoolRepository;
use App\Repository\ScoreRepository;
use App\Repository\UserRepository;
use App\Service\OsuApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class UserController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;
    public function __construct(Security $security)
    {
        $this->security = $security;
    }




    /**
     * @Route("/user/{id}", name="user", methods={"GET","HEAD"})
     * @param int $id
     * @param UserRepository $ur
     * @param ScoreRepository $sc
     * @param MappoolFollowedRepository $mf
     * @return Response
     */
    public function index(int $id, OsuApiService $osu,UserRepository $ur, MappoolFollowedRepository $mfr,MappoolRepository $mr,ContributorRepository $cr, ScoreRepository $sc, EntityManagerInterface $em): Response
    {


        // Info jumbotron
        $user = $ur->findOneBy(['osu_id'=> $id]); //Info joueur
        //$recent_activity = $this->getRecentActivity($user, $sc); // Info activité récente
        $mappools_followed = $this->getMappoolsFollowed($user, $mfr);
        $api = $osu->GetUserInfo($user->getOsuId());
        $nb_c = 0;
        foreach ($cr->findBy(['user' => $user]) as $poolset){
            $nb_c += count($mr->findBy(['poolSet' => $poolset->getPoolSet()]));
        }
        $nb_follow = count($mfr->findBy(['user' => $user]));

        $recent_activity = $osu->getUserScores($user->getOsuId(), 'recent',0,'osu',10);


        return $this->render('page/user.html.twig', [
            'user' => $user,
            'recent_activity' => $recent_activity,
            'api' => $api,
            'nbsubmit' => $nb_c,
            'nbfollow' => $nb_follow
        ]);
    }

    /**
     * @param Request $request
     * @param MappoolFollowedRepository $mfr
     * @param EntityManagerInterface $em
     * @param MappoolRepository $mr
     * @return JsonResponse
     * @Route("/follow_pool", name="follow_pool", methods={"GET", "POST"})
     */
    public function followPool(Request $request, MappoolFollowedRepository $mfr, EntityManagerInterface $em, MappoolRepository $mr){
        $id = (int) $request->getContent();
        $mappool = $mr->findOneBy(['id' => $id]);
        $user = $this->security->getUser();

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
        return new JsonResponse([$mappool->getFollow()]);
    }

    /**
     * @param Request $request
     * @param MappoolFollowedRepository $mfr
     * @param EntityManagerInterface $em
     * @param MappoolRepository $mr
     * @return JsonResponse
     * @Route("/unfollow_pool", name="unfollow_pool", methods={"GET", "POST"})
     */
    public function unfollowPool(Request $request, MappoolFollowedRepository $mfr, EntityManagerInterface $em, MappoolRepository $mr){
        $id = (int) $request->getContent();
        $mappool = $mr->findOneBy(['id' => $id]);
        $user = $this->security->getUser();
        $tmp = $mfr->findBy(['user' => $user]);
        foreach($tmp as $tm){
            if ($tm->getMappool() === $mappool){
                $em->remove($tm);
                $mappool->setFollow($mappool->getFollow() - 1);
                $em->flush();
            }
        }
        return new JsonResponse([$mappool->getFollow()]);
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