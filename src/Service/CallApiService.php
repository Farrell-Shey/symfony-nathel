<?php


namespace App\Service;

use Symfony\Contracts\HttpClient\HttpClientInterface;

class CallApiService
{
    private $client;

    const CLIENT_ID = 8955;
    const SECRET = 'tdqs5PCfWr1V5CeSCwWoFpIIC3M3umVpoMhzcCif';
    const URI = 'https://127.0.0.1:8000/connexion';

    public $code; // code obtained in credential token request
    public $credential_token_refresh; // refresh crendetials token
    public $user_token_refresh; // refresh user token
    public $credential_token; // Token used, whatever the user is logged or not
    public $user_token; // Token you get while a user logs, used to get his informations


    public function __construct(HttpClientInterface $client)
    {
        $this->client = $client;
    }

    public function getCode() {

        if (isset($_GET['code'])){
            $this->code = $_GET['code'];
        }else{
            // Lancer la page erreur
        }
    }
    public function test(){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://osu.ppy.sh/oauth/token',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => array('grant_type' => 'client_credentials','client_id' => '8955','client_secret' => 'tdqs5PCfWr1V5CeSCwWoFpIIC3M3umVpoMhzcCif','scope' => 'public'),
            CURLOPT_HTTPHEADER => array(
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);
        dd($response);
    }
    public function curlcbien(){
        // Method that get a token code, credentials ($code = null) or user one
        $curl = curl_init();
        $code = null;
        if ($code == null):
            $payload = [
                "client_id"     => self::CLIENT_ID,
                "client_secret" => self::SECRET,
                "grant_type"    => "client_credentials",
                "scope" => "public",
            ];

        else:

            $payload = [
                "client_id"     => self::CLIENT_ID,
                "client_secret" => self::SECRET,
                "grant_type"    => "authorization_code",
                "redirect_uri" => self::URI,
                "code" => $code
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
            ),
        ));

        $response = curl_exec($curl);
        dd($response);
        curl_close($curl);
        $response = json_decode($response, true);


        if ($code==null): // Public Token


            //$this->current_credentials_token = $response['access_token'];

        else: // User authentification token

            //return $response['access_token'];
        endif;
}
    public function getCredential_token(){
        $endpoint = 'https://osu.ppy.sh/oauth/token';
        $method = 'POST';
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => [
                'grant_type' => 'client_credentials',
                'client_id' => 8955,
                'client_secret' => 'tdqs5PCfWr1V5CeSCwWoFpIIC3M3umVpoMhzcCif',
                'scope' => 'public'
            ]

        ];
        dd($this->client->request($method, $endpoint, $options)->toArray());
    }

    public function getUser_token()
    {
        $this->getCode();

        $endpoint = 'https://osu.ppy.sh/oauth/token';
        $method = 'POST';
        $options = [
            'headers' => [
                'Accept' => 'application/json',
                'Content-Type' => 'application/json'
            ],
            'body' => [
                'grant_type' => 'authorization_code',
                'client_id' => $this::CLIENT_ID,
                'client_secret' => $this::SECRET,
                'redirect_uri' => $this::URI,
                'code' => $this->code,
            ]

        ];
        dd($options);
        dd($this->client->request($method, $endpoint, $options)->getContent());
    }
        public function requestOsu(){
            $endpoint = 'https://osu.ppy.sh/api/v2/me/osu';
            $method = 'GET';
            $options = [
            'headers' => [
                'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiJ9.eyJhdWQiOiI4OTU1IiwianRpIjoiYzA3MDI3ZDcyNzVmYzBiMTU4YTVhNDBiZWY2NGYwYzNjZjFlODc3NDhlMzczNWQxY2ZkM2RiZjhmZGEwZmYxNjA5OWY1NjNhZmJmZjc5ZDEiLCJpYXQiOjE2MjgzMjMyMjQuMjM2NTQsIm5iZiI6MTYyODMyMzIyNC4yMzY1NDIsImV4cCI6MTYyODQwOTYyNC4yMjAwOSwic3ViIjoiOTU0MzYzMyIsInNjb3BlcyI6WyJpZGVudGlmeSIsInB1YmxpYyJdfQ.QMGMT120hTfnMYr-HfOY8lN7hWm1cmaPw2graXOdpUcApbMJW4x4eAfzXPCzWknXleKl7zeFsCuxVrQSrRISVTNkcc5dLAqQw02xiIBPmOCM9FC5ezS5_rE3I92TCtEx4aFX-oo_kCkym-Tq9cafVIZgaY9-xVI1C6bRz1wtGoysh6LO8T28N5_IT9VgMBjdYoL6B7zsSRAeFR4tYtLYLaoJgDE_p8gaJvsgQLRAmBwAPe3Xq8AMzkRJCC774awC1-r6v7qSv2MU0eD6qvtCgXD1EjG57WW8EAYIzKB64Cs01n5h3KKSUa8rbU34wU8QAokPlGHTOnjKft-HgunlmxS4HxlhFQ0vJHFbmC6ym-qcKqDRWYN-_dlXnFuLymVKtcw_cfN4-MCESejCOQuxRW-Hjqv5HSIiFsRao1IHoTDsTlPM3XZ2R0HyMMz4--S6SK-4Q63fqeIeN_b8Fi-aiHIl39F4iZyDbdfDie7_Dtd4wC0B2mfLEAywkgWq2VBYfDvFcvSdkKfxHZMUP2LHwyCnvK0DcmNryQv0-nNES6ORYsRcduIZ_OyD2kcqnUbAQw13TzACvnUvQpPVEhdtD2XAZP390QPefBjwLFs2g3QBtW01zF7rMiWpKT4wwk4Iagcc7LPZnRTjEhlU_pFL2fZ5W3DIct08xj1H5MBrpss',
                'Accept' =>'application/json',
                'Content-Type' => 'application/json'
            ]];
        dd($this->client->request($method, $endpoint, $options)->getContent());
    }



    /*
    public function getUser_token(): string
    {


    }*/

    public function getOsuData(): array
    {
        $response = $this->client->request(
            'GET',
            'https://osu.ppy.sh/oauth/authorize'
        );
        return $response->toArray();
    }
}

