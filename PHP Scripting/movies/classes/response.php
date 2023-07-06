<?php
/**
 * Contains and handles cURL requests and responses
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_movies;
defined('MOODLE_INTERNAL') || die;

class response{
    private $httpcode;
    private $body;

    /**
     * Handles cURL request
     *
     * @param \CurlHandle $ch
     */
    function __construct($ch){
        $this->body = curl_exec($ch);
        $this->httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

        curl_close($ch);
    }

    /**
     * Get overload
     *
     * @param string $name
     * @return mixed
     */
    function __get($name){
        switch($name){
            case 'body': return $this->body;
            case 'httpcode': return $this->httpcode;
        }
    }

    /**
     * Converts response JSON output to object
     *
     * @return object|false
     */
    function json_decode(){
        $obj = json_decode($this->body);

        if(isset($obj->Error)){
            print_error('error:response', 'local_movies', '', null, $obj->Error);
        }

        return $obj;
    }
}