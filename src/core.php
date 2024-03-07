<?php

namespace JsPreloadResources;

class core
{


    /**
     * Define required hook for core class , this is an entry point of class
     *
     * we use 'wp_head' hook to define function responsible of loading resources in global and specific post
     *
     *
     * @return void
     */
    public static function define_hooks(): void {

        add_action('wp_head' , array('JsPreloadResources\core' , 'load_global_resources') );

        add_action('wp_head' , array('JsPreloadResources\core' , 'load_local_resources') );

    }




    /**
     * load global resources for every page without any condition
     *
     * we will call get_plugin_setting_page_fields_values function to get global resource values and then apply them
     * into head tag as this function called in 'wp_head' hook
     *
     *
     * @return void
     */
    public static function load_global_resources(): void{

        $resources = self::get_plugin_setting_page_fields_values();


        if( !is_array($resources) && $resources !=''){

            $resources = json_decode($resources,true);

        }


        if(empty($resources)){

            return;

        }

        foreach ($resources as $global_item) {

            self::print_resource_tag_in_header($global_item);

        }


    }




    /**
     * load local resources for each specific page
     *
     * we will check for 'js_pr_re_post_resources' meta data for each post and then if there is a value to set we will
     * print in head tag as we called this function in 'wp_head' hook
     *
     *
     * @return void
     */
    public static function load_local_resources(): void{

        global $post;

        if(!isset($post->ID)){

            return;

        }


        $local_resources = get_post_meta($post->ID , 'js_pr_re_post_resources' , true);


        if(empty($local_resources)){

            return;

        }


        foreach ($local_resources as $local_item){

            self::print_resource_tag_in_header($local_item);

        }




    }






    /**
     * Print a <link> tag for each specific resource
     *
     * this function is responsible to get a single resource value and then print a <link> tag based on provided data
     *
     * @param array{
     *        name : string ,
     *        type : string ,
     *        load : string ,
     *        url  : string ,
     *        cross : string ,
     *        priority : string ,
     *        mime_type : string,
     *    } $item_data
     *
     * @return void
     */
    public static function print_resource_tag_in_header(array $item_data): void{


        if( !isset($item_data['url']) || $item_data['url'] == '' ){

            return;

        }

        $mime_type_string = $item_data['mime_type'] != '' &&  $item_data['mime_type'] != 'none' ?
                            sprintf(' type="%s" ' , $item_data['mime_type']) :
                            '';

        printf(
            '<link rel="%s" as="%s" href="%s" fetchpriority="%s" crossorigin="%s" %s>' ,
            esc_attr($item_data['load']),
            esc_attr($item_data['type']),
            esc_url($item_data['url']),
            esc_attr($item_data['priority']),
            esc_attr($item_data['cross']),
            esc_attr($mime_type_string),
        );

    }






    /**
     * Print a <link> tag for each specific resource
     *
     * this function is responsible to get a single resource value and then print a <link> tag based on provided data ,
     * $setting param is array of resource values , resource value structure is like
     * array{
     *        name : string ,
     *        type : string ,
     *        load : string ,
     *        url  : string ,
     *        cross : string ,
     *        priority : string ,
     *        mime_type : string,
     *    }
     *
     * @param $setting[]
     *
     * @return array
     */
    public static function remove_empty_items_from_global_resource_setting_fields(array $setting): array{

        if ( !is_array($setting)){

            return [];

        }


        $result = array();

        foreach ($setting as $item_key => $item_value){

            if($item_value['url'] != ''){

                $result[$item_key] = $item_value;

            }

        }

        return $result;

    }





    /**
     * simple function which get a json string and save it as 'js-pr-re-global-resource-json' for global resource
     * setting
     *
     *
     * @param string $setting_json
     *
     * @return void
     */
    public static function save_plugin_setting_page_fields_values(string $setting_json):void{

        update_option('js-pr-re-global-resource-json' , $setting_json);

    }



    /**
     * simple function to return 'js-pr-re-global-resource-json' option as global resource setting
     *
     *
     * @return string
     */
    public static function get_plugin_setting_page_fields_values() : string {

        return get_option('js-pr-re-global-resource-json' , '');

    }



    /**
     * Return default value for a resource field settings
     *
     *
     * Render a simple text input for resource URL , we will put $current_value in it for previous defined values
     *
     *
     * @return array{
     *       name : string ,
     *       type : string ,
     *       load : string ,
     *       url  : string ,
     *       cross : string ,
     *       priority : string ,
     *       mime_type : string,
     *   }
     */
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




    /**
     * Render resource URL text input
     *
     *
     * Render a simple text input for resource URL , we will put $current_value in it for previous defined values
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_url_input_in_admin_setting_page(int $counter ,string $current_value) : void{

        printf(
            "
            <div class='js-pr-re-single-input-container'>
                <label>%s :</label>
                <input type='url' name='resource[%s][url]' placeholder='%s' value='%s'>
            </div>",
            __('Resource URL' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter,
            __('Resource URL','js-preload-resources'),
            esc_attr($current_value)
        );

    }




    /**
     * Render resource name text input
     *
     *
     * Render a simple text input for resource name , we will put $current_value in it for previous defined values
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_name_input_in_admin_setting_page(int $counter , string $current_value):void{

        printf(
            "
            <div class='js-pr-re-single-input-container'>
                <label>%s :</label>
                <input type='text' name='resource[%s][name]' placeholder='%s' value='%s'>
            </div>",
            __('Resource Name' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter ,
            __('Resource Name','js-preload-resources'),
            esc_attr($current_value)
        );

    }



    /**
     * Render resource type selector input
     *
     *
     * We will define a static array for resource  type then fill a select tag
     * with retrieved data also we check $current_value to see if it matches any of
     * retrieved values to make it selected
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_type_drop_down_in_admin_setting_page(int $counter ,string $current_value) : void{

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


        printf(
            "<div class='js-pr-re-single-input-container'>
                        <label>%s :</label><br />
                        <select name='resource[%s][type]'>",
            __('Resource Type' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter
        );

        foreach ($resource_type_array as $key => $value){

            $selected = $key == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                esc_attr($key) ,
                $selected ,
                esc_attr($value)
            );

        }

        printf("</select>");

        printf("</div>");

    }





    /**
     * Render resource load type selector input
     *
     *
     * We will define a static array for resource loading type then fill a select tag
     * with retrieved data also we check $current_value to see if it matches any of
     * retrieved values to make it selected
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_load_type_drop_down_in_admin_setting_page(int $counter ,string $current_value) : void{

        $resource_load_type_array = array(
            'preload'     => 'PreLoad',
            'prefetch'    => 'PreFetch',
            'preconnect'  => 'PreConnect',
            'prerender'   => 'PreRender',
        );


        printf(
            "<div class='js-pr-re-single-input-container'>
                        <label>%s :</label><br />
                        <select name='resource[%s][load]'>",
            __('Resource Load Type' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter
        );

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

        printf("</div>");


    }






    /**
     * Render resource cross origin type selector input
     *
     *
     * We will define a static array for resource cross origin type then fill a select tag
     * with retrieved data also we check $current_value to see if it matches any of
     * retrieved values to make it selected
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_cross_origin(int $counter ,string $current_value) : void{

        $resource_load_type_array = array(
            'anonymous'     => 'Anonymous',
            'use-credentials'  => 'User Credentials',
            'same-origin'   => 'Same Origin',
            'crossorigin' => 'Cross Origin'
        );


        printf(
            "<div class='js-pr-re-single-input-container'>
                        <label>%s :</label><br />
                        <select name='resource[%s][cross]'>",
            __('Resource Cross Mode' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter
        );

        foreach ($resource_load_type_array as $key => $value){

            $selected = $key == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                esc_attr($key) ,
                $selected ,
                esc_attr($value)
            );

        }

        printf("</select>");

        printf("</div>");


    }





    /**
     * Render resource priority type selector input
     *
     *
     * We will define a static array for resource loading priority and then fill a select tag
     * with retrieved data also we check $current_value to see if it matches any of
     * retrieved values to make it selected
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_priority(int $counter ,string $current_value) : void {

        $resource_load_type_array = array(
            'auto'      => 'Auto',
            'low'       => 'Low',
            'high'      => 'High'
        );


        printf(
            "<div class='js-pr-re-single-input-container'>
                        <label>%s :</label><br />
                        <select name='resource[%s][priority]'>",
            __('Resource Priority' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter
        );

        foreach ($resource_load_type_array as $key => $value){

            $selected = $key == $current_value ? 'selected' : '' ;

            printf(
                "<option value='%s' %s >%s</option>",
                esc_attr($key) ,
                $selected ,
                esc_attr($value)
            );

        }

        printf("</select>");

        printf("</div>");

    }





    /**
     * Render mime type selector input
     *
     *
     * We will call get_allowed_mime_types function and will fill a select tag with retrieved data ,
     * also we check $current_value to see if it matches any of retrieved values to make it selected
     *
     *
     * @param  int  $counter
     * @param  string $current_value
     *
     * @return void
     */
    public static function render_resource_mime_type(int $counter , string $current_value) : void {

        $available_mime_types = array('' => "No Need");

        $available_mime_types = array_merge( $available_mime_types  , get_allowed_mime_types() );

        printf(
            "<div class='js-pr-re-single-input-container'>
                        <label>%s :</label><br />
                        <select name='resource[%s][mime_type]'>",
            __('Resource Mime Type' , JS_PR_RE_PLUGIN_TEXTDOMAIN),
            $counter
        );

        foreach ($available_mime_types as $key => $value){

            $selected = $value == $current_value ? 'selected' : '' ;

            $label = $value;

            $value = $value == "No Need" ? '' : $value ;

            printf(
                "<option value='%s' %s >%s</option>",
                esc_attr($value) ,
                $selected ,
                esc_attr($label)
            );



        }

        printf("</select>");

        printf("</div>");

    }






    /**
     * Render resources settings input in admin page and post meta boxes
     *
     *
     * Used to render several inputs and fill them if there is a value
     *
     *
     * @param  array{
     *      name : string ,
     *      type : string ,
     *      load : string ,
     *      url  : string ,
     *      cross : string ,
     *      priority : string ,
     *      mime_type : string,
     *  } $current_values
     *
     * @return void
     */
    public static function render_inputs_for_resources(array $current_values) : void {

        for ($i = 0; $i < 10; $i++) {

            $current_value = isset($current_values[$i]) ?
                $current_values[$i] :
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

            printf("<hr />");

        }

    }

}