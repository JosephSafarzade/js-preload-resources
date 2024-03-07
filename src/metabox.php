<?php

namespace JsPreloadResources;

class metabox
{



    /**
     * Define required hooks for metabox class , currently only calls 'add_meta_boxes' and
     * 'save_post' actions
     *
     * @return void
     */
    public static function define_hooks(){

        add_action('add_meta_boxes' , array('JsPreloadResources\metabox', 'init_load_resource_metabox'));

        add_action('save_post', array('JsPreloadResources\metabox', 'save_metabox_values'));

    }




    /**
     * Function which call add_meta_box inside of 'add_meta_boxes' action
     *
     * @return void
     */
    public static function init_load_resource_metabox(){

        add_meta_box(
            'js_pr_re_metabox',                         // Unique ID
            'PreLoad Resources Setting',            // Box title
            array('JsPreloadResources\metabox', 'init_metabox_content')
        );

    }




    /**
     * Render content of meta box for
     *
     * This function will get the current post meta data 'js_pr_re_post_resources' and then
     * render input with filed data
     *
     *
     * @return void
     */
    public static function init_metabox_content(){

        global $post;

        $current_data = get_post_meta($post->ID , 'js_pr_re_post_resources' , true );

        if( !$current_data || empty($current_data) ){

            $current_data = [];

        }

        if( !is_array($current_data) ){

            $current_data = json_decode($current_data , true);

        }

        wp_nonce_field('js_pr_re_nonce_action', 'js_pr_re_nonce_name');

        core::render_inputs_for_resources($current_data);

    }





    /**
     * Save resource settings meta data for individual post
     *
     * This function called in 'save_post' action , we will get global post object and then check if
     * we can verify 'js_pr_re_nonce_name' nonce field , then we will try to save 'resource' post data
     * into 'js_pr_re_post_resources' post meta
     *
     *
     * @return void
     */
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


        update_post_meta($post->ID , 'js_pr_re_post_resources' , $result);

    }








}