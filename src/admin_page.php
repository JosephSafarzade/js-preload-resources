<?php

namespace JsPreloadResources;

class admin_page
{


    /**
     * Define required hook for admin_page class , this is an entry point of class
     *
     * we use 'admin_menu' hook to define function responsible for adding admin submenu for global setting fields
     *
     *
     * @return void
     */
    public static function define_hooks(){


        add_action('admin_menu', function(){

            add_submenu_page(
                'options-general.php',
                __('Preload & Prefetch Settings',JS_PR_RE_PLUGIN_TEXTDOMAIN),
                __('Preload & Prefetch Settings',JS_PR_RE_PLUGIN_TEXTDOMAIN),
                'manage_options',
                'preload-and-prefetch-settings',
                function (){
                    require_once (JS_PR_RE_PLUGIN_ROOT_DIR . "templates/admin-setting-page.php");
                }
            );

        } );


    }


    /**
     * called by add_submenu_page , this function is responsible for rendering global setting admin page content
     *
     *
     * @return void
     */
    public static function render_plugin_setting_page_fields($current_values){

        printf("<input type='hidden' name='js-pr-re-global-setting'>");

        core::render_inputs_for_resources($current_values);

    }

}