<?php
/**
 * Movie Search Page
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(__DIR__.'/../../config.php');

$id = optional_param('id', null, PARAM_TEXT);
$search = optional_param('search', null, PARAM_TEXT);
$page = optional_param('page', 0, PARAM_INT);

if($page < 0){
    $page = 0;
}

$params = ['search'=>$search, 'page'=>$page];

if(isset($id)){
    $title = get_string('movietitle', 'local_movies');
    $params['id'] = $id;

} else{
    $title = get_string('searchtitle', 'local_movies');
}

$url = new moodle_url('/local/movies/index.php', $params);

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url($url);
$PAGE->set_pagelayout('standard');
$PAGE->set_title($title);
$PAGE->set_heading($title);

echo $OUTPUT->header();
echo local_movies\render::output($id, $search, $page, $url);
echo $OUTPUT->footer();