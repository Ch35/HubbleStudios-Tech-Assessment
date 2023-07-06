<?php
/**
 * Contains API call methods for movie searching and fetching
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_movies;
defined('MOODLE_INTERNAL') || die;

class omdb{
    private const URI = 'https://www.omdbapi.com/';

    private static $instance;
    private $apikey;
    private $keyready;

    /**
     * Instantiates new OMDB API Instance
     * Validates if key is valid
     */
    private function __construct(){
        $this->apikey = get_config('local_movies', 'apikey');
        $this->keyready = get_config('local_movies', 'keyready');
    }

    /**
     * Constructor interface
     * 
     * @param bool $skipvalidate
     * @return omdb
     */
    public static function instance($skipvalidate = false){
        if(!isset(self::$instance)){
            self::$instance = new self();
            
            // skip API key validation
            if(!$skipvalidate){
                self::$instance->validate_key();
            }
        }
        return self::$instance;
    }

    /**
     * Confirms if API key is valid
     *
     * @throws \moodle_exception
     * @return bool
     */
    private function validate_key(){
        // No need to validate the key
        if($this->keyready === $this->apikey){
            return true;
        }

        if(empty($this->apikey)){
            print_error('error:missingapikey', 'local_movies');
        }

        // Sample request to confirm key is valid
        try{
            self::request($this->params());

        } catch(\Exception $e){
            if($e->errorcode = 'error:401'){
                print_error('error:invalidapikey', 'local_movies');
            }
        }

        // Store a copy for future reference
        set_config('keyready', $this->apikey, 'local_movies');
        return true;
    }

    /**
     * Sends a search request to OMDB API
     * @param string $s
     * @param int $page
     * 
     * @return object|bool
     */
    public function search($s, $page){
        if(empty($s)){
            return true;
        }

        $p = $this->params([
            's' => $s,
            'page' => $page
        ]);

        $r = self::request($p);

        return $r->json_decode();
    }

    /**
     * Sends a request to fetch a specific movie title using the provided IMDB ID
     *
     * @param string $id
     * @return object|bool
     */
    public function movie($id){
        $p = $this->params([
            'i' => $id
        ]);
        $r = self::request($p);
        $resp = $r->json_decode();

        return $resp;
    }

    /**
     * Sets api key for testing purposes
     */
    public function set_testkey($key){
        $this->apikey = $key;
    }

    /**
     * Creates and executes cURL request
     *
     * @param array $params
     * 
     * @throws \moodle_exception
     * @return response
     */
    public static function request($params){
        // $url = self::URI.'?'.http_build_query($params);

        // I believe ASP.Net doesn't handle RFC1738 encoded HTTP params very well
        $url = self::URI.'?';
        foreach($params AS $key=>$val){
            $url .= "$key=$val&";
        }
        $url = rtrim($url, '&');

        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
        ]);

        $response = new response($ch);

        switch($response->httpcode){
            case 401: print_error('error:401', 'local_movies');
            case 404: print_error('error:404', 'local_movies');
        }

        return $response;
    }

    /**
     * Set default parameters for outgoing API requests
     *
     * @param array $additional
     * @return array
     */
    private function params($additional = []){
        $additional['apikey'] = $this->apikey;
        return $additional;
    }
}