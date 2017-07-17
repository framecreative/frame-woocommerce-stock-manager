<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

class WCM_Stock {

  /**
	 * Instance of this class.
	 *
	 * @since    1.0.0
	 *
	 * @var      object
	 */
	protected static $instance = null;
  
  /**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.0
	 */
  public $limit = 100; 
   

	/**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.0
	 */
	private function __construct() {
    
	}
  
  /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.0
	 *
	 * @return    object    A single instance of this class.
	 */
	public static function get_instance() {

    

		// If the single instance hasn't been set, set it now.
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}
  
  
  	/**
  	 * Return products
  	 *
  	 *   
  	 * @since 1.0.0  
  	 */        
  	public function get_products($data = array()){
  
    	if(isset($_GET['sku'])){ return $this->get_product_by_sku($_GET['sku']); }
        if(isset($_GET['product-title'])){ return $this->get_product_by_product_title($_GET['product-title']); }
  
    	$args = array();
    	$args['post_type'] = 'product';

    	//Inicialize tax_query array
    	if( !empty( $_GET['product-type'] ) ||  !empty( $_GET['product-category'] ) ){
    			$args['tax_query'] = array();	
    	

    		if(isset($_GET['product-type'])){
      			if($_GET['product-type'] == 'variable'){
        		        
        			$args['tax_query'][] = array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'variable',
										'field' 	  => 'slug'
								
								);
        
      			}else{
        		
        			$args['tax_query'] = array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'simple',
										'field' 	  => 'slug'
								);
      			}

    		}

    	

    	/**
    	 * Product category filter
    	 */         
    	if( isset( $_GET['product-category'] ) ){
      		if( $_GET['product-category'] != 'all' ){
      
      			$category = $_GET['product-category'];
      
      			$args['tax_query'][] = array(
										'taxonomy' 	=> 'product_cat',
										'terms' 	  => $category,
										'field' 	  => 'term_id'
								);   
      		}
    	}
   
    	}	

    	//Inicialize meta_query array
    	if( !empty( $_GET['stock-status'] ) || !empty( $_GET['manage-stock'] ) ){
    		$args['meta_query'] = array();	
    	

   			if(!empty($_GET['stock-status'])){ 
      			$status = $_GET['stock-status'];
   
      			$args['meta_query'][] = array(
      					'key'     => '_stock_status',
						'value'   => $status,
						'compare' => '=',
      			);

   			}
   
   			if(!empty($_GET['manage-stock'])){ 
      			$manage = $_GET['manage-stock'];
      
      			$args['meta_query'][] = array(
      					'key'     => '_manage_stock',
						'value'   => $manage,
						'compare' => '=',
      			);

   			}
   		}

   		if(isset($_GET['order-by'])){ 
      		$order_by = $_GET['order-by'];

      		if( $order_by == 'name-asc' ){

      			$args['orderby'] = 'title';
				$args['order'] = 'ASC';

      		}
      		elseif( $order_by == 'name-desc' ){

      			$args['orderby'] = 'title';
				$args['order'] = 'DESC';

   			}
   			elseif( $order_by == 'sku-asc' ){

      			$args['meta_key'] = '_sku';
      			$args['orderby'] = 'meta_value_num';
				$args['order'] = 'ASC';   				

   			}
   			elseif( $order_by == 'sku-desc' ){

   				$args['meta_key'] = '_sku';
      			$args['orderby'] = 'meta_value_num';
				$args['order'] = 'DESC';

   			}


   		}

  
    	$args['posts_per_page'] = $this->limit;


    	if(!empty($_GET['offset'])){
      		$offset = $_GET['offset'] - 1;
      		$offset = $offset * $this->limit;
      		$args['offset'] = $offset;

    	}
  	
  
    	$the_query = new WP_Query( $args );
    
    	return $the_query;
  	} 
  
  /**
   * Return all products
   *
   *   
   * @since 1.0.0  
   */        
  	public function get_all_products(){
  
    
    
    
    $args = array();
    
    if(isset($_GET['product-type'])){
      if($_GET['product-type'] == 'variable'){
        $args['post_type'] = 'product';
        
        $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'variable',
										'field' 	  => 'slug'
									)
								);
        
      }else{
        $args['post_type'] = 'product';
        $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_type',
										'terms' 	  => 'simple',
										'field' 	  => 'slug'
									)
								);
      }
    }else{
        $args['post_type'] = 'product';
    }
    
    
    /**
     * Product category filter
     */         
    if(isset($_GET['product-category'])){
      if($_GET['product-category'] != 'all'){
      
      $category = $_GET['product-category'];
      
      $args['tax_query'] = array(
									array(
										'taxonomy' 	=> 'product_cat',
										'terms' 	  => $category,
										'field' 	  => 'term_id'
									)
								);   
      }
    }
   
   if(isset($_GET['stock-status'])){ 
      $status = $_GET['stock-status'];
   
      $args['meta_key']   = '_stock_status';
      $args['meta_value'] = $status;
   }
   
   if(isset($_GET['manage-stock'])){ 
      $manage = $_GET['manage-stock'];
      
      $args['meta_key']   = '_manage_stock';
      $args['meta_value'] = $manage;
   }
    
    
    
    
    
    $args['posts_per_page'] = -1;
    

    $the_query = new WP_Query( $args );
    
    return $the_query->posts;
  }   
  
  /**
   * Return all products
   *
   *   
   * @since 1.0.0  
   */        
  public function get_products_for_export(){
  
    $args = array();
    $args['post_type'] = 'product';
    $args['posts_per_page'] = -1;
    
    $the_query = new WP_Query( $args );
    
    return $the_query->posts;
  }   
  
  /**
   * Return pagination
   *
   */        
  public function pagination( $query ){
     
     if(isset($_GET['sku'])){ return false; }
     
     $all = $query->found_posts;
     $pages = ceil($all / $this->limit);
     if(!empty($_GET['offset'])){
       $current = $_GET['offset'];
     }else{
       $current = 1;
     }
     
     $html = '';
     $html .= '<div class="stock-manager-pagination">';
     $query_string = $_SERVER['QUERY_STRING'];
     if($pages != 1){
     
      for ($i=1; $i <= $pages; $i++){
        if($current == $i){
            $html .= '<span class="btn btn-default">'.$i.'</span>';
        }else{
            $html .= '<a class="btn btn-primary" href="'.admin_url().'admin.php?'.$query_string.'&offset='.$i.'">'.$i.'</a>';
        }
      }
     
     }
     
     $html .= '</div>';
     
     return $html;
  }  
  
  /**
   * Save all meta data
   *
   */        
    public function save_all($data){

        foreach($data['product_id'] as $key => $item){
  
            $_product = wc_get_product( $item );

            $sku          = sanitize_text_field($data['sku'][$item]);
            update_post_meta($item, '_sku', $sku);

            if( !empty( $data['manage_stock'] ) ){
                $manage_stock = sanitize_text_field($data['manage_stock'][$item]);
                update_post_meta($item, '_manage_stock', $manage_stock);
            }
            
            if( !empty( $data['backorders'] ) ){
                $backorders   = sanitize_text_field($data['backorders'][$item]);
                update_post_meta($item, '_backorders', $backorders);
            }
            if( !empty( $data['stock'] ) ){
                $stock        = sanitize_text_field($data['stock'][$item]);
                wc_update_product_stock( $_product, $stock );
            }

            if( !empty( $data['stock_status'] ) ){
            
                $stock_status = sanitize_text_field( $data['stock'][$item] );
                if( $data['manage_stock'][$item] == 'yes' ){
                    
                    if( $data['backorders'][$item] == 'no' ){
                        if( $data['stock'][$item] > 0 ){
                            update_post_meta($item, '_stock_status', 'instock');
                        }else{
                            update_post_meta($item, '_stock_status', 'outofstock');
                        }                   
                    }else{
                        update_post_meta($item, '_stock_status', 'instock');
                    }
                
                }else{
                    update_post_meta($item, '_stock_status', $stock_status);
                }
                
            }


            if( !empty( $data['weight'] ) ){
                $weight       = sanitize_text_field($data['weight'][$item]);
                update_post_meta($item, '_weight', $weight);
            }
                      
            if( !empty( $data['regular_price'] ) ){
                $price        = sanitize_text_field($data['regular_price'][$item]);
                update_post_meta( $item, '_price', $price );
                update_post_meta( $item, '_regular_price', $price );
                $sale_price   = sanitize_text_field($data['sales_price'][$item]);
                update_post_meta( $item, '_sale_price', $sale_price );                
            }         

            $product_type = $_product->get_type();
            if($product_type == 'product_variation'){

                $parent_id = wp_get_post_parent_id( $item );
                $parent_product = wc_get_product( $parent_id );

                $data_store = WC_Data_Store::load( 'product-' . $parent_product->get_type() );
                $data_store->sync_price( $parent_product );


            }
      
                        

            

            
     
        }   
    }

    /**
   * Save all meta data
   *
   */        
    public function save_filter_display($data){

        $option = array();
        
        if( !empty( $data['price'] ) ){ $option['price'] = 'display'; }else{ $option['price'] = 'no'; }
        if( !empty( $data['sales_price'] ) ){ $option['sales_price'] = 'display'; }else{ $option['sales_price'] = 'no'; }
        if( !empty( $data['weight'] ) ){ $option['weight'] = 'display'; }else{ $option['weight'] = 'no'; }
        if( !empty( $data['manage_stock'] ) ){ $option['manage_stock'] = 'display'; }else{ $option['manage_stock'] = 'no'; }
        if( !empty( $data['stock_status'] ) ){ $option['stock_status'] = 'display'; }else{ $option['stock_status'] = 'no'; }
        if( !empty( $data['backorders'] ) ){ $option['backorders'] = 'display'; }else{ $option['backorders'] = 'no'; }
        if( !empty( $data['stock'] ) ){ $option['stock'] = 'display'; }else{ $option['stock'] = 'no'; }


        if( !empty( $option ) ){
            update_option( 'wsm_display_option', $option );
        }
     
        
    }
  
  /**
   *
   * Get prduct categories 
   *
   */   
  public function products_categories($selected = null){
    $out = '';
    
    
    
    
    $terms = get_terms(
                      'product_cat', 
                      array(
                            'hide_empty' => 0, 
                            'orderby' => 'ASC'
                      )
    );
    if(count($terms) > 0)
    {
        foreach ($terms as $term)
        {
            if(!empty($selected) && $selected == $term->term_id){ $sel = 'selected="selected"'; }else{ $sel = ''; }
            $out .= '<option value="'.$term->term_id.'" '.$sel.'>'.$term->name.'</option>';
        }
        return $out;
    }
    return;
  }
  
    /**
     * Get products by sku
     *
     */
    private function get_product_by_sku($sku){
        $args = array();
        $args['post_type']  = 'product';
        $args['meta_query'] = array(
            array(
                'key'       => '_sku',
                'value'     => $sku,
                'compare'   => 'LIKE'
            )
        );
   
        $the_query = new WP_Query( $args );
    
        return $the_query;
  
    }   

        /**
     * Get products by sku
     *
     */
    private function get_product_by_product_title($title){

        add_filter( 'posts_search', 'wsm_search_by_title_only', 500, 2 );

        $args = array();
        $args['post_type']      = 'product';
        $args['s']              = $title;
        $args['post_status']    = 'publish';
        $args['orderby']        = 'title';
        $args['order']          = 'ASC';
 
        $the_query = new WP_Query( $args );

        remove_filter( 'posts_search', 'wsm_search_by_title_only' );
    
        return $the_query;
  
    }         
  
  
  
}//End class  