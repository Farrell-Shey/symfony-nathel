<?php

namespace App\Controller;

use App\Entity\Mappool;
use App\Entity\MappoolFollowed;
use App\Entity\Score;
use App\Entity\User;
use App\Repository\BeatmapRepository;
use App\Repository\ContributorRepository;
use App\Repository\MappoolFollowedRepository;
use App\Repository\MappoolMapRepository;
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
    public function index(int $id, Request $request, MappoolMapRepository $mmr,BeatmapRepository $bmr, ScoreRepository $scr, MappoolFollowedRepository $mf, OsuApiService $osu,UserRepository $ur, MappoolFollowedRepository $mfr,MappoolRepository $mr,ContributorRepository $cr, ScoreRepository $sc, EntityManagerInterface $em): Response
    {
        // Info jumbotron
        $user = $ur->findOneBy(['osu_id'=> $id]); //Info joueur
        $mappools = $this->getMappools($user, $mf, $mr, $cr);



        $api = $osu->GetUserInfo($user->getOsuId());
        $nb_c = 0;
        foreach ($cr->findBy(['user' => $user]) as $poolset){
            $nb_c += count($mr->findBy(['poolSet' => $poolset->getPoolSet()]));
        }
        $nb_follow = count($mfr->findBy(['user' => $user]));

        $scores = $osu->getUserScores($user->getOsuId(), 'recent',0,'osu',10);


        return $this->render('page/user.html.twig', [
            'user' => $user,
            'recent_activity' => $scores,
            'api' => $api,
            'nbsubmit' => $nb_c,
            'nbfollow' => $nb_follow
        ]);
    }

    /**
     * @param int $id
     * @param OsuApiService $osu
     * @param UserRepository $ur
     * @return array
     */
    public function getScore(User $user, OsuApiService $osu, UserRepository $ur): array
    {
        $recent_activityv1 = $osu->getRecentUserScores($user->getOsuId(), 50);
        $scores = [];
        foreach($recent_activityv1 as $recent){
            $acc = (300 * $recent['count300'] + 100 * $recent['count100'] + $recent['count50'] * 50)
                / (300 * ($recent['count300'] + $recent['count100'] + $recent['count50']));
            if ($recent['rank'] != 'F'){
                array_push($scores,['beatmap' =>$osu->getBeatmapInfo($recent['beatmap_id']), 'acc' => $acc, 'score'=>$recent]);

            }
            if (count($scores) > 9){
                break;
            }
        }
        return $scores;
    }


    /**
     * @param ContributorRepository $cr
     * @param MappoolFollowedRepository $mf
     * @param EntityManagerInterface $em
     * @param Request $request
     * @param MappoolMapRepository $mmr
     * @param OsuApiService $osu
     * @param ScoreRepository $scr
     * @param BeatmapRepository $bmr
     * @param UserRepository $ur
     * @param MappoolRepository $mr
     * @return JsonResponse
     * @Route("/save_scores", name="save_scores", methods={"GET", "POST"})
     */
    public function saveScores(ContributorRepository $cr, MappoolFollowedRepository $mf, EntityManagerInterface $em,Request $request, MappoolMapRepository $mmr,OsuApiService $osu, ScoreRepository $scr,BeatmapRepository $bmr,UserRepository $ur, MappoolRepository $mr): JsonResponse
    {
        $user = $this->security->getUser(); //Info joueur
        $mappools = $this->getMappools($user, $mf, $mr, $cr)[0];
        foreach($mappools as $mappool){
            $pool = $mappool->getMappool();
            $mmaps = $mmr->findBy(['mappool' => $pool]);
            foreach($mmaps as $mmap){
                $sco = $scr->findByMmapAndUser($mmap,$user);
                    $link = $mmap->getBeatmap()->getUrl();
                    $ch = strrev($link);
                    $map_id = '';
                    for($i = 0;is_numeric($ch[$i]) == true; $i++){
                        $map_id = $map_id . (string) $ch[$i];
                    }
                    $map_id = strrev($map_id);

                    if ($mmap->getMode() == 'NM'){
                        $sco = $osu->getUserBeatmapScore($user->getOsuId(),$map_id,'0', $mmap->getBeatmap()->getModeInt());

                    }else if ($mmap->getMode() == 'HR'){
                        $sco = $osu->getUserBeatmapScore($user->getOsuId(),$map_id,'8', $mmap->getBeatmap()->getModeInt());
                    }else if ($mmap->getMode() == 'HD'){
                        $sco = $osu->getUserBeatmapScore($user->getOsuId(),$map_id,'16', $mmap->getBeatmap()->getModeInt());
                    }else if ($mmap->getMode() == 'DT'){
                        $sco = $osu->getUserBeatmapScore($user->getOsuId(),$map_id,'64', $mmap->getBeatmap()->getModeInt());
                    }


                    if ($sco != []){
                        $sco = $sco[0];
                        $acc = (300 * $sco['count300'] + 100 * $sco['count100'] + $sco['count50'] * 50)
                            / (300 * ($sco['count300'] + $sco['count100'] + $sco['count50']));
                        $score = new Score();
                        $score->setUser($user);
                        $score->setUpdatedAt(new \DateTime());
                        $score->setAcc($acc);
                        $score->setBad($sco['count50']);
                        $score->setCombo($sco['maxcombo']);
                        $score->setGood($sco['count100']);
                        $score->setMiss($sco['countmiss']);
                        $score->setNote($sco['rank']);
                        $score->setScore($sco['score']);
                        $score->setPerfect($sco['count300']);
                        $score->setMappoolMap($mmap);
                        $em->persist($score);
                        $em->flush();
                    }


            }

        }

        return new JsonResponse([true]);

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
    public function getMappools(User $user, MappoolFollowedRepository $mf, MappoolRepository $mr, ContributorRepository $cr): array
    {
        $mappool_followed = $mf->findBy(['user'=> $user]);
        $mappools_followed = [];
        $mappools_complete = [];
        foreach ($mappool_followed as $mappool){
            array_push($mappools_followed, $mr->findOneBy(['id'=>$mappool->getMappool()->getId()]));
            if ($mappool->getIsComplete() == true){
                array_push($mappools_complete, $mappool->getMappool());
            }
        }
        $col_submitted = $cr->findBy(['user' => $user]);

        return [$mappool_followed, $mappools_complete, $col_submitted];

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