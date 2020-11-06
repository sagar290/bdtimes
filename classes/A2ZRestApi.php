<?php
namespace A2ZTRADE;

use A2ZTRADE\Library\AlpineRestApi as Route;

if (!class_exists('A2ZRestApi')) {

    class A2ZRestApi
    {

        public function __construct()
        {
            Route::post('a2ztrade/v1', '/addProduct', array($this, "addProduct"));
            Route::post('a2ztrade/v1', '/uploadimage', array($this, "uploadImage"));
        }


        // {
        //     "product_key": "0007204811",
        //     "variant_key": "",
        //     "option_key": null,
        //     "name": "K√ºhltasche isoBag S 13 lt.",
        //     "additional_name": "2tlg. mango",
        //     "variant_name": "",
        //     "option_name": "",
        //     "stock_level": "2",
        //     "stock_text": "sofort lieferbar",
        //     "stock_class": "green",
        //     "stock_quantity": "3",
        //     "price": "49.5",
        //     "display_price": "CHF 49.50",
        //     "discount": "0",
        //     "display_discount": "",
        //     "original_price": 0,
        //     "display_original_price": null,
        //     "cost_price": 27.5,
        //     "cost_price_discount": 0,
        //     "cost_price_total": 27.5,
        //     "sku": "0007204811",
        //     "barcode": 4002458485831,
        //     "weight": ".387",
        //     "image_url": "//www.dfshop.com/wsshop/Dipius/pict/0007204811.jpg",
        //     "image_url2": "",
        //     "image_url3": "",https:
        //     "brand_id": "5",
        //     "brand_name": "Alfi",
        //     "category": "alfi/To Go/isoBag",
        //     "category_alt": "",
        //     "variant_label": "",
        //     "option_label": "",
        //     "option_group": "\t\t\t\t\t\t\t\t",
        //   },
        public function addProduct($request)
        {
            global $wpdb;

            // Nonce is checked, get the POST data and sign user on
            $name = $request['name'];
            $stock_level = $request['stock_level'];
            $stock_quantity = $request['stock_quantity'];
            $additional_name = $request['additional_name'];
            $price = $request['price'];
            $original_price = $request['original_price'];
            $weight = $request['weight'];
            $image_url = $request['image_url'];
            $sku = $request['sku'];
            $brand_name = $request['brand_name'];
            $category = $request['category'];

            $post_id = wp_insert_post(array(
                'post_title' => $name,
                'post_content' => $additional_name,
                'post_status' => 'publish',
                'post_type' => "product",
            ));
            wp_set_object_terms($post_id, $brand_name, 'pa_brand', true);
            update_post_meta( $post_id, '_visibility', 'visible' );
            update_post_meta( $post_id, '_stock_status', $stock_level < $stock_quantity ? 'instock' : 'outofstock');
            update_post_meta( $post_id, 'total_sales', '0' );
            update_post_meta( $post_id, '_downloadable', 'no' );
            update_post_meta( $post_id, '_virtual', 'yes' );
            update_post_meta( $post_id, '_regular_price', $original_price ? $original_price : $price );
            update_post_meta( $post_id, '_sale_price', $price );
            update_post_meta( $post_id, '_purchase_note', '' );
            update_post_meta( $post_id, '_featured', 'no' );
            update_post_meta( $post_id, '_weight', $weight );
            update_post_meta( $post_id, '_length', '' );
            update_post_meta( $post_id, '_width', '' );
            update_post_meta( $post_id, '_height', '' );
            update_post_meta( $post_id, '_sku', $sku );
            update_post_meta( $post_id, '_product_attributes', [
                'pa_brand' => [
                    'name'=>'pa_brand',
                    'value'=> $brand_name,
                    'is_visible' => '1',
                    'is_taxonomy' => '1'
                ]
            ] );
            update_post_meta( $post_id, '_sale_price_dates_from', '' );
            update_post_meta( $post_id, '_sale_price_dates_to', '' );
            update_post_meta( $post_id, '_price', $price );
            update_post_meta( $post_id, '_sold_individually', '' );
            update_post_meta( $post_id, '_manage_stock', 'no' );
            update_post_meta( $post_id, '_backorders', 'no' );

            $this->upload_media($image_url, $post_id);
        }


        public function uploadImage($request) {
            $url = $request['url'];
            $post_id = $request['post_id'];

            $this->upload_media($url, $post_id);
        }

        public function upload_media($url, $post_id) {
            $current_user = wp_get_current_user();

            $image_url = $url;
        
            $upload_dir = wp_upload_dir();
        
            $image_data = file_get_contents($image_url);
        
            $filename = basename($image_url);
        
            if (wp_mkdir_p($upload_dir['path'])) {
                $file = $upload_dir['path'] . '/' . $filename;
            } else {
                $file = $upload_dir['basedir'] . '/' . $filename;
            }
        
            file_put_contents($file, $image_data);
        
            $wp_filetype = wp_check_filetype($filename, null);
        
            $attachment = array(
                'post_mime_type' => $wp_filetype['type'],
                'post_title' => sanitize_file_name($filename),
                'post_content' => '',
                'post_status' => 'inherit',
            );
        
            $attach_id = wp_insert_attachment($attachment, $file);
            require_once ABSPATH . 'wp-admin/includes/image.php';
            $attach_data = wp_generate_attachment_metadata($attach_id, $file);
            wp_update_attachment_metadata($attach_id, $attach_data);

            update_post_meta( $post_id, '_thumbnail_id', $attach_id );
            // $attach_id;
        }




        public function upload_image($url, $post_id) {
            $image = "";
            if($url != "") {
             
                $file = array();
                $file['name'] = $url;
                $file['tmp_name'] = download_url($url);
         
                if (is_wp_error($file['tmp_name'])) {
                    @unlink($file['tmp_name']);
                    var_dump( $file['tmp_name']->get_error_messages( ) );
                } else {
                    $attachmentId = media_handle_sideload($file, $post_id);
                     
                    if ( is_wp_error($attachmentId) ) {
                        @unlink($file['tmp_name']);
                        var_dump( $attachmentId->get_error_messages( ) );
                    } else {                
                        $image = wp_get_attachment_url( $attachmentId );
                    }
                }
            }
            return $image;
        }

    }

}
