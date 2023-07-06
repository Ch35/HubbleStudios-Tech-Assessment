<?php
/**
 * Plugin settings
 * @package    local_movies
 * @author     Ryan Gray <ryangray1111@gmail.co.za>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

$settings = null;
if (is_siteadmin()) {
    $ADMIN->add('root', new admin_category('local_movies', get_string('pluginname', 'local_movies')));
    $ADMIN->add('local_movies', new admin_externalpage('index', 'Move Search', new moodle_url('/local/movies/')));

    $settings = new admin_settingpage('local_movies_settings', new lang_string('settings'));

    $id = 'local_movies/apikey';
    $name = get_string('apikey', 'local_movies');
    $desc = get_string('apikey_desc', 'local_movies');
    $default = '';
    $settings->add(new admin_setting_configtext($id, $name, $desc, $default, PARAM_TEXT));

    // Validate the inputted apikey
    local_movies\render::validate_apikey();

    $ADMIN->add('local_movies', $settings);
}