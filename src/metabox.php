<?php

namespace JsPreloadResources;

class metabox
{


    public static function define_hooks(){

        add_action('add_meta_boxes' , array('JsPreloadResources\metabox', 'init_load_resource_metabox'));

        add_action('save_post', array('JsPreloadResources\metabox', 'save_metabox_values'));


    }


    public static function init_load_resource_metabox(){

        add_meta_box(
            'js_pr_re_metabox',                         // Unique ID
            'PreLoad Resources',            // Box title
            array('JsPreloadResources\metabox', 'init_metabox_content')
        );

    }



    public static function init_metabox_content(){

        global $post;

        $current_data = get_post_meta($post->ID , 'js_pr_re_post_resources' , true);


        if( !$current_data || empty($current_data) ){

            $current_data = [];

        } else {

            $current_data = json_decode($current_data , true);

        }

        wp_nonce_field('js_pr_re_nonce_action', 'js_pr_re_nonce_name');

        for($i = 1 ; $i < 11 ; $i++){

            $current_value = isset($current_data[$i]) ?
                $current_data[$i] :
                core::return_default_values_for_global_resources_setting_fields();

            printf("<div class='js-pr-re-metabox-input-container'>");

            core::render_resource_name_input_in_admin_setting_page($i , $current_value['name']);

            core::render_resource_type_drop_down_in_admin_setting_page($i ,$current_value['type']);

            core::render_resource_load_type_drop_down_in_admin_setting_page($i , $current_value['load']);

            core::render_resource_url_input_in_admin_setting_page($i , $current_value['url']);

            core::render_resource_mime_type($i , $current_value['mime_type']);

            core::render_resource_cross_origin($i , $current_value['cross']);

            core::render_resource_priority($i , $current_value['priority']);

            printf("</div>");


        }

    }



    public static function save_metabox_values()
    {


        global $post;



        if ( ! isset( $_POST['js_pr_re_nonce_name'] ) ||
             ! wp_verify_nonce( $_POST['js_pr_re_nonce_name'], 'js_pr_re_nonce_action' )
        ) {

            return;

        }


        if(!isset($_POST['resource'])){

            return;

        }

        $result = core::remove_empty_items_from_global_resource_setting_fields($_POST['resource']);

        if( empty($result) ){

            return;

        }




        $result = json_encode($result);

        update_post_meta($post->ID , 'js_pr_re_post_resources' , $result);


    }








}