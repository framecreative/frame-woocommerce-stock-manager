<?php
/**
 * Plugin Name:       Frame WooCommerce Stock Manager
 * Plugin URI:        https://github.com/framedigital/woocommerce-stock-manager
 * Description:       WooCommerce Stock Manager
 * Version:           1.1.7
 * Author:            F / R / A / M / E Creative / Vladislav Musílek
 * Author URI:        https://framecreative.com.au
 * Text Domain:       stock-manager
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path:       /languages
 * Github Plugin URI: https://github.com/framedigital/woocommerce-stock-manager
 * Github Branch:     master
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

// Allow this to be overridden, and default to something SANE like a comma.

if ( ! defined( 'WC_STOCK_CSV_SEPERATOR' ) ) {
    define('WC_STOCK_CSV_SEPERATOR', ',');
}


define( 'STOCKDIR', plugin_dir_path( __FILE__ ) );
define( 'STOCKURL', plugin_dir_url( __FILE__ ) );
/*----------------------------------------------------------------------------*
 * Public-Facing Functionality
 *----------------------------------------------------------------------------*/
require_once( plugin_dir_path( __FILE__ ) . 'public/class-stock-manager.php' );

register_activation_hook( __FILE__, array( 'Stock_Manager', 'activate' ) );
register_deactivation_hook( __FILE__, array( 'Stock_Manager', 'deactivate' ) );

add_action( 'plugins_loaded', array( 'Stock_Manager', 'get_instance' ) );

if ( is_admin() && ( ! defined( 'DOING_AJAX' ) || ! DOING_AJAX ) ) {

	require_once( plugin_dir_path( __FILE__ ) . 'admin/class-stock-manager-admin.php' );
	add_action( 'plugins_loaded', array( 'Stock_Manager_Admin', 'get_instance' ) );

}







  add_action( 'wp_ajax_save_one_product', 'stock_manager_save_one_product_stock_data' );

  /**
   * Save one product stock data
   *
   */
  function stock_manager_save_one_product_stock_data(){

    if( current_user_can('manage_woocommerce') ){

        $product_id   = sanitize_text_field( $_POST['product'] );

        check_ajax_referer( 'wsm-ajax-nonce-'.$product_id, 'secure' );

        $sku          = sanitize_text_field($_POST['sku']);

        $manage_stock = sanitize_text_field( $_POST['manage_stock'] );
        $stock_status = sanitize_text_field( $_POST['stock_status'] );
        $backorders   = sanitize_text_field( $_POST['backorders'] );
        $stock        = sanitize_text_field( $_POST['stock'] );
        $weight       = sanitize_text_field( $_POST['weight'] );

        update_post_meta( $product_id, '_sku', $sku );
        update_post_meta( $product_id, '_manage_stock', $manage_stock );
        update_post_meta( $product_id, '_stock_status', $stock_status );
        update_post_meta( $product_id, '_backorders', $backorders );

        $_product = wc_get_product( $product_id );
        //Set stock via product class
        wc_update_product_stock( $_product, $stock );

            //Custom update stock status
                if( $manage_stock == 'yes' ){

                    if( $backorders == 'no' ){
                        if( $stock > 0 ){
                            update_post_meta($product_id, '_stock_status', 'instock');
                        }else{
                            update_post_meta($product_id, '_stock_status', 'outofstock');
                        }
                    }else{
                        update_post_meta($product_id, '_stock_status', 'instock');
                    }

                }else{
                    update_post_meta($product_id, '_stock_status', $stock_status);
                }

            if( !empty( $_POST['regular_price'] ) ){
                $price = sanitize_text_field($_POST['regular_price']);
                if( !empty( $_POST['sales_price'] ) ){
                    $sale_price   = sanitize_text_field($_POST['sales_price']);
                    wsm_save_price( $product_id, $price, $sale_price );
                }else{
                    wsm_save_price( $product_id, $price );
                }
            }

        update_post_meta($product_id, '_weight', $weight);

    }

     exit();
  }


add_action( 'wp_ajax_wsm_save_title_product', 'stock_manager_wsm_save_title_product' );

  /**
   * Save product title
   *
   */
  function stock_manager_wsm_save_title_product(){

    if( current_user_can('manage_woocommerce') ){

        //check_ajax_referer( 'wsm_update', 'security' );

        $item   = sanitize_text_field($_POST['item']);
        $title   = sanitize_text_field($_POST['title']);

        $args = array(
            'ID'           => $item,
            'post_title'   => $title,
        );

        $product_id = wp_update_post( $args );



    }
    echo $product_id;
    exit($product_id);
}

add_action( 'wp_ajax_wsm_save_sku', 'stock_manager_wsm_save_sku' );

  /**
   * Save product title
   *
   */
  function stock_manager_wsm_save_sku(){

    if( current_user_can('manage_woocommerce') ){

        //check_ajax_referer( 'wsm_update', 'security' );

        $item   = sanitize_text_field($_POST['item']);
        $sku   = sanitize_text_field($_POST['sku']);

        update_post_meta( $item, '_sku', $sku );



    }
    echo $item;
    exit();
}


  /**
   * Get WooCommerce setting for number field step
   *
   */
  function wsm_get_step(){
      $number = get_option('woocommerce_price_num_decimals');
      if( $number == '0' ){ $step = '1'; }
      if( $number == '1' ){ $step = '0.1'; }
      if( $number == '2' ){ $step = '0.01'; }
      if( $number == '3' ){ $step = '0.001'; }
      if( $number == '4' ){ $step = '0.0001'; }
      if( $number == '5' ){ $step = '0.00001'; }
      if( $number == '6' ){ $step = '0.000001'; }

      return $step;

  }


    /**
     *
     *
     */
    function wsm_save_price( $product_id, $regular_price, $sale_price = null ){

        //$sale_price =  get_post_meta( $product_id, '_sale_price', true );
        $date_from = get_post_meta( $product_id, '_sale_price_dates_from', true );
        $date_from = ( '' === $date_from ) ? '' : date( 'Y-m-d', $date_from );
        $date_to =  get_post_meta( $product_id, '_sale_price_dates_to', true );
        $date_to = ( '' === $date_to ) ? '' : date( 'Y-m-d', $date_to );

        if( $sale_price === null ){
            $sale_price =  get_post_meta( $product_id, '_sale_price', true );
        }
            _wc_save_product_price( $product_id, $regular_price, $sale_price, $date_from, $date_to );

    }



    function wsm_search_by_title_only( $search, &$wp_query ){
        global $wpdb;
        if ( empty( $search ) )
            return $search; // skip processing - no search term in query
        $q = $wp_query->query_vars;
        $n = ! empty( $q['exact'] ) ? '' : '%';
        $search = '';
        $searchand = '';
        foreach ( (array) $q['search_terms'] as $term ) {
            $term = esc_sql( $wpdb->esc_like( $term ) );
            $search .= "{$searchand}($wpdb->posts.post_title LIKE '{$n}{$term}{$n}')";
            $searchand = ' AND ';
        }
        if ( ! empty( $search ) ) {
            $search = " AND ({$search}) ";
            if ( ! is_user_logged_in() )
                $search .= " AND ($wpdb->posts.post_password = '') ";
        }
        return $search;
    }

