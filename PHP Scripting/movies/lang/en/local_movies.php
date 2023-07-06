<?php
/**
 * Language Pack
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$string['pluginname'] = 'OMDb Movies';

$string['searchtitle'] = 'Movies - Search';
$string['movietitle'] = 'Movie';
$string['apikey_valid'] = 'API key is valid';

// Settings
$string['apikey']      = 'API Key';
$string['apikey_desc'] = 'Generated key from the OMDb API. Generate your key here: https://www.omdbapi.com/apikey.aspx';

// Errors
$string['error:missingapikey'] = 'Missing API Key';
$string['error:invalidapikey'] = 'Invalid API Key';
$string['error:response'] = 'API Response Error';
$string['error:401'] = 'Unauthorized Request';
$string['error:404'] = '404 Error';