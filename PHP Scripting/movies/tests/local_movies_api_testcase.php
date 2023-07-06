<?php
/**
 * Tests for the omdb class.
 *
 * @package   local_movies
 * @author    Ryan Gray <ryangray1111@gmail.co.za>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die;

class local_movies_api_testcase extends advanced_testcase {
    /**
     * Tests if API endpoint is functioning correctly
     * In theory, we should always get a 401 unauthorized error if we do not supply an API Key
     * 
     * Following test case assertions depend on this assertion result
     */
    public function test_api_endpoint(){
        $this->expectException(\moodle_exception::class);

        $omdb = local_movies\omdb::instance(true);

        try{
            $omdb->request([
                's'=>'Donnie Darko',
                'apikey'=>'',
            ]);

        } catch(Exception $e){
            // We are essentially skipping the exception with the try/catch
            // and the test case is expecting one. Let's throw a new one
            print_error($e->errorcode, $e->module);

            $this->assertEquals('error:401', $e->errorcode);
        }
    }

    /**
     * Tests if a given movie (Star Wars) returns the required properties
     * This confirms if the API has updated
     */
    public function test_movie_response(){
        $omdb = local_movies\omdb::instance(true);

        // Since we are using a test DB, we cannot use the API Key in settings
        // manually need to set it here.
        $testkey = '';
        $omdb->set_testkey($testkey);
        
        try{
            $movie = $omdb->movie('tt0076759');

            $this->assertEquals(true, isset($movie->Released));
            $this->assertEquals(true, isset($movie->Runtime));
            $this->assertEquals(true, isset($movie->Actors));
            $this->assertEquals(true, isset($movie->Plot));
            $this->assertEquals(true, isset($movie->Poster));

        } catch(Exception $e){
            $error = get_string($e->errorcode, $e->module);
            $msg = "There was an error with the request. Make sure you are using a valid API key. Error|$e->errorcode: $error";
            $this->assertEquals(true, false, $msg);
        }
    }
}