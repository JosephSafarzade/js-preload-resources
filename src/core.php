<?php

namespace JsPreloadResources;

class core
{


    public static function define_hooks(){

        add_action('wp_head' , array('JsPreloadResources\core' , 'load_global_resources') );


    }



    public static function load_global_resources(){

        $resources = self::get_plugin_setting_page_fields_values();

        if($resources != '' && $resources != null){

            $resources_array = json_decode($resources , true);

        } else {

            return;

        }

        foreach ($resources_array as $item) {

            if($item['url'] != ''){

                printf(
                    '<link rel="%s" as="%s" href="%s">' ,
                    $item['load'] ,
                    $item['type'],
                    $item['url']
                );

            }

        }



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
            'url'  => ''
        );

    }

}