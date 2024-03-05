<?php
/*
Plugin Name: JS Preload Resources
Plugin URI:
Description: Simple Plugin Which Let You Preload or Prefetch Resources Like CSS , JS , Fonts or Media Files Globally or Per Page
Version: 1.0.0.
Requires at least: 5.8
Requires PHP: 7.4
Author: Joseph Safarzade
Author URI: https://safarzade.com
License: GPLv2 or later
Text Domain: js-preload-resources
*/


if (!defined('ABSPATH')) die('No direct access allowed');


if( ! function_exists('get_plugin_data') ){

    require_once( ABSPATH . 'wp-admin/includes/plugin.php' );

}

$plugin_data = get_plugin_data( __FILE__ );

define('JS_PR_RE_PLUGIN_VERSION'            , $plugin_data['Version']);

define('JS_PR_RE_PLUGIN_TEXTDOMAIN'         , $plugin_data['TextDomain']);

define('JS_PR_RE_PLUGIN_ROOT_DIR'           , plugin_dir_path( __FILE__ ) );

define('JS_PR_RE_PLUGIN_URL'                , plugin_dir_url(__FILE__) );

define('JS_PR_RE_PLUGIN_ASSETS_URL'         , JS_PR_RE_PLUGIN_URL . "assets/" );

define('JS_PR_RE_PLUGIN_ASSETS_CSS_URL'     , JS_PR_RE_PLUGIN_ASSETS_URL . "css/" );

define('JS_PR_RE_PLUGIN_ASSETS_JS_URL'      , JS_PR_RE_PLUGIN_ASSETS_URL . "js/" );

require_once JS_PR_RE_PLUGIN_ROOT_DIR . "/vendor/autoload.php";

\JsPreloadResources\core::define_hooks();

\JsPreloadResources\scripts::define_hooks();

\JsPreloadResources\admin_page::define_hooks();

\JsPreloadResources\metabox::define_hooks();

