<?php

namespace App\Service;

use Exception;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class OsuApiService
{
    private $client;

    const CLIENT_ID = 8955;
    const SECRET = 'tdqs5PCfWr1V5CeSCwWoFpIIC3M3umVpoMhzcCif';
    const URI = 'https://nathel.wip/connexion';

    public $code = null; // code obtained in credential token request
    public $credential_token_refresh; // refresh crendetials token
    public $user_token_refresh; // refresh user token
    public $credential_token; // Token used, whatever the user is logged or not
    public $user_token; // Token you get while a user logs, used to get his informations


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }
    public function connexion(){
        $this->getToken($this->getCode());
    }

    public function getCode()
    {
        // fonction transformant le GET['code'] en attribut
        if (isset($_GET['code'])) {
            $this->code = $_GET['code'];
            return $_GET['code'];
        }else{
            return null;
        }
    }

    // Fonction à retravailler dans l'optique d'utiliser les requettes de tokens ... Plus tard...
    public function checkToken(bool $token)
    {
        // Vérifier si le token est dans les attributs, le réobtneir le cas échéant
        // 1 for user token, 0 for credential token
        if ($token == 1) {
            if (!isset($this->user_token)) {
                $this->getToken($this->code);
            }
        } else {
            if (!isset($this->credential_token)) {
                $this->getToken(null);
            }
        }
    }

    public function getToken(string $code = null)
    {
        // Method that get a token code, credentials ($code = null) or user one // USE CURL (find a method with HTTP client only would be great)
        $curl = curl_init();
        if ($this->code == null) :
            $payload = [
                "client_id"     => self::CLIENT_ID,
                "client_secret" => self::SECRET,
                "grant_type"    => "client_credentials",
                "scope" => "public",
            ];
        else :
            $payload = [
                "client_id"     => self::CLIENT_ID,
                "client_secret" => self::SECRET,
                "grant_type"    => "authorization_code",
                "redirect_uri" => self::URI,
                "code" => $this->code
            ];

        endif;
        $payload = json_encode($payload);
        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://osu.ppy.sh/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => 1,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json',
                'Accept: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);
        $response = json_decode($response, true);
        if ($code == null) : // Public Token
            $this->credential_token = $response['access_token'];
        else : // User authentification token
            $this->user_token = $response['access_token'];
        endif;
    }

    /**
     * @throws TransportExceptionInterface
     * @throws ServerExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws DecodingExceptionInterface
     * @throws ClientExceptionInterface
     */
    public function apiQueryGET(bool $token, string $endpoint, array $params = null)
    {
        /*this method is the "GET template" for queries in the osu Api
            $token 1 for user, $token 0 for credentials
            */

        $tokenused = $token == 1 ? $this->user_token : $this->credential_token;
        $method = 'GET';
        $options = [
            'headers' => [
                'Authorization' => 'Bearer ' . $tokenused,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ]
        ];

        // Mise en place des paramètres GET si il y'en a.
        if ($params !== null) {
            $options['query'] = $params;
        }



        return $this->client->request($method, $endpoint, $options)->toArray();


    }


    // -------- OSU QUERIES USED -----------------

    //USER
    public function GetUserInfo(int $user_id)
    {
        // returns the detail of specified user.
        $this->checkToken(0);
        $endpoint = "https://osu.ppy.sh/api/v2/users/".$user_id;
        return $this->apiQueryGET(0, $endpoint);
    }

    public function getOwnUserInfo($token_user = null) //Only used for Oauth user_id verification
    {
        //Similar to GetUserInfo but with authenticated user (token owner) as user id.
        if ($token_user == null){
            $this->checkToken(1);
        }else{
            $this->user_token = $token_user;
        }
        $endpoint = 'https://osu.ppy.sh/api/v2/me/osu';
        return $this->apiQueryGET(1, $endpoint);
    }

    // MAPS
    public function getBeatmapInfo($map_id)
    {
        // Gets beatmap data for the specified beatmap ID.
        $this->getToken();


        $endpoint = 'https://osu.ppy.sh/api/v2/beatmaps/'.$map_id;


        return $this->apiQueryGET(0, $endpoint);
    }

    public function getUserRecentActivity($user_id, $limit = 12, $offset = 1)
    {
        // Returns recent activity.
        //Limit : Maximum number of results
        //Offset : Result offset for pagination

        $this->checkToken(0);
        $endpoint = "https://osu.ppy.sh/api/v2/users/" . $user_id . "/recent_activity";
        $params = array(
            'limit' => 12,
            'offset' => 1,
        );
        return $this->apiQueryGET(0,$endpoint, $params);
    }

    public function getUserScores($user_id, $score_type, $include_fails=0, $mode='osu', $limit=12, $offset=1)
    {
        // This method returns the scores of specified user.
        // score_type : Must be one of these: best, firsts, recent.
        // include_fails : Only for recent scores, include scores of failed plays. Set to 1 to include them. Defaults to 0.
        // mode : GameMode of the scores to be returned. Defaults to the specified user's mode.

        $this->checkToken(0);

        $endpoint = "https://osu.ppy.sh/api/v2/users/" . $user_id ."/scores/" . $score_type;
        $params = array(
            'include_fails' => $include_fails,
            'mode' => $mode,
            'limit' => $limit,
            'offset' => $offset
        );

        return $this->apiQueryGET(0, $endpoint, $params);

    }
}