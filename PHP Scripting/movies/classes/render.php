<?php
/**
 * Contains API call methods for movie searching and fetching
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_movies;
defined('MOODLE_INTERNAL') || die;

class render{
    /**
     * Attempts to instantiate OMDb API instance to validate the submitted key
     * Displays Moodle error message in the event of failed validation
     *
     * @return void
     */
    public static function validate_apikey(){
        global $PAGE;

        // Only execute this method on the plugin settings page
        if($PAGE->bodyid !== 'page-admin-setting-local_movies_settings'){
            return;
        }

        // Avoid executing multiple times when saving settings
        if(optional_param('action', null, PARAM_TEXT) === 'save-settings'){
            return;
        }

        // no need to check for missing key
        if(empty(get_config('local_movies', 'apikey'))){
            return;
        }

        try{
            omdb::instance();

        } catch(\Exception $e){
            \core\notification::error($e->getMessage());
            return;
        }

        \core\notification::success(get_string('apikey_valid', 'local_movies'));
    }

    /**
     * Binary rendering for movie searching and rendering
     *
     * @param string $id
     * @param string $search
     * @param int $page
     * @param \moodle_url $url
     * 
     * @return string
     */
    public static function output($id, $search, $page, $url){
        $api = omdb::instance();

        return isset($id) 
            ? self::movie($id, $search, $api) 
            : self::search($search, $page, $api, $url);
    }

    /**
     * @param string $id
     * @param omdb $api
     * 
     * @return string
     */
    private static function movie($id, $search, $api){
        global $OUTPUT;

        $movie = $api->movie($id);

        // Remove "N/A" Poster
        if($movie->Poster === 'N/A'){
            $movie->Poster = null;
        }

        return $OUTPUT->render_from_template('local_movies/search', [
            'movie' => $movie,
            'search' => $search,
        ]);
    }

    /**
     * @param string $s
     * @param int $page
     * @param omdb $api
     * @param string $url
     * 
     * @return string
     */
    private static function search($s, $page, $api, $url){
        global $OUTPUT;

        
        try{
            $response = $api->search($s, ++$page);
            --$page;

        } catch(\Exception $e){
            \core\notification::error($e->getMessage());
            $response = true;
        }


        if($response === true){
            $results = [];
            $pages = 0;
            $perpage = 0;

        } else{
            $results = $response->Search;
            $perpage = count($results);
            $pages = $response->totalResults / $perpage;

            // Remove "N/A" Posters
            foreach($results AS $i=>$r){
                if($r->Poster === 'N/A'){
                    $r->Poster = null;
                }

                $results[$i] = $r;
            }
        }

        return $OUTPUT->render_from_template('local_movies/search', [
            'results' => $results,
            'search' => $s,
            'pagination' => $OUTPUT->paging_bar($pages, $page, $perpage, $url)
        ]);
    }
}