<?php

/*
  Plugin Name: Playlist Audio Player
  Plugin URI: https://wordpress.org/plugins/playlist-audio-player/
  Description: Playlist Audio Player will add a HTML5 player at the bottom of the site. Create your playlist. Player has built in playlist manager in which you can manage your music.Enjoy your music!!!!!!!!!!!
  Version: 1.1
  Author: dynaWEB
  Author URI: http://adidynaweb.com/
  License: GPLv2

  Copyright YEAR  PLUGIN_AUTHOR_NAME  (email : PLUGIN AUTHOR EMAIL)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License, version 2, as
  published by the Free Software Foundation.

  This program is distributed in the hope that it will be useful,
  but WITHOUT ANY WARRANTY; without even the implied warranty of
  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
  GNU General Public License for more details.

  You should have received a copy of the GNU General Public License
  along with this program; if not, write to the Free Software
  Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
 */
if (!defined('ABSPATH')) {
    die('Access denied.');
}

/*
 * Defining Constants
 */

define("PLAYER_NAME", "Qvido calculator");
define("PLAYER_VERSION", "1.1");
define("PLAYER_PATH", dirname(__FILE__));
define("PLAYER_PATH_INCLUDES", dirname(__FILE__) . "/inc");
define("PLAYER_FOLDER", basename(PLAYER_PATH));
define("PLAYER_URL", plugins_url() . "/" . PLAYER_FOLDER);
define("PLAYER_URL_INCLUDES", PLAYER_URL . "/inc");
define("playlist_player", "playlist-player");
define("PLAYER_MAIN_DIR", dirname(dirname(dirname(__FILE__))) . "/playlist-audio-player/");
define("PLAYER_UPLOAD_DIR", dirname(dirname(dirname(__FILE__))) . "/playlist-audio-player/uploads");
define("PLAYER_UPLOAD_URL", content_url() . "/playlist-audio-player/uploads/");
define("PLAYER_IMAGE_URL", content_url());

/*
 * Create folders if not exist
 */

if (!is_dir(PLAYER_MAIN_DIR)) {
    wp_mkdir_p(PLAYER_MAIN_DIR);
}
if (!is_dir(PLAYER_UPLOAD_DIR)) {
    wp_mkdir_p(PLAYER_UPLOAD_DIR);
}

/*
 * Function to define table names
 */

if (!function_exists("track_list")) {

    function track_list() {
        global $wpdb;
        return $wpdb->prefix . "audio_tracklist";
    }

}

/*
 * Function to create database
 */

if (!function_exists("createPlayerDatabase")) {

    function createPlayerDatabase() {
        include_once PLAYER_PATH . '/classes/database.php';
    }

}

register_activation_hook(__FILE__, 'createPlayerDatabase');

/*
 * include plugin classes
 */
require_once(ABSPATH . 'wp-includes/pluggable.php');
require_once( __DIR__ . '/classes/player-class.php' ); //contains hooks
$playlist_player = new playlist_player(); //init plugin    

