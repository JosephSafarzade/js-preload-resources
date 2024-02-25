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

            self::render_resource_name_input_in_admin_setting_page($i , $value['name']);

            self::render_resource_type_drop_down_in_admin_setting_page($i , $value['type']);

            self::render_resource_load_type_drop_down_in_admin_setting_page($i , $value['load']);

            self::render_resource_url_input_in_admin_setting_page($i , $value['url']);

            printf("<hr/>");

        }


    }




    public static function render_resource_url_input_in_admin_setting_page($counter , $current_value){

        printf(
            "<input type='url' name='resource[%s][url]' placeholder='%s' value='%s'>",
            $counter,
            \__('Resource URL','js-preload-resources'),
            \esc_attr($current_value)
        );

    }




    public static function render_resource_name_input_in_admin_setting_page($counter , $current_value){

        printf(
            "<input type='text' name='resource[%s][name]' placeholder='%s' value='%s'>",
            $counter ,
            \__('Resource Name','js-preload-resources'),
            \esc_attr($current_value)
        );

    }




    public static function render_resource_type_drop_down_in_admin_setting_page($counter , $current_value){

        $resource_type_array = array(
            'audio'     => 'Audio',
            'document'  => 'Document',
            'embed'     => 'Embed',
            'fetch'     => 'Fetch' ,
            'font'      => 'Font' ,
            'image'     => 'Image',
            'object'    => 'Object' ,
            'script'    => 'Script' ,
            'style'     => 'Style' ,
            'track'     => 'Track' ,
            'worker'    => 'Worker',
            'video'     => 'Video'
        );

        printf("<select name='resource[%s][type]'>",$counter );

        foreach ($resource_type_array as $key => $value){

            $selected = $key == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                \esc_attr($key) ,
                $selected ,
                \esc_attr($value)
            );

        }

        printf("</select>");

    }






    public static function render_resource_load_type_drop_down_in_admin_setting_page($counter , $current_value){


        $resource_load_type_array = array(
            'preload'     => 'PreLoad',
            'prefetch'  => 'PreFetch',
        );

        printf("<select name='resource[%s][load]' >",$counter );

        foreach ($resource_load_type_array as $key => $value){

            $selected = $key == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                \esc_attr($key) ,
                $selected ,
                \esc_attr($value)
            );

        }

        printf("</select>");


    }


}