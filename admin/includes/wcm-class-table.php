<?php
/**
 * @package   WooCommerce Stock Manager
 * @author    Vladislav Musílek
 * @license   GPL-2.0+
 * @link      http:/toret.cz
 * @copyright 2015 Toret.cz
 */

class WCM_Table {

  /**
	 * Instance of this class.
	 *
	 * @since    1.0.5
	 *
	 * @var      object
	 */
	protected static $instance = null;
  
  
	/**
	 * Constructor for the stock class.
	 *
	 * @since     1.0.5
	 */
	private function __construct() {

		
    
	}
  
  /**
	 * Return an instance of this class.
	 *
	 * @since     1.0.5
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
   	 * Row filter
   	 *
  	 * @since     1.0.5
  	 */           
  	public static function row_filter($product_meta, $id){
  	
  
  	}  

    /**
     * Display option
     *
     * @since     1.1.2
     */           
    public static function display_option(){
        $display_option = get_option( 'wsm_display_option' );
        
        if( empty( $display_option ) ){
            $display_option = array(  
                'price'         => 'display',
                'sales_price'   => 'no',
                'weight'        => 'display',
                'manage_stock'  => 'display',
                'stock_status'  => 'display',
                'backorders'    => 'display',
                'stock'         => 'display',
            );
        }
        return $display_option;
    }

    /**
     * Table header
     *
     * @since     1.1.2
     */           
    public static function table_header_line(){

        $display_option = self::display_option(); 

        self::table_head_sku();
        self::table_head_id();
        self::table_head_name();
        self::table_head_product_type();
        self::table_head_parent_id();

        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::table_head_price();
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::table_head_sales_price();
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::table_head_weight();
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::table_head_manage_stock();
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::table_head_stock_status();
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::table_head_backorders();
        }

        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::table_head_stock();
        }
        do_action( 'stock_manager_table_th' );
        self::table_head_save();

    } 

    /**
     * Table simple line
     *
     * @since     1.1.2
     */           
    public static function table_simple_line( $product_meta, $item ){

        $display_option = self::display_option();
        
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::price_box($product_meta, $item->ID);
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::sales_price_box($product_meta, $item->ID); 
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::weight_box($product_meta, $item->ID); 
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::manage_stock_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::stock_status_box($product_meta, $item); 
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::backorders_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::qty_box($product_meta, $item);
        }


    }

    /**
     * Table variation line
     *
     * @since     1.1.2
     */           
    public static function table_variation_line( $product_meta, $item ){

        $display_option = self::display_option();
        
        if(!empty( $display_option['price'] ) && $display_option['price'] == 'display' ){
            self::price_box($product_meta, $item->ID);
        }
        if(!empty( $display_option['sales_price'] ) && $display_option['sales_price'] == 'display' ){
            self::sales_price_box($product_meta, $item->ID); 
        }
        if(!empty( $display_option['weight'] ) && $display_option['weight'] == 'display' ){
            self::weight_box($product_meta, $item->ID); 
        }
        if(!empty( $display_option['manage_stock'] ) && $display_option['manage_stock'] == 'display' ){
            self::manage_stock_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock_status'] ) && $display_option['stock_status'] == 'display' ){
            self::stock_status_box($product_meta, $item); 
        }
        if(!empty( $display_option['backorders'] ) && $display_option['backorders'] == 'display' ){
            self::backorders_box($product_meta, $item); 
        }
        if(!empty( $display_option['stock'] ) && $display_option['stock'] == 'display' ){
            self::qty_box($product_meta, $item);
        }


    }

    /**
     * Table head Sku
     *
     * @since     1.1.2
     */           
    public static function table_head_sku(){
        ?>
        <th style="width:100px;"><?php _e('SKU','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head ID
     *
     * @since     1.1.2
     */           
    public static function table_head_id(){
        ?>
        <th><?php _e('ID','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Name
     *
     * @since     1.1.2
     */           
    public static function table_head_name(){
        ?>
        <th><?php _e('Name','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Product type
     *
     * @since     1.1.2
     */           
    public static function table_head_product_type(){
        ?>
        <th><?php _e('Product type','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Parent ID
     *
     * @since     1.1.2
     */           
    public static function table_head_parent_id(){
        ?>
        <th><?php _e('Parent ID','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Price
     *
     * @since     1.1.2
     */           
    public static function table_head_price(){
        ?>
        <th><?php _e('Price','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Price
     *
     * @since     1.1.2
     */           
    public static function table_head_sales_price(){
        ?>
        <th><?php _e('Sale price','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Weight
     *
     * @since     1.1.2
     */           
    public static function table_head_weight(){
        ?>
        <th><?php _e('Weight','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Manage stock
     *
     * @since     1.1.2
     */           
    public static function table_head_manage_stock(){
        ?>
        <th><?php _e('Manage stock','stock-manager'); ?></th>
        <?php
    }
    /**
     * Table head Stock status
     *
     * @since     1.1.2
     */           
    public static function table_head_stock_status(){
        ?>
        <th style="width:80px;"><?php _e('Stock status','stock-manager'); ?></th>
        <?php
    }
    /**
     * Table head Backorders
     *
     * @since     1.1.2
     */           
    public static function table_head_backorders(){
        ?>
        <th><?php _e('Backorders','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Stock
     *
     * @since     1.1.2
     */           
    public static function table_head_stock(){
        ?>
        <th style="width:50px;"><?php _e('Stock','stock-manager'); ?></th>
        <?php
    } 
    /**
     * Table head Save
     *
     * @since     1.1.2
     */           
    public static function table_head_save(){
        ?>
        <th style="width:100px;"><?php _e('Save','stock-manager'); ?></th>
        <?php
    } 

 


    /**
     * Hidden box
     *
     * @since     1.1.2
     */           
    public static function hidden_box( $item ){
        ?>
        <input type="hidden" name="product_id[<?php echo $item->ID; ?>]" value="<?php echo $item->ID; ?>" />
        <?php
    } 

  	/**
  	 * SKU box
  	 *
  	 * @since     1.1.2
  	 */           
  	public static function sku_box($product_meta, $item){
  		?>
  		  <td class="item_sku_box">
            <span class="item-sku-text-<?php echo $item->ID; ?>"><?php if(!empty($product_meta['_sku'][0])){ echo $product_meta['_sku'][0]; } ?></span>
                <span class="dashicons dashicons-edit" data-item="<?php echo $item->ID; ?>"></span>
                <div class="item-sku-wrap item-sku-wrap-<?php echo $item->ID; ?>">
                    <input type="text" name="sku[<?php echo $item->ID; ?>]" style="width:100%;" class="item-sku sku_<?php echo $item->ID; ?>" value="<?php if(!empty($product_meta['_sku'][0])){ echo $product_meta['_sku'][0]; } ?>" />
                    <span class="btn btn-info item-sku-button" data-item="<?php echo $item->ID; ?>"><?php _e('Save', 'stock-manager'); ?></span>
                    <span class="btn btn-danger item-sku-button-close"><?php _e('Close', 'stock-manager'); ?></span>
                </div>
        </td>
  		<?php
  	} 

  	/**
  	 * ID box
  	 *
  	 * @since     1.1.2
  	 */           
  	public static function id_box( $item ){
  		?>
  		<td class="td_center"><?php echo $item->ID; ?></td>
  		<?php
  	} 

        /**
         * Name box
         *
         * @since     1.1.2
         */           
        public static function name_box( $item ){
  		    ?>
  		    <td class="table_name_box">
                <a href="<?php echo admin_url().'post.php?post='.$item->ID.'&action=edit'; ?>" target="_blank" class="item-post-link-<?php echo $item->ID; ?>">
                    <?php echo $item->post_title; ?>  
                </a>                
                <span class="dashicons dashicons-edit" data-item="<?php echo $item->ID; ?>"></span>
                <div class="item-post-title-wrap item-post-title-wrap-<?php echo $item->ID; ?>">
                    <input type="text" name="item-post-title" class="item-post-title item-post-title-<?php echo $item->ID; ?>" value="<?php echo $item->post_title; ?>" />
                    <span class="btn btn-info item-post-title-button" data-item="<?php echo $item->ID; ?>"><?php _e('Save', 'stock-manager'); ?></span>
                    <span class="btn btn-danger item-post-title-button-close"><?php _e('Close', 'stock-manager'); ?></span>
                </div>
            </td>
            <?php
        } 

  	/**
  	 * Show variables box
  	 *
  	 * @since     1.1.2
  	 */           
  	public static function show_variables_box( $item, $product_type ){
  		?>
  		<td class="td_center">
            <?php if($product_type == 'variable'){
              echo '<span class="btn btn-info btn-sm show-variable" data-variable="'.$item->ID.'">'.__('Show variables','stock-manager').'</span>';
            }else{ 
              echo $product_type; 
            } ?>
          </td>
  		<?php
  	} 


  /**
   * Price box
   *
   * @since     1.1.2
   */           
  public static function price_box($product_meta, $id){
  ?>
  <td>
    <input class="line-price regular_price_<?php echo $id; ?>" name="regular_price[<?php echo $id; ?>]" type="number" min="<?php echo wsm_get_step(); ?>" step="<?php echo wsm_get_step(); ?>" <?php if(!empty($product_meta['_regular_price'][0])){ echo 'value="'.$product_meta['_regular_price'][0].'"'; } ?> />
  </td>
  <?php
  }  

    /**
     * Sales Price box
     *
     * @since     1.1.2
     */           
    public static function sales_price_box($product_meta, $id){
        ?>
        <td>
            <input class="line-price sales_price_<?php echo $id; ?>" name="sales_price[<?php echo $id; ?>]" type="number" min="<?php echo wsm_get_step(); ?>" step="<?php echo wsm_get_step(); ?>" <?php if(!empty($product_meta['_sale_price'][0])){ echo 'value="'.$product_meta['_sale_price'][0].'"'; } ?> />
        </td>
        <?php
    }  

    /**
     * Weight box
     *
     * @since     1.1.2
     */           
    public static function weight_box($product_meta, $id){
        ?>
        <td>
            <input class="line-price weight_<?php echo $id; ?> wc_input_decimal" name="weight[<?php echo $id; ?>]" <?php if(!empty($product_meta['_weight'][0])){ echo 'value="'.$product_meta['_weight'][0].'"'; } ?> />
        </td>
        <?php
    }  

    /**
     * Manage stock box
     *
     * @since     1.1.2
     */           
    public static function manage_stock_box($product_meta, $item){
        ?>
        <td>
            <select name="manage_stock[<?php echo $item->ID; ?>]" class="manage_stock_<?php echo $item->ID; ?>">
              <option value="yes" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'yes'){ echo 'selected="selected"'; } ?>><?php _e('Yes','stock-manager'); ?></option>
              <option value="no" <?php if(!empty($product_meta['_manage_stock'][0]) && $product_meta['_manage_stock'][0] == 'no'){ echo 'selected="selected"'; } ?>><?php _e('No','stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  
    /**
     * Stock status box
     *
     * @since     1.1.2
     */           
    public static function stock_status_box($product_meta, $item){
        ?>
        <td>
            <select name="stock_status[<?php echo $item->ID; ?>]" class="stock_status_<?php echo $item->ID; ?>">
              <option value="instock" <?php if(!empty($product_meta['_stock_status'][0]) && $product_meta['_stock_status'][0] == 'instock'){ echo 'selected="selected"'; } ?>><?php _e('In stock','stock-manager'); ?></option>
              <option value="outofstock" <?php if(!empty($product_meta['_stock_status'][0]) && $product_meta['_stock_status'][0] == 'outofstock'){ echo 'selected="selected"'; } ?>><?php _e('Out of stock','stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  
    /**
     * Backorders box
     *
     * @since     1.1.2
     */           
    public static function backorders_box($product_meta, $item){
        ?>
        <td>
            <select name="backorders[<?php echo $item->ID; ?>]" class="backorders_<?php echo $item->ID; ?>">
              <option value="no" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'no'){ echo 'selected="selected"'; } ?>><?php _e('No','stock-manager'); ?></option>
              <option value="notify" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'notify'){ echo 'selected="selected"'; } ?>><?php _e('Notify','stock-manager'); ?></option>
              <option value="yes" <?php if(!empty($product_meta['_backorders'][0]) && $product_meta['_backorders'][0] == 'yes'){ echo 'selected="selected"'; } ?>><?php _e('Yes','stock-manager'); ?></option>
            </select>
          </td>
        <?php
    }  

    /**
     * Weight box
     *
     * @since     1.1.2
     */           
    public static function qty_box($product_meta, $item){
        
        $class = '';
        if(!empty($product_meta['_stock'])){
            if($product_meta['_stock'][0] < 1){ 
                $stock_number = $product_meta['_stock'][0];
                $class = 'outofstock';
            }else{ 
                $stock_number = $product_meta['_stock'][0];
                if($product_meta['_stock'][0] < 5){ 
                    $class = 'lowstock'; 
                }else{
                    $class = 'instock';
                } 
            } 
        }else{
            $class = '';
            $stock_number = 0;
        }
        $_product = wc_get_product( $item );
        $product_type = $_product->get_type();
            if($product_type == 'variable'){
                //Show count all variations stock.

                ?>
                <td class="td_center <?php echo $class; ?>" style="width:70px;">
                    <input type="number" name="stock[<?php echo $item->ID; ?>]" step="1" value="<?php echo round($stock_number); ?>" class="stock_<?php echo $item->ID; ?>" style="width:70px;" />
                </td>
            <?php
            }else{
            ?>
                <td class="td_center <?php echo $class; ?>" style="width:70px;">
                    <input type="number" name="stock[<?php echo $item->ID; ?>]" step="1" value="<?php echo round($stock_number); ?>" class="stock_<?php echo $item->ID; ?>" style="width:70px;" />
                </td>
            <?php
            }
    }  

    /**
     * Line nonce box
     *
     * @since     1.1.2
     */           
    public static function line_nonce_box($item){       
        ?>
        <input type="hidden" name="wsm-ajax-nonce-<?php echo $item->ID; ?>" class="wsm-ajax-nonce_<?php echo $item->ID; ?>" value="<?php echo wp_create_nonce( 'wsm-ajax-nonce-'.$item->ID ); ?>" />
        </td>
        <?php
    } 
    /**
     * Line save box
     *
     * @since     1.1.2
     */           
    public static function line_save_box($item){       
        ?>
        <td class="td_center">
            <span class="btn btn-primary btn-sm save-product" data-product="<?php echo $item->ID; ?>"><?php _e('Save','stock-manager'); ?></span>
        </td>
        <?php
    }  
  
  
  
}//End class  