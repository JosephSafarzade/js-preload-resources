<?php

namespace JsPreloadResources;

class core
{


    public static function define_hooks(){

        add_action('wp_head' , array('JsPreloadResources\core' , 'load_global_resources') );

        add_action('wp_head' , array('JsPreloadResources\core' , 'load_local_resources') );



    }



    public static function load_global_resources(){

        $resources = self::get_plugin_setting_page_fields_values();

        if($resources != '' && $resources != null){

            $resources_array = json_decode($resources , true);

        } else {

            return;

        }

        foreach ($resources_array as $global_item) {

            self::print_resource_tag_in_header($global_item);

        }


    }



    public static function load_local_resources(){

        global $post;

        if(!isset($post->ID)){

            return;

        }


        $local_resources = get_post_meta($post->ID , 'js_pr_re_post_resources' , true);

        if( !empty($local_resources) ){

            $local_resources = json_decode($local_resources,true);

            foreach ($local_resources as $local_item){

                self::print_resource_tag_in_header($local_item);

            }


        }

    }






    public static function print_resource_tag_in_header($item_data){


        if( !isset($item_data['url']) || $item_data['url'] == '' ){

            return;

        }

        $mime_type_string = $item_data['mime_type'] != '' &&  $item_data['mime_type'] != 'none' ?
                            sprintf(' type="%s" ' , $item_data['mime_type']) :
                            '';

        printf(
            '<link rel="%s" as="%s" href="%s" fetchpriority="%s" crossorigin="%s" %s>' ,
            $item_data['load'] ,
            $item_data['type'],
            $item_data['url'],
            $item_data['priority'],
            $item_data['cross'],
            $mime_type_string,
        );

    }






    public static function remove_empty_items_from_global_resource_setting_fields($setting){

        if ( !is_array($setting)){

            return false;

        }


        $result = array();

        foreach ($setting as $item_key => $item_value){

            if($item_value['url'] != ''){

                $result[$item_key] = $item_value;

            }

        }

        return $result;

    }




    public static function save_plugin_setting_page_fields_values($setting_json){

        update_option('js-pr-re-global-resource-json' , $setting_json);

    }


    public static function get_plugin_setting_page_fields_values() : string {

        return get_option('js-pr-re-global-resource-json' , '');

    }



    public static function return_default_values_for_global_resources_setting_fields() : array{

        return array(
            'name' => '',
            'type' => 'audio',
            'load' => 'preload',
            'url'  => '',
            'cross' => '',
            'priority' => 'auto',
            'mime_type' => 'none'
        );

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
            'video'     => 'Video',
            'dns-prefetch' => 'DNS Prefetch'
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
            'prefetch'    => 'PreFetch',
            'preconnect'  => 'PreConnect',
            'prerender'   => 'PreRender',
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




    public static function render_resource_cross_origin($counter , $current_value){

        $resource_load_type_array = array(
            'anonymous'     => 'Anonymous',
            'use-credentials'  => 'User Credentials',
            'same-origin'   => 'Same Origin',
            'crossorigin' => 'Cross Origin'
        );

        printf("<select name='resource[%s][cross]' >",$counter );

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






    public static function render_resource_priority($counter , $current_value){

        $resource_load_type_array = array(
            'auto'      => 'Auto',
            'low'       => 'Low',
            'high'      => 'High'
        );

        printf("<select name='resource[%s][priority]' >",$counter );

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






    public static function render_resource_mime_type($counter , $current_value){

        $available_mime_types = array('none' => "No Need");

        $available_mime_types = array_merge( $available_mime_types  , get_allowed_mime_types() );

        printf("<select name='resource[%s][mime_type]' >",$counter );

        foreach ($available_mime_types as $key => $value){

            $selected = $value == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                \esc_attr($value) ,
                $selected ,
                \esc_attr($value)
            );

        }

        printf("</select>");

    }

}