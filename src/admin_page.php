<?php

namespace JsPreloadResources;

class admin_page
{

    public static function define_hooks(){


        add_action('admin_menu', array('JsPreloadResources\admin_page', 'add_plugin_setting_page'));


    }




    public static function add_plugin_setting_page(){


        add_submenu_page(
            'options-general.php',
            'Preload & Prefetch Settings',
            'Preload & Prefetch Settings',
            'manage_options',
            'preload-and-prefetch-settings',
            function (){
                require_once (JS_PR_RE_PLUGIN_ROOT_DIR . "templates/admin-setting-page.php");
            }
        );


    }


    public static function render_plugin_setting_page_fields($current_values){

        printf("<input type='hidden' name='js-pr-re-global-setting'>");

        for ($i = 1; $i < 11; $i++) {

            $value = isset($current_values[$i]) ?
                $current_values[$i] :
                core::return_default_values_for_global_resources_setting_fields();

            core::render_resource_name_input_in_admin_setting_page($i , $value['name']);

            core::render_resource_type_drop_down_in_admin_setting_page($i , $value['type']);

            core::render_resource_load_type_drop_down_in_admin_setting_page($i , $value['load']);

            core::render_resource_url_input_in_admin_setting_page($i , $value['url']);

            printf("<hr/>");

        }


    }

}