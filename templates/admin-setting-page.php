<h1 class="js-pr-re-setting-heading">

    <?php esc_html_e('Global Preload Settings','js-preload-resources'); ?>

</h1>

<hr>

<p class="js-pr-re-setting-description">

    <?php

        esc_html_e(
            'Here you can define up to 10 global preload or prefetch resources ,
             they will load globally regardless of what page
            user is visiting ',
            'js-preload-resources'
        );

    ?>

</p>


<br />


<form method="post" action="#" id="js-pr-re-global-resources">

    <?php

        if( isset( $_POST['js-pr-re-global-setting'] ) ) {

            //Here We Go , Settings are saved and we need to update options

            $filtered_value = JsPreloadResources\core::remove_empty_items_from_global_resource_setting_fields($_POST['resource']);

            if ($filtered_value){

                JsPreloadResources\core::save_plugin_setting_page_fields_values( json_encode($filtered_value) );

            }

            JsPreloadResources\admin_page::render_plugin_setting_page_fields( $_POST['resource'] );

        } else {

            //Here page is loaded but form is not submitted

            $value = JsPreloadResources\core::get_plugin_setting_page_fields_values();

            $value = $value == '' ? [] : json_decode($value,true);

            JsPreloadResources\admin_page::render_plugin_setting_page_fields( $value );

        }

    ?>


    <input type="submit">

</form>