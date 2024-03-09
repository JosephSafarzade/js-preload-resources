<?php
/*
Plugin Name: Preload & Prefetch Resources
Plugin URI:
Description: Simple Plugin Which Let You Preload or Prefetch Resources Like CSS , JS , Fonts or Media Files Globally
or Per Page to improve your website speed
Version: 1.0.0.
Requires at least: 5.8
Requires PHP: 7.4
Author: Joseph Safarzade
Author URI: https://safarzade.com
License: GPLv2 or later
Text Domain: js-preload-resources
Domain Path : /languages
*/


define('JS_PR_RE_PLUGIN_VERSION'            , '1.0.0');

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

