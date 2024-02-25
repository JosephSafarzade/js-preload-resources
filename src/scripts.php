<?php

namespace JsPreloadResources;

class scripts
{


    public static function define_hooks(){


        add_action( 'admin_enqueue_scripts' , array( 'JsPreloadResources\scripts' , 'load_admin_scripts' ) );


    }


    public static function load_admin_scripts(){


        \wp_enqueue_style( 'js-pr-re-admin-styles', JS_PR_RE_PLUGIN_ASSETS_CSS_URL . "admin-style.css" , [] , JS_PR_RE_PLUGIN_VERSION );

    }

}