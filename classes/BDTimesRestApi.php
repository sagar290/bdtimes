<?php
namespace BDTAP;

use BDTAP\Library\AlpineRestApi as Route;

if (!class_exists('BDTimesRestApi')) {

    class BDTimesRestApi
    {


        public function __construct()
        {
            Route::post('bdtap/v1', '/videoMetaByTitle', array($this, "videoMetaByTitle"));
        }

        public function videoMetaByTitle($request) {
            global $wpdb;

            // Nonce is checked, get the POST data and sign user on
            $title = $request['title'];

            // $post = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type='post'", $content ));
            // if ( $post )
            //     return get_post($post, $output);

            $post = get_page_by_title($title, OBJECT, 'post');
            
            if ( !$post ){
                wp_send_json_error([]);
            } else {
                $post_meta = get_post_meta( $post->ID, 'td_last_set_video');
                wp_send_json_success($post_meta);
            }
            

        }


    }

}
